<?php
namespace extas\components\plugins;

use extas\interfaces\routes\descriptions\IHaveApiDescription;

class PluginOutputValidators extends PluginInputValidators
{
    protected function getResultParameters(array $result): array
    {
        return $result[IHaveApiDescription::OUTPUT_FIELD__PARAMETERS] ?? [];
    }
}
