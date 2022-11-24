<?php
namespace extas\interfaces\routes;

use extas\interfaces\extensions\IExtendable;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface IRouteDispatcher extends IExtendable
{
    public const SUBJECT = 'extas.route.dispatcher';

    public function __construct(RequestInterface $request, ResponseInterface $response, array $args, IRoute $route);

    public function execute(): ResponseInterface;

    public function help(): ResponseInterface;

    public function getRoute(): IRoute;
    public function setRoute(IRoute $route): IRouteDispatcher;
}
