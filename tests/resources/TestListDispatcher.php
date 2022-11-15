<?php
namespace tests\resources;

use extas\components\routes\descriptions\TRouteHelp;
use extas\components\routes\dispatchers\JsonDispatcher;
use extas\components\routes\TRouteList;

class TestListDispatcher extends JsonDispatcher
{
    use TRouteHelp;
    use TRouteList;

    public static $tmp = 0;

    protected string $repoName = 'routes';

    protected function getWhere(): array
    {
        if ($this->getRequestParameter('fail', '') == 'yes') {
            throw new \Exception('Fail');
        }
        
        return $this->getRequestData();
    }
}
