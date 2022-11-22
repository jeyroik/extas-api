<?php
namespace extas\components\routes;

use extas\components\extensions\TExtendable;
use extas\components\Plugins;
use extas\interfaces\stages\IStageApiListData;
use Psr\Http\Message\ResponseInterface;

/**
 * @property string $repoName 
 * 
 * @method void setResponseData(array $data, string $errorMessage = '')
 * @method array getWhere()
 */
trait TRouteList
{
    use TExtendable;

    public function execute(): ResponseInterface
    {
        try {
            $items = $this->getItems();

            $this->before($items);
            $this->listData($items);
            $this->after($items);
            $this->setResponseData($items);
        } catch (\Exception $e) {
            $this->setResponseData([], $e->getMessage());
        }
        
        return $this->response;
    }

    protected function before(array &$items): void
    {
        foreach (Plugins::byStage(IStageApiListData::NAME) as $plugin) {
            $plugin($items, $this);
        }

        foreach (Plugins::byStage(IStageApiListData::NAME . '.' . $this->repoName) as $plugin) {
            $plugin($items, $this);
        }
    }

    protected function after(array &$items): void
    {
        foreach (Plugins::byStage(IStageApiListData::NAME) as $plugin) {
            $plugin($items, $this);
        }

        foreach (Plugins::byStage(IStageApiListData::NAME . '.' . $this->repoName) as $plugin) {
            $plugin($items, $this);
        }
    }

    protected function listData(array &$items): void
    {
        foreach (Plugins::byStage(IStageApiListData::NAME) as $plugin) {
            $plugin($items, $this);
        }

        foreach (Plugins::byStage(IStageApiListData::NAME . '.' . $this->repoName) as $plugin) {
            $plugin($items, $this);
        }
    }

    protected function getItems(): array
    {
        $table = $this->repoName;
        $where = $this->getWhere();

        return $this->$table()->allAsArray($where);
    }
}
