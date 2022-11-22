<?php
namespace extas\interfaces\stages;

use extas\interfaces\routes\IRouteDispatcher;

interface IStageApiBeforeUpdate
{
    public const NAME = 'extas.api.before.update';

    public function __invoke(array &$data, IRouteDispatcher $dispatcher): void;
}
