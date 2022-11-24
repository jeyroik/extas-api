<?php
namespace extas\components\routes;

use extas\components\extensions\TExtendable;
use extas\interfaces\routes\IRoute;
use extas\interfaces\routes\IRouteDispatcher;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class RouteDispatcher implements IRouteDispatcher
{
    use TExtendable;

    protected ?RequestInterface $request = null;
    protected ?ResponseInterface $response = null;
    protected array $args = [];
    protected ?IRoute $route = null;

    public function __construct($request, $response, $args, IRoute $route)
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;
        $this->route = $route;
    }

    public function getRoute(): IRoute
    {
        return $this->route;
    }

    public function setRoute(IRoute $route): IRouteDispatcher
    {
        $this->route = $route;

        return $this;
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }

    abstract protected function getRequestData(): array;
    abstract protected function getRequestParameter(string $paramName, string $default = ''): mixed;
    abstract protected function setResponseData(array $data, string $errorMessage = ''): void;
}
