<?php
namespace extas\interfaces\stages;

use extas\interfaces\IItem;
use extas\interfaces\routes\IRouteDispatcher;

interface IStageApiBeforeDelete
{
    public const NAME = 'extas.api.before.delete';

    public function __invoke(IItem &$item, IRouteDispatcher $dispatcher): void;
}
