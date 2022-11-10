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
        $items = $this->getItems();

        $this->enrichData($items);
        $this->setResponseData($items);
        
        return $this->response;
    }

    protected function enrichData(array &$items): void
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
