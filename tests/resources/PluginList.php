<?php
namespace tests\resources;

use extas\components\plugins\Plugin;
use extas\interfaces\repositories\IRepository;
use extas\interfaces\routes\IRoute;
use extas\interfaces\routes\IRouteDispatcher;
use extas\interfaces\stages\IStageApiListData;

/**
 * @method IRepository routes()
 */
class PluginList extends Plugin implements IStageApiListData
{
    public function __invoke(array &$items, IRouteDispatcher $dispatcher): void
    {
        foreach ($items as $index => $item) {
            $item[IRoute::FIELD__TITLE] = 'listed';
            $items[$index] = $item;
        }
    }
}
