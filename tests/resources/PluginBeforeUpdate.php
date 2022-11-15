<?php
namespace tests\resources;

use extas\components\plugins\Plugin;
use extas\interfaces\stages\IStageApiBeforeUpdate;

class PluginBeforeUpdate extends Plugin implements IStageApiBeforeUpdate
{
    public function __invoke(array &$data): void
    {
        $data['enriched'] = true;
    }
}
