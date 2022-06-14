<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 22/02/2019
 * Time: 17:36
 */

namespace API\Validation;

use API\Core\Utils\Translate;
use API\Core\Utils\Validation;
use API\Models\AppUser;
use API\Repository\AuthModelRepository;
use JetBrains\PhpStorm\Pure;

class CheckIfRegisteredUser extends Validation
{


    private AuthModelRepository $repository;
    private Translate $translate;

    #[Pure] public function __construct(Translate $translate, AuthModelRepository $repository)
    {
        parent::__construct($translate);
        $this->translate = $translate;
        $this->repository = $repository;
    }

    public function __invoke($email): ?string
    {
        $user = (new AppUser())
            ->setEmail($email);
        $result = $this->repository->checkIfRegisteredUser($user);
        if ($result !== null) {
            return '[CR] '.$this->lang->translate($result);
        }
        return null;
    }
}
