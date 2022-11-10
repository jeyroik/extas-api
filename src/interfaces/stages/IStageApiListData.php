<?php
namespace extas\interfaces\stages;

use extas\interfaces\routes\IRouteDispatcher;

interface IStageApiListData
{
    public const NAME = 'extas.api.list';

    public function __invoke(array &$items, IRouteDispatcher $dispatcher): void;
}
