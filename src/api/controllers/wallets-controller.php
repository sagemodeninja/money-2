<?php
namespace Api\Controllers;

use Framework\Api\Controller\ControllerBase;
use Framework\Api\Attributes\{Get,Post,Patch,Delete,FromBody};
use Api\Data\DataContext;
use Api\Data\Models\WalletModel;

class WalletsController extends ControllerBase
{
    private DataContext $context;

    public function __construct(DataContext $context)
    {
        $this->context = $context;
    }

    #[Get]
    public function getAll()
    {
        $wallets = $this->context->wallets->where('Status', 1)->all();
        return $this->Ok($wallets);
    }

    #[Post]
    public function create(#[FromBody(WalletModel::class)] WalletModel $wallet)
    {
        $this->context->wallets->add($wallet)->save();
        return $this->Created();
    }

    #[Patch("{id}")]
    public function update(int $id, #[FromBody(WalletModel::class)] WalletModel $wallet)
    {
        $model = $this->context->wallets->where([['Id', $id],['Status',1]])->first();
        $model->displayName = $wallet->displayName;
        $this->context->wallets->save();
        return $this->Ok($model);
    }

    #[Delete("{id}")]
    public function delete(int $id)
    {
        $model = $this->context->wallets->where([['Id', $id],['Status',1]])->first();
        $model->status = 0;
        $this->context->wallets->save();
        return $this->NoContent();
    }
}
?>