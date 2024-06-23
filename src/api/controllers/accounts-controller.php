<?php
    namespace Api\Controllers;

    use Framework\Api\Controller\ControllerBase;
    use Framework\Api\Attributes\{FromBody, FromFile, Get, Post};
    use Api\Data\DataContext;
    use Api\Data\Models\AccountModel;

    class AccountsController extends ControllerBase {
        private readonly DataContext $context;

        public function __construct()
        {
            $this->context = new DataContext();
        }

        #[Get]
        public function getAll() {
            $query = 'SELECT a.`Id`, a.`Shortcode`, a.`Title`, a.`CategoryId`, a.`AccountNumber`, a.`BankIcon` FROM `account` a WHERE a.`Status` = 1;';
            $accounts = $this->context->accounts->fetchAll($query);
            return $this->Ok($accounts);
        }

        #[Post]
        public function create(#[FromBody(AccountModel::class)] AccountModel $account)
        {
            $query = 'INSERT INTO `account` (`Title`, `ShortCode`) VALUES (:title, :short_code)';
            $params = [
                ':title' => $account->title,
                ':short_code' => $account->shortCode
            ];

            $this->context->accounts->execute($query, $params);
            return $this->Created();
        }
    }
?>