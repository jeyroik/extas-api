<?php
namespace extas\components\routes\dispatchers;

use extas\components\routes\RouteDispatcher;
use extas\interfaces\routes\dispatchers\IJsonDispatcher;
use extas\interfaces\routes\descriptions\IHaveApiDescription as api;
use extas\interfaces\routes\descriptions\IJsonSchemaV1 as schema;

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

    protected function getAttributeHelp(string $desc, string $useIn, string $type, array $maxMin): array
    {
        $edges = count($maxMin) == 2 ? implode(',', $maxMin) : array_shift($maxMin);

        return [
            static::HELP__DESCRIPTION => $desc,
            static::HELP__TYPE => $type . '(' . $edges . ')'
        ];
    }

    protected function formatResponseData(array $in, array $out): array
    {
        return [
            schema::HELP__REQUEST => [
                schema::HELP__REQUEST_METHOD => $in[api::INPUT_FIELD__METHOD],
                schema::HELP__REQUEST_PARAMETERS => $in[api::INPUT_FIELD__PARAMETERS]
            ],
            schema::HELP__RESPONSE => $out[api::OUTPUT_FIELD__PARAMETERS]
        ];
    }
}
