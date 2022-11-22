<?php
namespace tests\resources;

use extas\components\plugins\Plugin;
use extas\interfaces\routes\IRouteDispatcher;
use extas\interfaces\stages\IStageApiBeforeUpdate;

class PluginBeforeUpdate extends Plugin implements IStageApiBeforeUpdate
{
    public function __invoke(array &$data, IRouteDispatcher $dispatcher): void
    {
        $data['enriched'] = true;
    }
}
