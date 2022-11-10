<?php
namespace tests\resources;

use extas\components\exceptions\MissedOrUnknown;
use extas\components\extensions\TExtendable;
use extas\components\plugins\Plugin;
use extas\interfaces\IItem;
use extas\interfaces\repositories\IRepository;
use extas\interfaces\routes\IRoute;
use extas\interfaces\routes\IRouteDispatcher;
use extas\interfaces\stages\IStageApiDeleteData;

/**
 * @method IRepository routes()
 */
class PluginDelete extends Plugin implements IStageApiDeleteData
{
    use TExtendable;

    public function __invoke(IItem &$item, IRouteDispatcher $dispatcher): void
    {
        /**
         * @var IRoute $item
         */
        $route = $this->routes()->one([
            IRoute::FIELD__ID => $item->getId()
        ]);

        if ($route) {
            $this->routes()->delete([], $item);
        } else {
            throw new MissedOrUnknown('route');
        }
    }

    protected function getSubjectForExtension(): string
    {
        return '';
    }
}
