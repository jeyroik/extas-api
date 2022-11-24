<?php
namespace tests\routes;

use extas\components\extensions\TExtendable;
use extas\components\http\TSnuffHttp;
use extas\components\plugins\Plugin;
use extas\components\plugins\TSnuffPlugins;
use extas\components\repositories\TSnuffRepository;
use extas\components\routes\Route;
use extas\interfaces\routes\descriptions\IJsonSchemaV1;
use extas\interfaces\stages\IStageApiAfterCreate;
use extas\interfaces\stages\IStageApiAfterDelete;
use extas\interfaces\stages\IStageApiAfterUpdate;
use extas\interfaces\stages\IStageApiAfterView;
use extas\interfaces\stages\IStageApiBeforeCreate;
use extas\interfaces\stages\IStageApiDeleteData;
use extas\interfaces\stages\IStageApiListData;
use extas\interfaces\stages\IStageApiUpdateData;
use PHPUnit\Framework\TestCase;
use tests\resources\PluginAfterCreate;
use tests\resources\PluginAfterDelete;
use tests\resources\PluginAfterUpdate;
use tests\resources\PluginBeforeCreate;
use tests\resources\PluginDelete;
use tests\resources\PluginList;
use tests\resources\PluginUpdate;
use tests\resources\PluginView;
use tests\resources\Simple;
use tests\resources\TestCreateDispatcher;
use tests\resources\TestDeleteDispatcher;
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
    use TExtendable;

    protected function getSubjectForExtension(): string
    {
        return '';
    }

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

        $r->setClass(Simple::class);
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
        $this->createSnuffPlugin(PluginAfterCreate::class, [IStageApiAfterCreate::NAME, IStageApiAfterCreate::NAME . '.routes']);

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
            ['arg' => 'ok'],
            $r
        );

        $response = $create->execute();
        $result = $this->getJsonRpcResponse($response);

        $this->assertArrayHasKey('data', $result, print_r($result, true));
        $this->assertArrayHasKey('name', $result['data'], print_r($result['data'], true));
        $this->assertArrayHasKey('id', $result['data'], print_r($result['data'], true));
        $this->assertArrayHasKey('arg', $result['data'], print_r($result['data'], true));
        $this->assertArrayHasKey('created', $result['data'], print_r($result['data'], true));

        $this->assertEquals(2, $result['data']['created'], print_r($result['data'], true));

        $count = $r->routes()->all([]);
        $this->assertCount(1, $count);
    }

    public function testDispatcherForEnrichBeforeCreate()
    {
        $this->createSnuffPlugin(PluginBeforeCreate::class, [IStageApiBeforeCreate::NAME.'.routes']);

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
            ['arg' => 'ok'],
            $r
        );

        $response = $create->execute();
        $result = $this->getJsonRpcResponse($response);

        $this->assertArrayHasKey('data', $result, print_r($result, true));
        $this->assertArrayHasKey('name', $result['data'], print_r($result['data'], true));
        $this->assertArrayHasKey('id', $result['data'], print_r($result['data'], true));
        $this->assertArrayHasKey('arg', $result['data'], print_r($result['data'], true));
        $this->assertArrayHasKey('enriched', $result['data'], print_r($result['data'], true));

        $count = $r->routes()->all([]);
        $this->assertCount(1, $count);
    }

    public function testDispatcherForView()
    {
        $this->createSnuffPlugin(PluginView::class, [IStageApiAfterView::NAME, IStageApiAfterView::NAME . '.routes']);

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
        $r = $r->routes()->create(new Route([
            Route::FIELD__NAME => '/',
            Route::FIELD__DESCRIPTION => 'test'
        ]));

        $view = new TestViewDispatcher(
            $this->getPsrRequest('.view'),
            $this->getPsrResponse(),
            [],
            $r
        );

        $response = $view->execute();
        $result = $this->getJsonRpcResponse($response);

        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('name', $result['data']);
        $this->assertArrayHasKey('title', $result['data']);
        $this->assertEquals('/', $result['data']['name']);

        $view = new TestViewDispatcher(
            $this->getPsrRequest('.view-fail'),
            $this->getPsrResponse(),
            [],
            $r
        );

        $response = $view->execute();
        $result = $this->getJsonRpcResponse($response);

        $this->assertArrayHasKey(IJsonSchemaV1::FIELD__DATA, $result);
        $this->assertArrayHasKey(IJsonSchemaV1::FIELD__ERROR, $result);
    }

    public function testDispatcherForUpdate()
    {
        $this->createSnuffPlugin(PluginUpdate::class, [IStageApiUpdateData::NAME, IStageApiUpdateData::NAME . '.routes']);
        $this->plugins()->create(new Plugin([
            Plugin::FIELD__CLASS => PluginAfterUpdate::class,
            Plugin::FIELD__STAGE => [IStageApiAfterUpdate::NAME, IStageApiAfterUpdate::NAME . '.routes']
        ]));

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
        $r = $r->routes()->create(new Route([
            Route::FIELD__NAME => '/'
        ]));

        $update = new TestUpdateDispatcher(
            $this->getPsrRequest('.update'),
            $this->getPsrResponse(),
            [],
            $r
        );

        $response = $update->execute();
        $result = $this->getJsonRpcResponse($response);

        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('name', $result['data']);
        $this->assertArrayHasKey('description', $result['data']);
        $this->assertArrayHasKey('updated', $result['data'], print_r($result['data'], true));
        $this->assertEquals(2, $result['data']['updated']);

        $update = new TestUpdateDispatcher(
            $this->getPsrRequest('.update-fail'),
            $this->getPsrResponse(),
            [],
            $r
        );

        $response = $update->execute();
        $result = $this->getJsonRpcResponse($response);

        $this->assertArrayHasKey(IJsonSchemaV1::FIELD__DATA, $result);
        $this->assertArrayHasKey(IJsonSchemaV1::FIELD__ERROR, $result);
    }

    public function testDispatcherForDelete()
    {
        $this->createSnuffPlugin(PluginDelete::class, [IStageApiDeleteData::NAME, IStageApiDeleteData::NAME . '.routes']);
        $this->plugins()->create(new Plugin([
            Plugin::FIELD__CLASS => PluginAfterDelete::class,
            Plugin::FIELD__STAGE => [IStageApiAfterDelete::NAME, IStageApiAfterDelete::NAME . '.routes']
        ]));

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
        $r = $r->routes()->create(new Route([
            Route::FIELD__NAME => '/'
        ]));

        
        $delete = new TestDeleteDispatcher(
            $this->getPsrRequest('.delete'),
            $this->getPsrResponse(),
            [],
            $r
        );

        $response = $delete->execute();
        $result = $this->getJsonRpcResponse($response);

        $this->assertArrayHasKey(IJsonSchemaV1::FIELD__ERROR, $result);
        $this->assertEquals('Missed or unknown route', $result[IJsonSchemaV1::FIELD__ERROR]);

        $r = new Route();
        $count = $r->routes()->all([]);
        $this->assertEmpty($count, print_r($count, true));

        $delete = new TestDeleteDispatcher(
            $this->getPsrRequest('.delete-fail'),
            $this->getPsrResponse(),
            [],
            $r
        );

        $response = $delete->execute();
        $result = $this->getJsonRpcResponse($response);

        $this->assertArrayHasKey(IJsonSchemaV1::FIELD__DATA, $result);
        $this->assertArrayHasKey(IJsonSchemaV1::FIELD__ERROR, $result);
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
        $r = $r->routes()->create(new Route([
            Route::FIELD__NAME => '/'
        ]));

        
        $list = new TestListDispatcher(
            $this->getPsrRequest('.list'),
            $this->getPsrResponse(),
            [],
            $r
        );

        $response = $list->execute();
        $result = $this->getJsonRpcResponse($response);

        $this->assertArrayHasKey('data', $result);
        $this->assertCount(1, $result['data']);
        $this->assertArrayHasKey('title', $result['data'][0]);

        $list = new TestListDispatcher(
            $this->getPsrRequest('.list-fail'),
            $this->getPsrResponse(),
            [],
            $r
        );

        $response = $list->execute();
        $result = $this->getJsonRpcResponse($response);

        $this->assertArrayHasKey(IJsonSchemaV1::FIELD__DATA, $result);
        $this->assertArrayHasKey(IJsonSchemaV1::FIELD__ERROR, $result);

        $this->deleteRepo('routes');
    }
}
