<?php
namespace tests\resources;

use extas\components\plugins\Plugin;
use extas\interfaces\routes\IRoute;
use extas\interfaces\stages\IStageApiAfterRouteExecute;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class PluginAfterRoute extends Plugin implements IStageApiAfterRouteExecute
{
    public function __invoke(RequestInterface $request, ResponseInterface &$response, array &$args, IRoute $route): void
    {
        $response = $response->withStatus(405);
    }
}
