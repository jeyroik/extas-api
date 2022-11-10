<?php
namespace extas\components\routes;

use extas\components\extensions\TExtendable;
use extas\components\Plugins;
use extas\interfaces\IItem;
use extas\interfaces\stages\IStageApiUpdateData;
use Psr\Http\Message\ResponseInterface;

/**
 * @property string $repoName
 * 
 * @method void setResponseData(array $data, string $errorMessage = '')
 * @method array getWhere()
 */
trait TRouteUpdate
{
    use TExtendable;

    public function execute(): ResponseInterface
    {
        $item = $this->getItem();

        $this->updateData($item, $this->getRequestData());
        $this->setResponseData($item->__toArray());
        
        return $this->response;
    }

    protected function updateData(IItem &$item, array $data): void
    {
        foreach (Plugins::byStage(IStageApiUpdateData::NAME) as $plugin) {
            $plugin($item, $data, $this);
        }

        foreach (Plugins::byStage(IStageApiUpdateData::NAME . '.' . $this->repoName) as $plugin) {
            $plugin($item, $data, $this);
        }
    }

    protected function getItem(): IItem
    {
        $where = $this->getWhere();

        return $this->{$this->repoName}()->one($where);
    }
}
