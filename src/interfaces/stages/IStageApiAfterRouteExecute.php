<?php
namespace extas\interfaces\stages;

use extas\interfaces\routes\IRoute;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface IStageApiAfterRouteExecute
{
    public const NAME = 'extas.api.after.route.execute';

    public function __invoke(RequestInterface $request, ResponseInterface &$response, array &$args, IRoute $route): void;
}
