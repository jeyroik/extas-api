<?php
namespace extas\components\routes\dispatchers;

use extas\components\routes\RouteDispatcher;
use extas\interfaces\routes\dispatchers\IJsonDispatcher;

abstract class JsonDispatcher extends RouteDispatcher implements IJsonDispatcher
{
    protected array $requestData = [];

    protected function getRequestParameter(string $paramName, string $default = ''): mixed
    {
        $data = $this->getRequestData();

        return $data[$paramName] ?? $default;
    }

    protected function getRequestData(): array
    {
        if (empty($this->requestData)) {
            $c = $this->request->getBody()->getContents();
            $data = $this->args;

            if ($c) { 
                $data = array_merge(
                    $data,
                    json_decode($c, true)
                );
            }

            $this->requestData = $data;
        }

        return $this->requestData;
    }

    protected function setResponseData(array $data, string $errorMessage = ''): void
    {
        $result = [
            static::FIELD__DATA => $data
        ];

        if ($errorMessage) {
            $result[static::FIELD__ERROR] = $errorMessage;
        }

        $this->response->getBody()->write(json_encode($result));
    }
}
