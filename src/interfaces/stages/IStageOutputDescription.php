<?php
namespace extas\interfaces\stages;

interface IStageOutputDescription
{
    public const NAME = 'extas.api.output.description';

    public function __invoke(array &$result, bool $isForHelp): void;
}
