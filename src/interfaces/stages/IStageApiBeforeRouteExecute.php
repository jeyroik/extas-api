<?php
namespace extas\interfaces\stages;

use extas\interfaces\routes\IRoute;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * If response will has status != 200 after plugins run, then it will be return without executing route.
 */
interface IStageApiBeforeRouteExecute
{
    public const NAME = 'extas.api.before.route.execute';

    public function __invoke(RequestInterface $request, ResponseInterface &$response, array &$args, IRoute $route): void;
}
