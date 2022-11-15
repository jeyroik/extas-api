<?php
namespace tests\resources;

use extas\components\plugins\Plugin;
use extas\interfaces\stages\IStageApiBeforeCreate;

class PluginBeforeCreate extends Plugin implements IStageApiBeforeCreate
{
    public function __invoke(array &$data): void
    {
        $data['enriched'] = true;
    }
}
