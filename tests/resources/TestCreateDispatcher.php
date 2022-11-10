<?php
namespace tests\resources;

use extas\components\routes\descriptions\TRouteHelp;
use extas\components\routes\dispatchers\JsonDispatcher;
use extas\components\routes\TRouteCreate;

class TestCreateDispatcher extends JsonDispatcher
{
    use TRouteHelp;
    use TRouteCreate;

    protected string $repoName = 'routes';
    protected array $validators = [
        'isName'
    ];

    protected function isName(array $data): bool
    {
        return isset($data['name']);
    }
}
