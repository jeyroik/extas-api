<?php
namespace extas\interfaces\stages;

interface IStageApiBeforeUpdate
{
    public const NAME = 'extas.api.before.update';

    public function __invoke(array &$data): void;
}
