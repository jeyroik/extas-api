<?php
namespace tests\jsonrpc;

use extas\interfaces\repositories\IRepository;
use extas\interfaces\stages\IStageApiAppInit;
use extas\components\api\App;
use extas\components\plugins\PluginRoutes;
use extas\components\plugins\TSnuffPlugins;
use extas\components\repositories\TSnuffRepository;
use extas\components\routes\Route;
use PHPUnit\Framework\TestCase;
use tests\api\PluginFakeRoute;
use tests\resources\TestDispatcher;

/**
 * Class AppTest
 *
 * @package tests
 * @author jeyroik@gmail.com
 */
class AppTest extends TestCase
{
    use TSnuffRepository;
    use TSnuffPlugins;

    protected function setUp(): void
    {
        putenv("EXTAS__CONTAINER_PATH_STORAGE_LOCK=vendor/jeyroik/extas-foundation/resources/container.dist.json");
        $this->buildBasicRepos();
    }

    protected function tearDown(): void
    {
        $this->dropDatabase(__DIR__);
        $this->deleteRepo('plugins');
        $this->deleteRepo('extensions');
    }

    public function testConstructing()
    {
        $app = App::create();
        $this->assertCount(0, $app->getRouteCollector()->getRoutes(), print_r($app->getRouteCollector()->getRoutes(), true));

        $this->buildRepo(__DIR__ . '/../../vendor/jeyroik/extas-foundation/resources/', [
            'routes' => [
                'namespace' => 'tests\\tmp',
                'item_class' => 'extas\\components\\routes\\Route',
                'pk' => 'id',
                'code' => [
                    'create-before' => '\\extas\\components\\UUID::setId($item);'
                ]
            ]
        ]);

        $r = new Route();
        $r->routes()->create(new Route([
            Route::FIELD__CLASS => TestDispatcher::class,
            Route::FIELD__NAME => 'test',
            Route::FIELD__METHOD => Route::METHOD__CREATE
        ]));
        $this->createSnuffPlugin(PluginRoutes::class, [IStageApiAppInit::NAME]);
        $app = App::create();
        // 1 - the route
        // 2 - the help route
        $this->assertCount(2, $app->getRouteCollector()->getRoutes());
    }
}
