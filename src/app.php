<?php
use Framework\Dependency\ServiceCollection;
use Framework\Middleware\{RequestPipeline,RequestPipelineBuilder};
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
        RequestPipeline::register($this->services, function (RequestPipelineBuilder $builder) {
            $builder->addMiddleware(StaticFilesMiddleware::class);
            $builder->addMiddleware(ViewMiddleware::class);
            $builder->addMiddleware(ApiMiddleware::class);
        });

        $this->services->addService(Server::class);
    }

    public function run()
    {
        $server =$this->services->getService(Server::class);
        $server->run();
    }
}
?>