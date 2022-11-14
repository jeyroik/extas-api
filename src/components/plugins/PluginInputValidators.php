<?php
namespace extas\components\plugins;

use extas\interfaces\routes\descriptions\IHaveApiDescription;
use extas\interfaces\stages\IStageInputDescription;

class PluginInputValidators extends Plugin implements IStageInputDescription
{
    public function __invoke(array &$result, bool $isForHelp): void
    {
        if ($isForHelp) {
            return;
        }

        $params = $this->getResultParameters($result);

        $result[IHaveApiDescription::INPUT_FIELD__PARAMETERS] = $this->injectValidators($params);
    }

    protected function getResultParameters(array $result): array
    {
        return $result[IHaveApiDescription::INPUT_FIELD__PARAMETERS] ?? [];
    }

    protected function injectValidators(array $params): array
    {
        foreach ($params as $name => $options) {
            if ($options[IHaveApiDescription::PROP__VALIDATOR]) {
                $validatorClass = $options[IHaveApiDescription::PROP__VALIDATOR];
                $params[$name][IHaveApiDescription::PROP__VALIDATOR] = new $validatorClass();
            }
        }

        return $params;
    }
}
