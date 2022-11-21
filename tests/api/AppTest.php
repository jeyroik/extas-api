<?php
namespace tests\api;

use extas\interfaces\stages\IStageApiAppInit;
use extas\components\api\App;
use extas\components\extensions\TExtendable;
use extas\components\http\TSnuffHttp;
use extas\components\plugins\Plugin;
use extas\components\plugins\PluginRoutes;
use extas\components\plugins\TSnuffPlugins;
use extas\components\repositories\TSnuffRepository;
use extas\components\routes\Route;
use extas\interfaces\stages\IStageApiAfterRouteExecute;
use extas\interfaces\stages\IStageApiBeforeRouteExecute;
use PHPUnit\Framework\TestCase;
use tests\resources\PluginAfterRoute;
use tests\resources\PluginBeforeRouteFailed;
use tests\resources\PluginBeforeRouteOk;
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
    use TSnuffHttp;
    use TExtendable;

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

    protected function getSubjectForExtension(): string
    {
        return time();
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
            Route::FIELD__METHOD => Route::METHOD__CREATE,
            Route::FIELD__TITLE => 'Test method',
            Route::FIELD__DESCRIPTION => 'Test method for smoke tests'
        ]));
        $this->createSnuffPlugin(PluginRoutes::class, [IStageApiAppInit::NAME]);
        $this->plugins()->create(new Plugin([
            Plugin::FIELD__CLASS => PluginBeforeRouteOk::class,
            Plugin::FIELD__STAGE => [IStageApiBeforeRouteExecute::NAME, IStageApiBeforeRouteExecute::NAME . '.routes']
        ]));
        $this->plugins()->create(new Plugin([
            Plugin::FIELD__CLASS => PluginAfterRoute::class,
            Plugin::FIELD__STAGE => [IStageApiAfterRouteExecute::NAME, IStageApiAfterRouteExecute::NAME . '.routes']
        ]));

        $app = App::create();
        // 1 - the route
        // 2 - the help route
        // 3 - the routes list
        $this->assertCount(3, $app->getRouteCollector()->getRoutes());

        $routes = $app->getRouteCollector()->getRoutes();
        $request = $this->getPsrRequest();

        foreach ($routes as $route) {
            if ($route->getPattern() == 'test') {
                $response = $route->run($request);
                $this->assertEquals(405, $response->getStatusCode());

                $this->createSnuffPlugin(PluginBeforeRouteFailed::class, [IStageApiBeforeRouteExecute::NAME]);

                $response = $route->run($request);
                $this->assertEquals(400, $response->getStatusCode());
                $this->assertEquals('Error', $response->getBody() . '');
            }
            if ($route->getPattern() == '/help') {
                $response = $route->run($request);
                $result = $this->getJsonRpcResponse($response);
                $this->assertEquals(
                    [
                        'data' => [
                            [
                                "route" => "/help",
                                "title" => "Routes list",
                                "description" => "The current route, shows all available routes",
                                "method" => "get"
                            ],
                            [
                                "route" => "test",
                                "title" => "Test method",
                                "description" => "Test method for smoke tests",
                                "method" => "post"
                            ],
                            [
                                "route" => "test/help",
                                "title" => "Docs for Test method",
                                "description" => "Help for route Test method",
                                "method" => "get"
                            ]
                        ]
                    ],
                    $result
                );
            }
        }
    }
}
