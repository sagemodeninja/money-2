<?php
    namespace Api\Controllers;

    use Framework\Api\ControllerBase;
    use Framework\Api\Attributes\Get;

    class AccountsController extends ControllerBase {
        #[Get]
        public function GetAll() {
            // $accounts = AccountModel::getAll();
            return $this->Ok('test');
        }
    }
?>