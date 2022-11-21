<?php
namespace tests\resources;

use extas\components\plugins\Plugin;
use extas\interfaces\routes\IRoute;
use extas\interfaces\stages\IStageApiBeforeRouteExecute;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class PluginBeforeRouteOk extends Plugin implements IStageApiBeforeRouteExecute
{
    public function __invoke(RequestInterface $request, ResponseInterface &$response, array &$args, IRoute $route): void
    {
        
    }
}
