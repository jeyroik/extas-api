<?php
namespace extas\interfaces\stages;

use extas\interfaces\routes\IRouteDispatcher;

interface IStageApiBeforeCreate
{
    public const NAME = 'extas.api.before.create';

    public function __invoke(array &$data, IRouteDispatcher $dispatcher): void;
}
