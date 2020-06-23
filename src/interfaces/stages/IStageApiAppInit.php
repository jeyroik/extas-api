<?php
namespace extas\interfaces\stages;

use Slim\App;

/**
 * Interface IStageApiAppInit
 *
 * @package extas\interfaces\stages
 * @author jeyroik@gmail.com
 */
interface IStageApiAppInit
{
    public const NAME = 'extas.api.app.init';

    /**
     * @param App $app
     */
    public function __invoke(App &$app): void;
}
