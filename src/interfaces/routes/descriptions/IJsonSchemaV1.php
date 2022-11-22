<?php
namespace extas\interfaces\routes\descriptions;

interface IJsonSchemaV1
{
    public const FIELD__ERROR = 'error';
    public const FIELD__DATA = 'data';

    public const HELP__REQUEST = 'request';
    public const HELP__RESPONSE = 'response';

    public const HELP__REQUEST_METHOD = 'method';
    public const HELP__REQUEST_PARAMETERS = 'parameters';

    public const METHOD__CREATE = 'POST';
    public const METHOD__READ = 'GET';
    public const METHOD__UPDATE = 'PUT';
    public const METHOD__DELETE = 'DELETE';
    public const METHOD__INDEX = 'GET';
}
