<?php
namespace tests\resources;

class Simple
{
    protected array $params = [];

    public function __construct($par1, $par2)
    {
        $this->params[] = $par1;
        $this->params[] = $par2;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
