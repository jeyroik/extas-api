<?php
namespace tests\resources;

use extas\components\routes\dispatchers\JsonDispatcher;
use extas\components\routes\TRouteView;
use extas\interfaces\routes\IRoute;
use Psr\Http\Message\ResponseInterface;

class TestViewDispatcher extends JsonDispatcher
{
    use TRouteView;

    public static $tmp = 0;

    protected string $repoName = 'routes';

    public function help(): ResponseInterface
    {
        return $this->response;
    }

    protected function getWhere(): array
    {
        if ($this->getRequestParameter('fail', '') == 'yes') {
            throw new \Exception('Fail');
        }

        return [IRoute::FIELD__NAME => '/'];
    }
}
