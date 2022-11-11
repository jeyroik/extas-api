<?php
namespace tests\jsonrpc;


use extas\components\http\TSnuffHttp;
use extas\components\plugins\TSnuffPlugins;
use extas\components\repositories\TSnuffRepository;
use extas\components\routes\Route;
use extas\components\SystemContainer;
use extas\interfaces\routes\descriptions\IJsonSchemaV1;
use extas\interfaces\stages\IStageApiDeleteData;
use extas\interfaces\stages\IStageApiListData;
use extas\interfaces\stages\IStageApiUpdateData;
use extas\interfaces\stages\IStageApiValidateInputData;
use extas\interfaces\stages\IStageApiViewData;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use tests\resources\PluginCreate;
use tests\resources\PluginDelete;
use tests\resources\PluginList;
use tests\resources\PluginUpdate;
use tests\resources\PluginView;
use tests\resources\TestCreateDispatcher;
use tests\resources\TestDeleteDispatcher;
use tests\resources\TestDispatcher;
use tests\resources\TestExceptionDispatcher;
use tests\resources\TestListDispatcher;
use tests\resources\TestUpdateDispatcher;
use tests\resources\TestViewDispatcher;

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
    use TSnuffHttp;

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

    public function testDispatcherForCreate()
    {
        $this->createSnuffPlugin(PluginCreate::class, [IStageApiValidateInputData::NAME.'.create.routes']);

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
        $count = $r->routes()->all([]);
        $this->assertEmpty($count);

        $create = new TestCreateDispatcher(
            $this->getPsrRequest('.create-true'),
            $this->getPsrResponse(),
            []
        );

        $response = $create->execute();
        $result = $this->getJsonRpcResponse($response);

        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('name', $result['data']);
        $this->assertArrayHasKey('id', $result['data']);

        $count = $r->routes()->all([]);
        $this->assertCount(1, $count);

        $createFalse = new TestCreateDispatcher(
            $this->getPsrRequest('.create-false-local'),
            $this->getPsrResponse(),
            []
        );

        $response = $createFalse->execute();
        $this->assertEmpty($response->getBody().'', $response->getBody().'');

        $createFalse = new TestCreateDispatcher(
            $this->getPsrRequest('.create-false-plugin'),
            $this->getPsrResponse(),
            []
        );

        $response = $createFalse->execute();
        $this->assertEmpty(
            $response->getBody().'',
            'Plugin for stage "' . IStageApiValidateInputData::NAME.'.create.routes' . '" is not working'
        );
    }

    public function testDispatcherForView()
    {
        $this->createSnuffPlugin(PluginView::class, [IStageApiViewData::NAME, IStageApiViewData::NAME . '.routes']);

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
            Route::FIELD__NAME => '/',
            Route::FIELD__DESCRIPTION => 'test'
        ]));

        $view = new TestViewDispatcher(
            $this->getPsrRequest('.view'),
            $this->getPsrResponse(),
            []
        );

        $response = $view->execute();
        $result = $this->getJsonRpcResponse($response);

        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('name', $result['data']);
        $this->assertArrayHasKey('title', $result['data']);
        $this->assertEquals('/', $result['data']['name']);
    }

    public function testDispatcherForUpdate()
    {
        $this->createSnuffPlugin(PluginUpdate::class, [IStageApiUpdateData::NAME, IStageApiUpdateData::NAME . '.routes']);

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
            Route::FIELD__NAME => '/'
        ]));

        $update = new TestUpdateDispatcher(
            $this->getPsrRequest('.update'),
            $this->getPsrResponse(),
            []
        );

        $response = $update->execute();
        $result = $this->getJsonRpcResponse($response);

        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('name', $result['data']);
        $this->assertArrayHasKey('description', $result['data']);
    }

    public function testDispatcherForDelete()
    {
        $this->createSnuffPlugin(PluginDelete::class, [IStageApiDeleteData::NAME, IStageApiDeleteData::NAME . '.routes']);

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
            Route::FIELD__NAME => '/'
        ]));

        
        $delete = new TestDeleteDispatcher(
            $this->getPsrRequest('.delete'),
            $this->getPsrResponse(),
            []
        );

        // Plugin is running twice, so on the second try should be exception, cause route is already deleted.
        $this->expectExceptionMessage('Missed or unknown route');
        $delete->execute();

        $r = new Route();
        $count = $r->routes()->all([]);
        $this->assertEmpty($count, print_r($count, true));
    }

    public function testDispatcherForList()
    {
        $this->createSnuffPlugin(PluginList::class, [IStageApiListData::NAME, IStageApiListData::NAME . '.routes']);

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
            Route::FIELD__NAME => '/'
        ]));

        
        $list = new TestListDispatcher(
            $this->getPsrRequest('.delete'),
            $this->getPsrResponse(),
            []
        );

        $response = $list->execute();
        $result = $this->getJsonRpcResponse($response);

        $this->assertArrayHasKey('data', $result);
        $this->assertCount(1, $result['data']);
        $this->assertArrayHasKey('title', $result['data'][0]);
        $this->deleteRepo('routes');
    }

    public function testDispatcherForException()
    { 
        $this->buildRepo(__DIR__ . '/../../vendor/jeyroik/extas-foundation/resources/', [
            'routes2' => [
                'namespace' => 'tests\\tmp',
                'item_class' => 'extas\\components\\routes\\Route',
                'pk' => 'id',
                'code' => [
                    'create-before' => 'throw new \\extas\\components\\exceptions\\AlreadyExist("route");'
                ]
            ]
        ]);

        $except = new TestExceptionDispatcher(
            $this->getPsrRequest('.create-true'),
            $this->getPsrResponse(),
            []
        );

        $response = $except->execute();
        $result = $this->getJsonRpcResponse($response);

        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey(IJsonSchemaV1::FIELD__ERROR, $result, print_r($result, true));

        $this->deleteRepo('routes2');
    }

    public function testHelp()
    { 
        $this->buildRepo(__DIR__ . '/../../vendor/jeyroik/extas-foundation/resources/', [
            'routes2' => [
                'namespace' => 'tests\\tmp',
                'item_class' => 'extas\\components\\routes\\Route',
                'pk' => 'id',
                'code' => [
                    'create-before' => 'throw new \\extas\\components\\exceptions\\AlreadyExist("route");'
                ]
            ]
        ]);

        $except = new TestExceptionDispatcher(
            $this->getPsrRequest('.create-true'),
            $this->getPsrResponse(),
            []
        );

        $response = $except->help();
        $result = $this->getJsonRpcResponse($response);

        $this->assertArrayHasKey('data', $result, print_r($result,true));
        $this->assertArrayHasKey('request', $result['data'], print_r($result,true));
        $this->assertArrayHasKey('method', $result['data']['request'], print_r($result,true));
        $this->assertArrayHasKey('parameters', $result['data']['request'], print_r($result,true));
        $this->assertArrayHasKey('id', $result['data']['request']['parameters'], print_r($result,true));
        $this->assertArrayHasKey('name', $result['data']['request']['parameters'], print_r($result,true));

        $this->assertArrayHasKey('response', $result['data'], print_r($result,true));
        $this->assertArrayHasKey('name', $result['data']['response'], print_r($result,true));
        $this->assertArrayHasKey('title', $result['data']['response'], print_r($result,true));
        $this->assertArrayHasKey('description', $result['data']['response'], print_r($result,true));
        $this->assertArrayHasKey('method', $result['data']['response'], print_r($result,true));
        $this->assertArrayHasKey('class', $result['data']['response'], print_r($result,true));

        $this->deleteRepo('routes2');
    }
}
