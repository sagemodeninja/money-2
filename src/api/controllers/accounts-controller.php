<?php
    namespace Api\Controllers;

    use Framework\Api\ControllerBase;
    use Framework\Api\Attributes\Get;
    use Api\Data\DataContext;

    class AccountsController extends ControllerBase {
        private readonly DataContext $context;

        public function __construct()
        {
            $this->context = new DataContext();
        }

        #[Get]
        public function GetAll() {
            $query = 'SELECT a.`Id`, a.`Shortcode`, a.`Title`, a.`CategoryId`, a.`AccountNumber`, a.`BankIcon` FROM `account` a WHERE a.`Status` = 1;';
            $accounts = $this->context->accounts->fetchAll($query);
            return $this->Ok($accounts);
        }
    }
?>