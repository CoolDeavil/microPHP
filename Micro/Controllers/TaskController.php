<?php


namespace API\Controllers;



use API\Core\App\Controller;
use API\Core\Session\Session;
use API\Core\Utils\Validator;
use API\Interfaces\ContainerInterface;
use API\Interfaces\RenderInterface;
use API\Interfaces\RepositoryInterface;
use API\Interfaces\ResourceInterface;
use API\Interfaces\RouterInterface;
use GuzzleHttp\Psr7\Response;
use API\Models\ToDoTask;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class TaskController extends Controller implements ResourceInterface
{
    private ToDoTask $task;
    private RepositoryInterface $conn;

    public function __construct(
        RouterInterface $router,
        RenderInterface $render,
        Validator $validator,
        RepositoryInterface $conn )
    {
        parent::__construct($router, $render, $validator);
        $this->router = $router;
        $this->render = $render;
        $this->validator = $validator;
        $this->conn = $conn;
        $this->task = new ToDoTask();

        $this->router->resource('/api/tasks', $this, 'taskCRUDService', INTEGER);
        $this->router->get('/tasks', [$this, 'index'], 'taskService.index');

    }

    public function index(ServerRequestInterface $request, ResponseInterface $response): Response
    {
        $tasks = $this->conn->getAll();
        $view = (string)$this->render->render("tasks/toDoTasks", ['tasks' => $tasks ]);
        $response->getBody()->write((string)$view);
        /**@var $response  Response */
        return $response;
    }
    public function create(ServerRequestInterface $request, ResponseInterface $response): Response
    {
        $data = $this->resolveRedirectData([
            'oldData',
            'errors',
        ]);
        $data['mode'] = 'CREATE';
        $data['validation_url'] = $this->router->generateURI('taskCRUDService.store',[]);
        $view = (string)$this->render->render("tasks/taskForm", $data );
        $response->getBody()->write((string)$view);
        /**@var $response  Response */
        return $response;
    }
    public function store(ServerRequestInterface $request) : Response
    {
        $this->validator->init($request);

        $this->validator->field('description')->rule('notEmpty');
        $this->validator->field('comments')->rule('notEmpty');
        $this->validator->field('comments')->rule('minLength')->val(10);

        $validated = $this->validator->validate();

        if($validated){
            Session::unsetKey('REDIRECT_DATA');
            $payload=[];

            $this->task->setDescription($this->validator->fetch('description'))
                ->setComments($this->validator->fetch('comments'))
                ->setEdited(time())
                ->setCreated(time())
                ->setCompleted(0);
            
                $this->conn->registerNew($this->task);

        }else{
            $payload = [
                'oldData' =>  $this->validator->fetchAll(),
                'errors' =>  $this->validator->fetchErrors(),
            ];
        }
        return $this->handleResponse(
            $request,
            $validated,
            $payload,
            $validated?
                $this->router->generateURI('taskCRUDService.index', []):
                $this->router->generateURI('taskCRUDService.create', []) );

    }
    public function edit(ServerRequestInterface $request, ResponseInterface $response): Response
    {
        $params = $request->getAttribute('PARAMS');
        extract($params);
        /**@var string $id */

        /**@var $task ToDoTask */
        $task = $this->conn->getByID($id);
        $data = $this->resolveRedirectData([
            'oldData',
            'errors',
        ]);
        if(empty(json_decode($data['oldData']))){
            $data['oldData'] = json_encode([
                'id' =>  $task->getId(),
                'description' =>  $task->getDescription(),
                'comments' =>  $task->getComments(),
            ]);
            $data['errors'] = json_encode([]);
        }

        $data['mode'] = 'EDIT';
        $data['task'] = $task;
        $data['validation_url'] = $this->router->generateURI('taskCRUDService.update',
            ['id' => $task->getId()]);

        $view = (string)$this->render->render("tasks/taskForm", $data );
        $response->getBody()->write((string)$view);
        /**@var $response  Response */
        return $response;
    }
    public function show(ServerRequestInterface $request, ResponseInterface $response): Response
    {
        $params = $request->getAttribute('PARAMS');
        extract($params);
        /**@var string $id */


        /**@var $task ToDoTask */
        $task = $this->conn->getByID($id);
        $data['oldData'] = json_encode([
            'id' =>  $task->getId(),
            'description' =>  $task->getDescription(),
            'comments' =>  $task->getComments(),
        ]);
        $data['errors'] = json_encode([]);
        $data['mode'] = 'SHOW';
        $data['task'] = $task;

        $view = (string)$this->render->render("tasks/taskForm", $data );
        $response->getBody()->write((string)$view);
        /**@var $response  Response */
        return $response;
    }
    public function update(ServerRequestInterface $request) : Response
    {
        $params = $request->getAttribute('PARAMS');
        extract($params);
        /**@var string $id */

        $this->validator->init($request);
        /**@var $task ToDoTask */
        $task = $this->conn->getByID($id);
        $mode = $this->validator->fetch('_mode');
        if($mode == "UPDATE_GENERAL"){
            $this->validator->field('description')->rule('NotEmpty');
            $this->validator->field('comments')->rule('NotEmpty');
            $this->validator->field('comments')->rule('MinLength')->val(10);
        }
        $validated = $this->validator->validate();

        if($validated){

            if($mode == "UPDATE_GENERAL"){
                $task->setDescription($this->validator->fetch('description'));
                $task->setComments($this->validator->fetch('comments'));
                $this->conn->updateTask($task);
            } elseif ($mode == "UPDATE_STATUS"){
                if($this->validator->fetch('completed') == 'on'){
                    $task->setCompleted(1)
                        ->setDateCompleted(time());
                }else{
                    $task->setCompleted(0)
                        ->setDateCompleted(0);
                }
                $this->conn->updateTask($task);
            }
            Session::unsetKey('REDIRECT_DATA');
            $payload=[];

        }else{
            $payload=[
                'oldData' => [
                    'description' => $this->validator->fetch('description'),
                    'comments' => $this->validator->fetch('comments'),
                ],
                'errors' => $this->validator->fetchErrors()
            ];
        }
        return $this->handleResponse(
            $request,
            $validated,
            $payload,
            $validated?$this->router->generateURI('taskCRUDService.index', []):
                $this->router->generateURI('taskCRUDService.edit', ['id' => $task->getId()])

        );
    }
    public function destroy(ServerRequestInterface $request) : Response
    {
        $params = $request->getAttribute('PARAMS');
        extract($params);
        /**@var string $id */
        $this->conn->deleteByID($id);
        return $this->handleResponse(
            $request,
            true,
            [],
            $this->router->generateURI('taskCRUDService.index', [])
        );
    }

}