<?php
namespace extas\components\routes\validators;

use extas\components\Item;
use extas\interfaces\routes\validators\IValidator;

abstract class Validator extends Item implements IValidator
{
    protected string $type = '';
    protected $data = null;
    protected int $invalidTypeCode = 400;

    protected bool $canBeEmpty = true; 
    protected int $emptyCode = 400;

    public function __construct(array $config = [])
    {
        if (!isset($config[static::FIELD__ERRORS])) {
            $config[static::FIELD__ERRORS] = [];
        }

        parent::__construct($config);
    }

    public function isValid(mixed $data): bool
    {
        $this->data = $data;

        if (!$this->isValidType()) {
            $this->addError('Invalid type. ' . ucfirst($this->type) . ' is required.', $this->invalidTypeCode);
            return false;
        }

        if (!$this->canBeEmpty && empty($this->data)) {
            $this->addError('Can not be empty', $this->emptyCode);
            return false;
        }

        return $this->isValidData();
    }

    public function getErrors(): array
    {
        return $this->config[static::FIELD__ERRORS] ?? [];
    }

    abstract protected function isValidData(): bool;

    protected function isValidType(): bool
    {
        $typeFunc = 'is_' . $this->type;

        return $typeFunc($this->data);
    }

    protected function addError(string $message, int $code): self
    {
        $this->config[static::FIELD__ERRORS][] = [
            static::ERROR__MESSAGE => $message,
            static::ERROR__CODE => $code
        ];

        return $this;
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
