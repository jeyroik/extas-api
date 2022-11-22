<?php
namespace tests\resources;

use extas\components\plugins\Plugin;
use extas\interfaces\IItem;
use extas\interfaces\routes\IRouteDispatcher;
use extas\interfaces\stages\IStageApiAfterUpdate;

class PluginAfterUpdate extends Plugin implements IStageApiAfterUpdate
{
    public function __invoke(IItem &$item, IRouteDispatcher $dispatcher): void
    {
        $item['updated'] = isset($item['updated']) ? 2 : 1;
    }
}
