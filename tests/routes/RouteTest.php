<?php
namespace tests\jsonrpc;

use extas\interfaces\repositories\IRepository;
use extas\interfaces\stages\IStageApiAppInit;
use extas\components\api\App;
use extas\components\plugins\TSnuffPlugins;
use extas\components\repositories\TSnuffRepository;
use extas\components\routes\Route;
use PHPUnit\Framework\TestCase;
use tests\api\PluginFakeRoute;
use tests\resources\TestDispatcher;

/**
 * Class RouteTest
 *
 * @package tests
 * @author jeyroik@gmail.com
 */
class RouteTest extends TestCase
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

    public function testRoute()
    {
        $r = new Route();
        $r->setMethod(Route::METHOD__CREATE);
        $this->assertEquals(Route::METHOD__CREATE, $r->getMethod());

        $r->setClass(TestDispatcher::class);
        $d = $r->buildDispatcher(1,2);
        $p = $d->getParams();

        $this->assertCount(2, $p);
        $this->assertEquals(1, $p[0]);
        $this->assertEquals(2, $p[1]);

        $r->setClass('unknown');
        $this->expectExceptionMessage('Missed or unknown class "unknown"');
        $r->buildDispatcher();
    }
}
