<?php
namespace tests\jsonrpc;

use extas\interfaces\repositories\IRepository;
use extas\interfaces\stages\IStageApiAppInit;
use extas\components\api\App;
use extas\components\plugins\TSnuffPlugins;
use extas\components\repositories\TSnuffRepository;

use PHPUnit\Framework\TestCase;
use tests\api\PluginFakeRoute;

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

    protected IRepository $opRepo;

    protected function setUp(): void
    {
        putenv("EXTAS__CONTAINER_PATH_STORAGE_LOCK=vendor/jeyroik/extas-foundation/resources/container.dist.json");
        is_dir(__DIR__ . '/../tmp') || mkdir(__DIR__ . '/../tmp', 777);
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

        $this->createSnuffPlugin(PluginFakeRoute::class, [IStageApiAppInit::NAME]);
        $app = App::create();
        $this->assertCount(1, $app->getRouteCollector()->getRoutes());
    }
}
