<?php
namespace tests\resources;

use extas\components\plugins\Plugin;
use extas\interfaces\routes\IRouteDispatcher;
use extas\interfaces\stages\IStageApiValidateInputData;

class PluginCreate extends Plugin implements IStageApiValidateInputData
{
    public function __invoke(array $data, IRouteDispatcher $dispatcher): bool
    {
        return isset($data['description']);
    }
}
