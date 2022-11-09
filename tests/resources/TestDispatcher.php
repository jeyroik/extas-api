<?php
namespace tests\resources;

class TestDispatcher
{
    protected array $params = [];

    public function __construct(int $par1, int $par2)
    {
        $this->params[] = $par1;
        $this->params[] = $par2;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
