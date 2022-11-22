<?php
namespace extas\interfaces\stages;

use extas\interfaces\IItem;
use extas\interfaces\routes\IRouteDispatcher;

interface IStageApiAfterCreate
{
    public const NAME = 'extas.api.after.create';

    public function __invoke(IItem &$item, IRouteDispatcher $dispatcher): void;
}
