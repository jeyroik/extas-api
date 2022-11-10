<?php
namespace extas\interfaces\routes\descriptions;

interface IJsonSchemaV1
{
    public const FIELD__ERROR = 'error';
    public const FIELD__DATA = 'data';

    public const HELP__REQUEST = 'request';
    public const HELP__RESPONSE = 'response';

    public const HELP__DESCRIPTION = 'description';
    public const HELP__USE_IN = 'use_in';
    public const HELP__TYPE = 'type';

    public const HELP__REQUEST_METHOD = 'method';
    public const HELP__REQUEST_PARAMETERS = 'parameters';

    public const METHOD__GET = 'GET';
    public const METHOD__POST = 'POST';
    public const METHOD__PUT = 'PUT';
    public const METHOD__DELETE = 'DELETE';

    public const USE_IN__UI = 'ui';
    public const USE_IN__CODE = 'code';

    public const TYPE__STRING = 'string';
    public const TYPE__UUID = 'uuid';
    public const TYPE__INT = 'int';
}
