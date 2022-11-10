<?php
namespace extas\components\routes;

use extas\components\extensions\TExtendable;
use extas\components\Plugins;
use extas\interfaces\IItem;
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
        $item = $this->getItem();

        $this->deleteData($item);
        $this->setResponseData($item->__toArray());
        
        return $this->response;
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
