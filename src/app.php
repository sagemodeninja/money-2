<?php
include_once "framework/service-extensions.php";

use Api\Data\DataContext;
use Framework\Dependency\ServiceCollection;
use Framework\Middleware\RequestPipelineBuilder;
use Framework\Middleware\Default\{StaticFilesMiddleware,ViewMiddleware,ApiMiddleware};
use Framework\Server;

class App
{
    private ServiceCollection $services;

    public function __construct()
    {
        $this->services = new ServiceCollection();
    }

    public function configure()
    {
        addMiddlewares($this->services, function (RequestPipelineBuilder $builder) {
            $builder->addMiddleware(StaticFilesMiddleware::class);
            $builder->addMiddleware(ViewMiddleware::class);
            $builder->addMiddleware(ApiMiddleware::class);
        });

        $this->services->addService(DataContext::class);

        addControllers($this->services);

        $this->services->addService(Server::class);
    }

    public function run()
    {
        $server =$this->services->getService(Server::class);
        $server->run();
    }
}
?>