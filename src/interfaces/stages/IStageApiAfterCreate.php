<?php
namespace extas\interfaces\stages;

use extas\interfaces\IItem;

interface IStageApiAfterCreate
{
    public const NAME = 'extas.api.after.create';

    public function __invoke(IItem &$item): void;
}
