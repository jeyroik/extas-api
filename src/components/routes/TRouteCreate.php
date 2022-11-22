<?php
namespace extas\components\routes;

use extas\components\extensions\TExtendable;
use extas\components\Plugins;
use extas\interfaces\IItem;
use extas\interfaces\stages\IStageApiAfterCreate;
use extas\interfaces\stages\IStageApiBeforeCreate;
use extas\interfaces\stages\IStageApiValidateInputData;
use Psr\Http\Message\ResponseInterface;

/**
 * @method array getRequestData()
 * @method void setResponseData(array $data, string $errorMessage = '')
 * 
 * @property string $repoName
 * @property array $validators
 * @property bool $isDebug
 */
trait TRouteCreate
{
    use TExtendable;

    public function execute(): ResponseInterface
    {
        $data = $this->getRequestData();

        try {
            $this->before($data);

            $class = $this->{$this->repoName}()->getItemClass();
            $item  = new $class($data);
            $item  = $this->{$this->repoName}()->create($item);
            
            $this->after($item);
            $this->setResponseData($item->__toArray());
        } catch (\Exception $e) {
            $this->setResponseData($data, $e->getMessage());
        }

        return $this->response;
    }

    protected function after(IItem &$item): void
    {
        foreach(Plugins::byStage(IStageApiAfterCreate::NAME) as $plugin) {
            $plugin($item, $this);
        }

        foreach(Plugins::byStage(IStageApiAfterCreate::NAME . '.' . $this->repoName) as $plugin) {
            $plugin($item, $this);
        }
    }

    protected function before(array &$data): void
    {
        foreach(Plugins::byStage(IStageApiBeforeCreate::NAME) as $plugin) {
            $plugin($data, $this);
        }

        foreach(Plugins::byStage(IStageApiBeforeCreate::NAME . '.' . $this->repoName) as $plugin) {
            $plugin($data, $this);
        }
    }

    protected function isValidData(array $data): bool
    {
        foreach ($this->validators as $validator) {
            $valid = $this->$validator($data);
            if (!$valid) {
                return false;
            }
        }

        foreach (Plugins::byStage(IStageApiValidateInputData::NAME . '.create.' . $this->repoName) as $plugin) {
            $valid = $plugin($data, $this);
            if (!$valid) {
                return false;
            }
        }

        return true;
    }
}
