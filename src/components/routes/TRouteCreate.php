<?php
namespace extas\components\routes;

use extas\components\exceptions\AlreadyExist;
use extas\components\extensions\TExtendable;
use extas\components\Plugins;
use extas\interfaces\stages\IStageApiBeforeCreate;
use extas\interfaces\stages\IStageApiValidateInputData;
use Psr\Http\Message\ResponseInterface;
use ReflectionClass;

/**
 * @method array getRequestData()
 * @method void setResponseData(array $data, string $errorMessage = '')
 * 
 * @property string $repoName
 * @property array $validators
 */
trait TRouteCreate
{
    use TExtendable;

    public function execute(): ResponseInterface
    {
        $data = $this->getRequestData();

        if (!$this->isValidData($data)) {
            $this->setResponseData([], 'Invalid data');
            return $this->response;
        }

        try {
            $this->enrichData($data);

            $class = $this->{$this->repoName}()->getItemClass();
            $item = new $class($data);
        
            $item = $this->{$this->repoName}()->create($item);
        } catch (\Exception $e) {
            $this->setResponseData($data, $e->getMessage());
            return $this->response;
        }

        $this->setResponseData($item->__toArray());

        return $this->response;
    }

    protected function enrichData(array &$data): void
    {
        foreach(Plugins::byStage(IStageApiBeforeCreate::NAME) as $plugin) {
            $plugin($data);
        }

        foreach(Plugins::byStage(IStageApiBeforeCreate::NAME . '.' . $this->repoName) as $plugin) {
            $plugin($data);
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
