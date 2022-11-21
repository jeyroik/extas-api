<?php
namespace tests\resources;

use extas\components\routes\dispatchers\JsonDispatcher;
use Psr\Http\Message\ResponseInterface;

class TestDispatcher extends JsonDispatcher
{
    protected string $repoName = 'routes';

    public function execute(): ResponseInterface
    {
        return $this->response;
    }

    public function help(): ResponseInterface
    {
        return $this->response;
    }
}
