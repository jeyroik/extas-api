<?php
namespace extas\interfaces\stages;

use extas\interfaces\IItem;
use extas\interfaces\routes\IRouteDispatcher;

interface IStageApiDeleteData
{
    public const NAME = 'extas.api.delete';

    public function __invoke(IItem &$item, IRouteDispatcher $dispatcher): void;
}
