<?php
namespace extas\interfaces\routes\descriptions;

interface IHaveApiDescription
{
    public const PROP__REQUIRED = 'required';
    public const PROP__VALIDATOR = 'validator';
    public const PROP__DESCRIPTION = 'description';
    public const PROP__USE_IN = 'use_in';
    public const PROP__TYPE = 'type';
    public const PROP__TYPE_EDGES = 'type_edges';
    public const PROP__RELATIONS = 'relations';

    public const INPUT_FIELD__METHOD = 'method';
    public const INPUT_FIELD__PARAMETERS = 'parameters';

    public const OUTPUT_FIELD__PARAMETERS = 'parameters';
    public const OUTPUT_FIELD__ITEMS = 'items';

    public const OUT_PROP__DESCRIPTION = 'description';
    public const OUT_PROP__TYPE = 'type';
    public const OUT_PROP__TYPE_EDGES = 'type_edges';

    public const OUTPUT__MANY = 'many';
    public const OUTPUT__ONE = 'one';
}
