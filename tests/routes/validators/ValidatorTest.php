<?php
namespace tests\routes\validators;

use extas\components\http\TSnuffHttp;
use extas\components\plugins\PluginInputValidators;
use extas\components\plugins\PluginOutputValidators;
use extas\components\plugins\TSnuffPlugins;
use extas\components\repositories\TSnuffRepository;
use extas\components\routes\Route;
use extas\components\routes\validators\VUUID;
use extas\interfaces\routes\descriptions\IHaveApiDescription as i;
use extas\interfaces\stages\IStageInputDescription;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use tests\resources\TestCreateDispatcher;

/**
 * Class RouteTest
 *
 * @package tests
 * @author jeyroik@gmail.com
 */
class ValidatorTest extends TestCase
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
        $this->dropDatabase(__DIR__ . '/..');
        $this->deleteRepo('plugins');
        $this->deleteRepo('extensions');
    }

    public function testVuuid()
    {
        $this->createSnuffPlugin(PluginInputValidators::class, [IStageInputDescription::NAME]);
        $this->createSnuffPlugin(PluginOutputValidators::class, [IStageInputDescription::NAME]);

        $this->buildRepo(__DIR__ . '/../../../vendor/jeyroik/extas-foundation/resources/', [
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

        $result = $create->getParamsDesc();

        $this->assertArrayHasKey('input', $result);
        $this->assertArrayHasKey(i::INPUT_FIELD__PARAMETERS, $result['input']);
        $iParams = $result['input'][i::INPUT_FIELD__PARAMETERS];

        $this->assertArrayHasKey('id', $iParams);
        $this->assertArrayHasKey('name', $iParams);

        $this->assertArrayHasKey(i::PROP__VALIDATOR, $iParams['id'], print_r($iParams['id'],true));
        $this->assertInstanceOf(VUUID::class, $iParams['id'][i::PROP__VALIDATOR], print_r($iParams['id'],true));

        /**
         * @var VUUID $v
         */
        $v = $iParams['id'][i::PROP__VALIDATOR];
        
        $this->assertFalse($v->isValid(true));
        $this->assertFalse($v->isValid(''));
        $this->assertFalse($v->isValid('test'));
        $this->assertTrue($v->isValid(Uuid::uuid4()->toString()));

        $this->assertArrayHasKey(i::PROP__VALIDATOR, $iParams['name'], print_r($iParams['name'],true));
        $this->assertEquals(0, $iParams['name'][i::PROP__VALIDATOR], print_r($iParams['name'],true));


        $this->assertArrayHasKey('output', $result);
        $this->assertArrayHasKey(i::OUTPUT_FIELD__PARAMETERS, $result['output']);
    }
}
