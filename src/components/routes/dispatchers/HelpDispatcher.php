<?php
namespace extas\components\routes\dispatchers;

use Psr\Http\Message\ResponseInterface;

class HelpDispatcher extends JsonDispatcher
{
    public static array $routesList = [];

    public function execute(): ResponseInterface
    {
        $this->setResponseData(self::$routesList);

        return $this->response;
    }

    public function help(): ResponseInterface
    {
        return $this->execute();
    }
}
