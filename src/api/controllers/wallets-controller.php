<?php
namespace Api\Controllers;

use Framework\Api\Controller\ControllerBase;
use Framework\Api\Attributes\{FromBody, Post,Get};
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
        $query = 'INSERT INTO `wallet` (`UserId`, `DisplayName`) VALUES (:user_id, :display_name)';
        $params = [
            ':user_id' => $wallet->userId,
            ':display_name' => $wallet->displayName
        ];

        $this->context->wallets->execute($query, $params);
        return $this->Created();
    }
}
?>