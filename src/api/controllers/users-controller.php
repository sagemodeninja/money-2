<?php
namespace Api\Controllers;

use Framework\Api\Controller\ControllerBase;
use Framework\Api\Attributes\Get;
use Api\Data\DataContext;

class UsersController extends ControllerBase
{
    private readonly DataContext $context;

    public function __construct()
    {
        $this->context = new DataContext();
    }

    #[Get]
    public function GetAll() {
        $users = $this->context->users->all();
        return $this->Ok($users);
    }
}
?>