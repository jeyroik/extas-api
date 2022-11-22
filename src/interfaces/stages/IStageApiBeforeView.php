<?php
namespace extas\interfaces\stages;

use extas\interfaces\routes\IRouteDispatcher;

interface IStageApiBeforeView
{
    public const NAME = 'extas.api.before.view';

    public function __invoke(array &$where, IRouteDispatcher $dispatcher): void;
}
