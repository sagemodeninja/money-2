<?php
    namespace Api\Data;

    use Framework\Api\DatabaseContext;
    use Framework\Api\DatabaseModel;
    use Framework\Api\DatabaseContextBuilder;
    use Api\Models\AccountModel;
    use Api\Models\UserModel;

    class DataContext extends DatabaseContext
    {
        public DatabaseModel $accounts;
        public DatabaseModel $users;

        public function configure(DatabaseContextBuilder $builder)
        {
            $builder->addModel('accounts', AccountModel::class);
            $builder->addModel('users', UserModel::class);
        }
    }
?>