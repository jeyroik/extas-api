<?php
namespace extas\interfaces;

interface IHaveMethod
{
    public const FIELD__METHOD = 'method';

    public const METHOD__CREATE = 'post';
    public const METHOD__READ = 'get';
    public const METHOD__UPDATE = 'put';
    public const METHOD__DELETE = 'delete';

    public function getMethod(): string;
    public function setMethod(string $method): self;
}
