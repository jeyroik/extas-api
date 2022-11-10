<?php
namespace extas\interfaces\stages;

use extas\interfaces\IItem;
use extas\interfaces\routes\IRouteDispatcher;

interface IStageApiViewData
{
    public const NAME = 'extas.api.view';

    public function __invoke(IItem &$item, IRouteDispatcher $dispatcher): void;
}
