<?php
namespace extas\interfaces\stages;

use extas\interfaces\IItem;
use extas\interfaces\routes\IRouteDispatcher;

interface IStageApiAfterView
{
    public const NAME = 'extas.api.after.view';

    public function __invoke(IItem &$item, IRouteDispatcher $dispatcher): void;
}
