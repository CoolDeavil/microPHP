<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 09/12/2018
 * Time: 20:04
 */

namespace API\Models;


use API\Core\Container\MicroDI;
use API\Core\Utils\Translate;
use API\Interfaces\ContainerInterface;
use DateTime;

class ToDoTask
{

    private $id;
    private $description;
    private $completed;
    private $dateCompleted;
    private $created;
    private $edited;
    private $comments;

    private $timeLine;
    private $lastEdit;

    public function __construct()
    {
        $args = func_get_args();
        $numArgs = func_num_args();
        if ($numArgs === 0) {
            $this->id = (int)$this->id;
            $this->description = (string)$this->description;
            $this->completed = (int)$this->completed;
            $this->dateCompleted = (int)$this->dateCompleted;
            $this->comments = (string)$this->comments;
            $this->created = (int)$this->created?$this->created:0;
            $this->edited = (int)$this->edited?$this->edited:0;

            $this->timeLine = (string) ToDoTask::setTimeFromCreate();
            $this->lastEdit = (string) ToDoTask::setTimeFromEdit();
        } else {
            call_user_func_array([$this,'__construct1'], $args);
        }
    }
    public function __construct1()
    {
        $args = func_get_args();
        $this->description = (string)$args[0];
        $this->completed = 0;
        $this->created = time();
        $this->edited = time();
    }

    public function setTimeFromCreate(): ?string
    {
        if($this->getCreated() === 0){ return null;}
        $diff = ToDoTask::dateDiff(
            $this->getCreated(),
            time()
        );
        $this->timeLine = $diff;
        return $this->timeLine;
    }
    public function setTimeFromEdit(): ?string
    {
        if($this->getEdited() === 0){ return null;}
        $diff = ToDoTask::dateDiff(
            $this->getEdited(),
            time()
        );
        $this->lastEdit = $diff;
        return $this->lastEdit;
    }
    function dateDiff($start, $end): string
    {
        $d_start=null;
        $d_end=null;
        // Checks $start and $end format (timestamp only for more simplicity and portability)
        if ($end == 0) {
            return 'NaN';
        }
        $start  = date('Y-m-d H:i:s', $start);
        $end    = date('Y-m-d H:i:s', $end);
        try {
            $d_start = new DateTime($start);
        } catch (\Exception $e) {
        }
        try {
            $d_end = new DateTime($end);
        } catch (\Exception $e) {
        }
        $diff = $d_start->diff($d_end);

        $year     = (int) $diff->format('%y');
        $month    = (int) $diff->format('%m');
        $day      = (int) $diff->format('%d');
        $hour     = (int) $diff->format('%h');
        $min      = (int) $diff->format('%i');
        $sec      = (int) $diff->format('%s');

        $ioc = MicroDI::getInstance([]);
        $tra = $ioc->get(Translate::class);

        $dateDif =[
            0 => [
                'label' => $year>1?$tra->translate('YEARS'):$tra->translate('YEAR'),
                'value' => $year,
            ],
            1 => [
                'label' => $month>1?$tra->translate('MONTHS'):$tra->translate('MONTH'),
                'value' => $month,
            ],
            2 => [
                'label' => $day>1?$tra->translate('DAYS'):$tra->translate('DAY'),
                'value' => $day,
            ],
            3 => [
                'label' => $hour>1?$tra->translate('HOURS'):$tra->translate('HOUR'),
                'value' => $hour,
            ],
            4 => [
                'label' => $min>1?$tra->translate('MINUTES'):$tra->translate('MINUTE'),
                'value' => $min,
            ],
            5 => [
                'label' => $sec>1?$tra->translate('SECONDS'):$tra->translate('SECOND'),
                'value' => $sec,
            ],
        ];
        $timeLapse = '';
        foreach ($dateDif as $key => $data) {
            if ($data['value'] != '0') {
                if ($key == 5) {
                    if ($timeLapse != '') {
                        $timeLapse .= 'e ' .$data['value'] .' '. $data['label'] . ' ';
                    } else {
                        $timeLapse .= $data['value'] .' '. $data['label'] . ' ';
                    }
                } else {
                    $timeLapse .= $data['value'] .' '. $data['label'] . ' ';
                }
            }
        }
        return $timeLapse;
    }
    ## Helper Setter/Getter
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
    /**
     * @param string $description
     * @return ToDoTask
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return int
     */
    public function getCompleted(): int
    {
        return $this->completed;
    }
    /**
     * @param int $completed
     * @return ToDoTask
     */
    public function setCompleted(int $completed): self
    {
        $this->completed = $completed;
        return $this;
    }

    /**
     * @return int
     */
    public function getDateCompleted(): int
    {
        return $this->dateCompleted;
    }
    /**
     * @param int $dateCompleted
     * @return ToDoTask
     */
    public function setDateCompleted(int $dateCompleted): self
    {
        $this->dateCompleted = $dateCompleted;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }
    /**
     * @param mixed $created
     * @return ToDoTask
     */
    public function setCreated($created): self
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEdited()
    {
        return $this->edited;
    }
    /**
     * @param mixed $edited
     * @return ToDoTask
     */
    public function setEdited($edited): self
    {
        $this->edited = $edited;
        return $this;
    }

    /**
     * @return string
     */
    public function getComments(): string
    {
        return $this->comments;
    }
    /**
     * @param string $comments
     * @return ToDoTask
     */
    public function setComments(string $comments): self
    {
        $this->comments = $comments;
        return $this;
    }

    /**
     * @return string
     */
    public function getTimeLine(): string
    {
        return $this->timeLine;
    }

    public function getTaskDuration(): string
    {
        if($this->getCompleted() === 0){ return '';}
        return ToDoTask::dateDiff(
            $this->getCreated(),
            $this->getDateCompleted()
        );

    }

    /**
     * @return string
     */
    public function getLastEdit(): string
    {
        return $this->lastEdit;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return ToDoTask
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }


}
