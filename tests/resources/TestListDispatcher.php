<?php
namespace tests\resources;

use extas\components\routes\dispatchers\JsonDispatcher;
use extas\components\routes\TRouteList;
use Psr\Http\Message\ResponseInterface;

class TestListDispatcher extends JsonDispatcher
{
    use TRouteList;

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
        
        return $this->getRequestData();
    }
}
