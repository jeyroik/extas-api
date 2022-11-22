<?php
namespace extas\components\routes;

use extas\components\extensions\TExtendable;
use extas\components\Plugins;
use extas\interfaces\IItem;
use extas\interfaces\stages\IStageApiAfterView;
use extas\interfaces\stages\IStageApiBeforeView;
use extas\interfaces\stages\IStageApiViewData;
use Psr\Http\Message\ResponseInterface;

/**
 * @property string $repoName
 * 
 * @method void setResponseData(array $data, string $errorMessage = '')
 * @method array getWhere()
 */
trait TRouteView
{
    use TExtendable;

    public function execute(): ResponseInterface
    {
        try {
            $where = $this->getWhere();
            $this->before($where);
            $item = $this->getItem($where);
            $this->after($item);
            $this->setResponseData($item->__toArray());
        } catch (\Exception $e) {
            $this->setResponseData([], $e->getMessage());    
        }
        
        return $this->response;
    }

    protected function before(array &$where): void
    {
        foreach (Plugins::byStage(IStageApiBeforeView::NAME) as $plugin) {
            $plugin($where, $this);
        }

        foreach (Plugins::byStage(IStageApiBeforeView::NAME . '.' . $this->repoName) as $plugin) {
            $plugin($where, $this);
        }
    }

    protected function after(IItem &$item): void
    {
        foreach (Plugins::byStage(IStageApiAfterView::NAME) as $plugin) {
            $plugin($item, $this);
        }

        foreach (Plugins::byStage(IStageApiAfterView::NAME . '.' . $this->repoName) as $plugin) {
            $plugin($item, $this);
        }
    }

    protected function getItem(array $where): IItem
    {
        return $this->{$this->repoName}()->one($where);
    }
}
