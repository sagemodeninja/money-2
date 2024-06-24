<?php
namespace Api\Data;

use Framework\Api\Data\{DatabaseContext,DatabaseModel,DatabaseContextBuilder};
use Api\Data\Models\{AccountModel,UserModel, WalletModel};

class DataContext extends DatabaseContext
{
    public DatabaseModel $wallets;
    public DatabaseModel $accounts;
    public DatabaseModel $users;

    public function configure(DatabaseContextBuilder $builder)
    {
        $builder->addModel(WalletModel::class, 'wallets', table: 'wallet');
        $builder->addModel(AccountModel::class, 'accounts', table: 'account');
        $builder->addModel(UserModel::class, 'users');
    }
}
?>