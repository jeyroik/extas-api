<?php
namespace extas\interfaces\stages;

use extas\interfaces\IItem;

interface IStageApiAfterDelete
{
    public const NAME = 'extas.api.after.delete';

    public function __invoke(IItem &$item): void;
}
