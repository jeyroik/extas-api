<?php
namespace extas\interfaces\routes;

use extas\interfaces\extensions\IExtendable;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface IRouteDispatcher extends IExtendable
{
    public const SUBJECT = 'extas.route.dispatcher';

    public function __construct(RequestInterface $request, ResponseInterface $response, array $args);

    public function execute(): ResponseInterface;

    public function help(): ResponseInterface;

    public function getRoute(): string;
    public function setRoute(string $route): IRouteDispatcher;
}
