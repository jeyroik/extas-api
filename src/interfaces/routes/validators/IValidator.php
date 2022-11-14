<?php
namespace extas\interfaces\routes\validators;

use extas\interfaces\IItem;

interface IValidator extends IItem
{
    public const SUBJECT = 'extas.route.validator';

    public const FIELD__ERRORS = 'errors';

    public const ERROR__MESSAGE = 'message';
    public const ERROR__CODE = 'code';

    public function isValid(mixed $data): bool;

    public function getErrors(): array;
}
