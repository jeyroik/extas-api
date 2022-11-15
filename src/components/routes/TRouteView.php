<?php
namespace extas\components\routes;

use extas\components\extensions\TExtendable;
use extas\components\Plugins;
use extas\interfaces\IItem;
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
            $item = $this->getItem();
            $this->enrichData($item);
            $this->setResponseData($item->__toArray());
        } catch (\Exception $e) {
            $this->setResponseData([], $e->getMessage());    
        }
        
        return $this->response;
    }

    protected function enrichData(IItem &$item): void
    {
        foreach (Plugins::byStage(IStageApiViewData::NAME) as $plugin) {
            $plugin($item, $this);
        }

        foreach (Plugins::byStage(IStageApiViewData::NAME . '.' . $this->repoName) as $plugin) {
            $plugin($item, $this);
        }
    }

    protected function getItem(): IItem
    {
        $where = $this->getWhere();

        return $this->{$this->repoName}()->one($where);
    }
}
