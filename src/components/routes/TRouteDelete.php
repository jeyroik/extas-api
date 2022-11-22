<?php
namespace extas\components\routes;

use extas\components\extensions\TExtendable;
use extas\components\Plugins;
use extas\interfaces\IItem;
use extas\interfaces\stages\IStageApiAfterDelete;
use extas\interfaces\stages\IStageApiBeforeDelete;
use extas\interfaces\stages\IStageApiDeleteData;
use Psr\Http\Message\ResponseInterface;

/**
 * @property string $repoName
 * 
 * @method void setResponseData(array $data, string $errorMessage = '')
 * @method array getWhere()
 */
trait TRouteDelete
{
    use TExtendable;

    public function execute(): ResponseInterface
    {
        try {
            $item = $this->getItem();
            $this->before($item);
            $this->deleteData($item);
            $this->after($item);
            $this->setResponseData($item->__toArray());
        } catch (\Exception $e) {
            $this->setResponseData([], $e->getMessage());
        }
        
        return $this->response;
    }

    protected function before(IItem &$item): void
    {
        foreach (Plugins::byStage(IStageApiBeforeDelete::NAME) as $plugin) {
            $plugin($item, $this);
        }

        foreach (Plugins::byStage(IStageApiBeforeDelete::NAME . '.' . $this->repoName) as $plugin) {
            $plugin($item, $this);
        }
    }

    protected function after(IItem &$item): void
    {
        foreach (Plugins::byStage(IStageApiAfterDelete::NAME) as $plugin) {
            $plugin($item, $this);
        }

        foreach (Plugins::byStage(IStageApiAfterDelete::NAME . '.' . $this->repoName) as $plugin) {
            $plugin($item, $this);
        }
    }

    protected function deleteData(IItem &$item): void
    {
        foreach (Plugins::byStage(IStageApiDeleteData::NAME) as $plugin) {
            $plugin($item, $this);
        }

        foreach (Plugins::byStage(IStageApiDeleteData::NAME . '.' . $this->repoName) as $plugin) {
            $plugin($item, $this);
        }
    }

    protected function getItem(): IItem
    {
        $where = $this->getWhere();

        return $this->{$this->repoName}()->one($where);
    }
}
