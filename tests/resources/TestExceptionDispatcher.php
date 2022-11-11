<?php
namespace tests\resources;

use extas\components\routes\descriptions\TRouteHelp;
use extas\components\routes\dispatchers\JsonDispatcher;
use extas\components\routes\TRouteCreate;

/**
 * @api__input_method post
 * @api__input.id(required=true,validate=\some\class\Name,description="ID",type=uuid,edges=[36])
 * @api__input.name(required=true,validate=\some\class\NameOther,description="Name",type=string,edges=[1,36])
 * 
 * @api__output.one \extas\interfaces\routes\IRoute
 */
class TestExceptionDispatcher extends JsonDispatcher
{
    use TRouteHelp;
    use TRouteCreate;

    protected string $repoName = 'routes2';
    protected array $validators = [];
}
