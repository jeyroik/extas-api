<?php
namespace extas\interfaces\stages;

use extas\interfaces\IItem;
use extas\interfaces\routes\IRouteDispatcher;

interface IStageApiBeforeList
{
    public const NAME = 'extas.api.before.list';

    /**
     * @param IItem[] $items
     * @return void
     */
    public function __invoke(array &$items, IRouteDispatcher $dispatcher): void;
}
