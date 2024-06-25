<?php
namespace Api\Controllers;

use Framework\Api\Controller\ControllerBase;
use Framework\Api\Attributes\{FromBody,Get,Post};
use Api\Data\DataContext;
use Api\Data\Models\AccountModel;

class AccountsController extends ControllerBase
{
    private DataContext $context;

    public function __construct(DataContext $context)
    {
        $this->context = $context;
    }

    #[Get]
    public function getAll() {
        $accounts = $this->context->accounts->where('status', 1)->all();
        return $this->Ok($accounts);
    }

    #[Get("{id}")]
    public function getById(int $id) {
        $account = $this->context->accounts->where([['id', $id], ['status', 1]])->first();
        return $this->Ok($account);
    }

    #[Post]
    public function create(#[FromBody(AccountModel::class)] AccountModel $account)
    {
        // $query = 'INSERT INTO `account` (`title`, `shortCode`) VALUES (:title, :short_code)';
        // $params = [
        //     ':title' => $account->title,
        //     ':short_code' => $account->shortCode
        // ];

        // $this->context->accounts->execute($query, $params);
        return $this->Created();
    }
}
?>