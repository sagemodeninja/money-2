<?php
    namespace Api\Data;

    use Framework\Api\Data\DatabaseContext;
    use Framework\Api\Data\DatabaseModel;
    use Framework\Api\Data\DatabaseContextBuilder;
    use Api\Data\Models\AccountModel;
    use Api\Data\Models\UserModel;

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