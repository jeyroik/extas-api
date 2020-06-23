<?php
namespace tests\api;

use extas\components\plugins\Plugin;
use extas\interfaces\stages\IStageApiAppInit;
use Slim\App;

/**
 * Class PluginFakeRoute
 *
 * @package tests\api
 * @author jeyroik <jeyroik@gmail.com>
 */
class PluginFakeRoute extends Plugin implements IStageApiAppInit
{
    /**
     * @param App $app
     */
    public function __invoke(App &$app): void
    {
        $app->get('/', function(){});
    }
}
