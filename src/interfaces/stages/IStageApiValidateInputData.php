<?php
namespace extas\interfaces\stages;

use extas\interfaces\routes\IRouteDispatcher;

interface IStageApiValidateInputData
{
    public const NAME = 'extas.api.validate.input.data';

    /**
     * @param array $data
     * @param IRouteDispatcher $dispatcher
     * @return boolean is data valid
     */
    public function __invoke(array $data, IRouteDispatcher $dispatcher): bool;
}
