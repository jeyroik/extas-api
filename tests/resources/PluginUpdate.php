<?php
namespace tests\resources;

use extas\components\exceptions\MissedOrUnknown;
use extas\components\extensions\TExtendable;
use extas\components\plugins\Plugin;
use extas\interfaces\IItem;
use extas\interfaces\repositories\IRepository;
use extas\interfaces\routes\IRoute;
use extas\interfaces\routes\IRouteDispatcher;
use extas\interfaces\stages\IStageApiUpdateData;

/**
 * @method IRepository routes()
 */
class PluginUpdate extends Plugin implements IStageApiUpdateData
{
    use TExtendable;

    public function __invoke(IItem &$item, array $data, IRouteDispatcher $dispatcher): void
    {
        /**
         * @var IRoute $item
         */
        $route = $this->routes()->one([
            IRoute::FIELD__ID => $item->getId()
        ]);

        if ($route) {
            foreach ($data as $field => $value) {
                $item[$field] = $value;
            }
            $this->routes()->update($item);
        } else {
            throw new MissedOrUnknown('route');
        }
    }

    protected function getSubjectForExtension(): string
    {
        return '';
    }
}
