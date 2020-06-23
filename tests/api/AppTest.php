<?php
namespace tests\jsonrpc;

use extas\interfaces\repositories\IRepository;
use extas\interfaces\stages\IStageApiAppInit;

use extas\components\extensions\ExtensionRepository;
use extas\components\api\App;
use extas\components\plugins\PluginRepository;
use extas\components\plugins\TSnuffPlugins;
use extas\components\repositories\TSnuffRepository;

use PHPUnit\Framework\TestCase;
use Dotenv\Dotenv;
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
        parent::setUp();
        $env = Dotenv::create(getcwd() . '/tests/');
        $env->load();
        $this->registerSnuffRepos([
            'extensionRepository' => ExtensionRepository::class,
            'pluginRepository' => PluginRepository::class
        ]);
    }

    protected function tearDown(): void
    {
        $this->unregisterSnuffRepos();
    }

    public function testConstructing()
    {
        $app = App::create();
        $this->assertCount(0, $app->getRouteCollector()->getRoutes());

        $this->createSnuffPlugin(PluginFakeRoute::class, [IStageApiAppInit::NAME]);
        $app = App::create();
        $this->assertCount(1, $app->getRouteCollector()->getRoutes());
    }
}
