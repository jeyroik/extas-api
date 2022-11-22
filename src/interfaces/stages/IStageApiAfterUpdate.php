<?php
namespace extas\interfaces\stages;

use extas\interfaces\IItem;
use extas\interfaces\routes\IRouteDispatcher;

interface IStageApiAfterUpdate
{
    public const NAME = 'extas.api.after.update';

    public function __invoke(IItem &$item, IRouteDispatcher $dispatcher): void;
}
