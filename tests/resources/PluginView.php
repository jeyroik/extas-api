<?php
namespace tests\resources;

use extas\components\extensions\TExtendable;
use extas\components\plugins\Plugin;
use extas\interfaces\IItem;
use extas\interfaces\repositories\IRepository;
use extas\interfaces\routes\IRoute;
use extas\interfaces\routes\IRouteDispatcher;
use extas\interfaces\stages\IStageApiAfterView;

/**
 * @method IRepository routes()
 */
class PluginView extends Plugin implements IStageApiAfterView
{
    use TExtendable;

    public function __invoke(IItem &$item, IRouteDispatcher $dispatcher): void
    {
        $item[IRoute::FIELD__TITLE] = 'viewed';
    }

    protected function getSubjectForExtension(): string
    {
        return '';
    }
}
