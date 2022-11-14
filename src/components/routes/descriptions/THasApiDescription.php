<?php
namespace extas\components\routes\descriptions;

use extas\components\Plugins;
use extas\interfaces\routes\descriptions\IHaveApiDescription as i;
use extas\interfaces\stages\IStageInputDescription;
use extas\interfaces\stages\IStageOutputDescription;
use ReflectionClass;

trait THasApiDescription
{
    protected function getOutputDescription(bool $forHelp =  false): array
    {
        $r = new ReflectionClass($this);
        $doc = $r->getDocComment();

        $result = [
            i::OUTPUT_FIELD__PARAMETERS => $this->getOutputParameters($doc, $forHelp)
        ];

        foreach (Plugins::byStage(IStageOutputDescription::NAME) as $plugin) {
            $plugin($result, $forHelp);
        }

        return $result;
    }

    protected function getOutputParameters(string $doc, $forHelp): array
    {
        $one = $this->getOutputOne($doc, $forHelp, i::OUTPUT__ONE);

        if (!empty($one)) {
            return $one;
        }

        $many = $this->getOutputOne($doc, $forHelp, i::OUTPUT__MANY);

        return [i::OUTPUT_FIELD__ITEMS => $many];
    }

    protected function getOutputOne(string $doc, bool $forHelp, string $pattern): array
    {
        preg_match('/\@api__output\.'.$pattern.'\s(\S+)/', $doc, $matches);

        if (empty($matches)) {
            return [];
        }

        $className = trim($matches[1]);
        $r = new ReflectionClass($className);
        $docSub = $r->getDocComment();

        return $this->getClassFieldsDescription($docSub, $forHelp);
    }

    protected function getClassFieldsDescription(string $doc, bool $forHelp): array
    {
        preg_match_all(
            '/\@field\.(.*)\(description="(.*)",type=(.*),edges\[(.*)\]/m',
            $doc,
            $matches
        );

        if (empty($matches[0])) {
            return [];
        }

        $result = [];

        foreach ($matches[1] as $index => $propertyName) {
            $result[$propertyName] = [
              i::OUT_PROP__DESCRIPTION => $matches[2][$index],
            ];

            $this->attachInputType(
                $result[$propertyName],
                $matches[3][$index],
                $this->convertTypeEdges($matches[4][$index]),
                $forHelp
            );
        }

        return $result;
    }

    protected function getInputDescription(bool $forHelp = false): array
    {
        $r = new ReflectionClass($this);
        $doc = $r->getDocComment();

        $result = [
            i::INPUT_FIELD__METHOD => $this->getInputMethod($doc),
            i::INPUT_FIELD__PARAMETERS => $this->getInputParameters($doc, $forHelp)
        ];

        foreach (Plugins::byStage(IStageInputDescription::NAME) as $plugin) {
            $plugin($result, $forHelp);
        }

        return $result;
    }

    protected function getInputParameters(string $doc, bool $forHelp): array
    {
        preg_match_all(
            '/\@api__input\.(\S+)\(required=(\S+),validate=(\S+),description="(.*)",type=(\S+),edges=\[(\S+)\]\)/',
            $doc,
            $matches
        );

        if (empty($matches[0])) {
            return [];
        }

        $result = [];

        foreach ($matches[1] as $index => $propertyName) {
            $result[$propertyName] = [
              i::PROP__REQUIRED => $this->convertRequired($matches[2][$index]),
              i::PROP__VALIDATOR => $matches[3][$index],
              i::PROP__DESCRIPTION => $matches[4][$index]
            ];

            $this->attachInputType(
                $result[$propertyName],
                $matches[5][$index],
                $this->convertTypeEdges($matches[6][$index]),
                $forHelp
            );
        }

        return $result;
    }

    protected function convertRequired(string $required): bool
    {
        return $required == 'true' ? true : false;
    }

    protected function attachInputType(array &$item, string $type, array $edges, bool $forHelp): void
    {
        if ($forHelp) {
            $item[i::PROP__TYPE] = $type . '(' . (count($edges) == 2 ? implode(',', $edges) : array_shift($edges)) . ')';
        } else {
            $item[i::PROP__TYPE] = $type;
            $item[i::PROP__TYPE_EDGES] = $edges;
        }
    }

    protected function convertTypeEdges(string $edges): array
    {
        return explode(',', $edges);
    }

    protected function getInputMethod(string $doc, string $default = 'get'): string
    {
        preg_match('/\@api__input_method\s(\S+)/', $doc, $matches);

        if (!empty($matches)) {
            return trim($matches[1]);
        }

        return $default;
    }
}
