<?php
namespace extas\interfaces\stages;

use extas\interfaces\IItem;

interface IStageApiAfterUpdate
{
    public const NAME = 'extas.api.after.update';

    public function __invoke(IItem &$item): void;
}
