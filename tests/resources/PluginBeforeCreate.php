<?php
namespace tests\resources;

use extas\components\plugins\Plugin;
use extas\interfaces\routes\IRouteDispatcher;
use extas\interfaces\stages\IStageApiBeforeCreate;

class PluginBeforeCreate extends Plugin implements IStageApiBeforeCreate
{
    public function __invoke(array &$data, IRouteDispatcher $dispatcher): void
    {
        $data['enriched'] = true;
    }
}
