<?php
namespace tests\resources;

use extas\components\routes\descriptions\TRouteHelp;
use extas\components\routes\dispatchers\JsonDispatcher;
use extas\components\routes\TRouteUpdate;
use extas\interfaces\routes\IRoute;

class TestUpdateDispatcher extends JsonDispatcher
{
    use TRouteHelp;
    use TRouteUpdate;

    public static $tmp = 0;

    protected string $repoName = 'routes';

    protected function getWhere(): array
    {
        return [IRoute::FIELD__NAME => '/'];
    }
}
