<?php
namespace Api\Controllers;

use Framework\Api\Controller\ControllerBase;
use Framework\Api\Attributes\Get;
use Api\Data\DataContext;

class UsersController extends ControllerBase {
    private readonly DataContext $context;

    public function __construct()
    {
        $this->context = new DataContext();
    }

    #[Get]
    public function GetAll() {
        $query = 'SELECT u.`Id`, u.`EmailAddress` FROM `users` u;';
        $users = $this->context->users->fetchAll($query);
        return $this->Ok($users);
    }
}
?>