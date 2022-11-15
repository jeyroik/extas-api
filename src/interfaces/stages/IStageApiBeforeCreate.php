<?php
namespace extas\interfaces\stages;

interface IStageApiBeforeCreate
{
    public const NAME = 'extas.api.before.create';

    public function __invoke(array &$data): void;
}
