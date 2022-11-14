<?php
namespace extas\components\routes\validators;

class VUUID extends Validator
{
    protected string $type = 'string';
    protected bool $canBeEmpty = false;
    
    protected int $length = 36;
    protected int $lengthErrorCode = 400;

    protected int $sectionSize = 5;
    protected int $sectionSizeCode = 400;

    protected function isValidData(): bool
    {
        if (strlen($this->data) != $this->length) {
            $this->addError('Incorrect UUID length. Length must be ' . $this->length, $this->lengthErrorCode);
            return false;
        }

        $sections = explode('-', $this->data);

        if (count($sections) != $this->sectionSize) {
            $this->addError(
                'Incorrect UUID structure. Exactly ' . $this->sectionSize . ' sections delimited with "-" is required',
                $this->lengthErrorCode
            );
            return false;
        }

        return $this->isValidSections($sections);
    }

    protected function isValidSections(array $sections): bool
    {
        $sectionSizes = $this->getSectionSizes();

        foreach ($sections as $index => $section) {
            if (strlen($section) != $sectionSizes[$index]) {
                $this->addError(
                    'Incorrect UUID structure. Section ' . ($index+1) . ' must has exactly ' . $sectionSizes[$index] . ' length',
                    $this->lengthErrorCode
                );
                return false;
            }
        }

        return true;
    }

    protected function getSectionSizes(): array
    {
        return [ 8, 4, 4, 4, 12 ];
    }
}
