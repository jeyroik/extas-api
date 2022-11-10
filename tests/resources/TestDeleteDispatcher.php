<?php
namespace tests\resources;

use extas\components\routes\descriptions\TRouteHelp;
use extas\components\routes\dispatchers\JsonDispatcher;
use extas\components\routes\TRouteDelete;
use extas\interfaces\routes\IRoute;

class TestDeleteDispatcher extends JsonDispatcher
{
    use TRouteHelp;
    use TRouteDelete;

    public static $tmp = 0;

    protected string $repoName = 'routes';

    protected function getWhere(): array
    {
        return [IRoute::FIELD__NAME => '/'];
    }
}
