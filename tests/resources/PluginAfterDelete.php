<?php
namespace tests\resources;

use extas\components\plugins\Plugin;
use extas\interfaces\IItem;
use extas\interfaces\stages\IStageApiAfterDelete;

class PluginAfterDelete extends Plugin implements IStageApiAfterDelete
{
    public function __invoke(IItem &$item): void
    {
        $item['deleted'] = isset($item['deleted']) ? 2 : 1;
    }
}
