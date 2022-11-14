<?php
namespace extas\interfaces\stages;

interface IStageInputDescription
{
    public const NAME = 'extas.api.input.description';

    public function __invoke(array &$result, bool $isForHelp): void;
}
