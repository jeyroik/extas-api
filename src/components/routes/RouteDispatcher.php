<?php
namespace extas\components\routes;

use extas\components\extensions\TExtendable;
use extas\interfaces\routes\IRouteDispatcher;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class RouteDispatcher implements IRouteDispatcher
{
    use TExtendable;

    protected ?RequestInterface $request = null;
    protected ?ResponseInterface $response = null;
    protected array $args = [];

    public function __construct($request, $response, $args)
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }

    abstract protected function getRequestData(): array;
    abstract protected function getRequestParameter(string $paramName, string $default = ''): mixed;
    abstract protected function setResponseData(array $data, string $errorMessage = ''): void;
}
