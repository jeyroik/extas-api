<?php
namespace tests\resources;

use extas\components\routes\descriptions\TRouteHelp;
use extas\components\routes\dispatchers\JsonDispatcher;
use extas\components\routes\TRouteCreate;

/**
 * @api__input_method post
 * @api__input.id(required=true,validate=\extas\components\routes\validators\VUUID,description="ID",type=uuid,edges=[36])
 * @api__input.name(required=true,validate=0,description="Name",type=string,edges=[1,36])
 * 
 * @api__output.one \extas\interfaces\routes\IRoute
 */
class TestCreateDispatcher extends JsonDispatcher
{
    use TRouteHelp;
    use TRouteCreate;

    protected string $repoName = 'routes';
    protected array $validators = [
        'isName'
    ];

    public function getParamsDesc(): array
    {
        return [
            'input' => $this->getInputDescription(),
            'output' => $this->getOutputDescription()
        ];
    }

    protected function isName(array $data): bool
    {
        return $this->getRequestParameter('name', '') !== '';
    }
}
