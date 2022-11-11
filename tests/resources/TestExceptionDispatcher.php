<?php
namespace tests\resources;

use extas\components\routes\descriptions\TRouteHelp;
use extas\components\routes\dispatchers\JsonDispatcher;
use extas\components\routes\TRouteCreate;

class TestExceptionDispatcher extends JsonDispatcher
{
    use TRouteHelp;
    use TRouteCreate;

    protected string $repoName = 'routes2';
    protected array $validators = [];
}
