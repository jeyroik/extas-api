<?php
namespace extas\interfaces\stages;

use extas\interfaces\IItem;
use extas\interfaces\routes\IRouteDispatcher;

interface IStageApiUpdateData
{
    public const NAME = 'extas.api.update';

    /**
     * @param IItem $item source item
     * @param array $data data for update
     * @param IRouteDispatcher $dispatcher
     * @return void
     */
    public function __invoke(IItem &$item, array $data, IRouteDispatcher $dispatcher): void;
}
