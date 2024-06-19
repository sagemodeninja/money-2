<?php
    namespace Api\Models;

    class UserModel
    {
        public int $id;
        public string $emailAddress;

        // public function getAll()
        // {
        //     $query = 'SELECT u.`Id`, u.`EmailAddress` FROM `users` u';
        //     return $this->fetchAll($query);
        // }
    }
?>