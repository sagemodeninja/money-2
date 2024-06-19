<?php
    namespace Api\Models;

    class AccountModel
    {
        public int $id;
        public string $shortCode;
        public string $title;
        public ?int $categoryId;
        public ?string $accountNumber;
        public ?string $bankIcon;

        // public static function getAll()
        // {
        //     $query = 'SELECT a.`Id`, a.`Shortcode`, a.`Title`, a.`CategoryId`, a.`AccountNumber`, a.`BankIcon` FROM `account` a WHERE a.`Status` = 1;';
        //     return AccountModel::fetchAll($query);
        // }
    }
?>