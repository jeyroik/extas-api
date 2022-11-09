<?php
namespace extas\components;

use extas\interfaces\IHaveMethod;

trait THasMethod
{
    /**
     * @description HTTP method
     * 
     * @return string [3,6]
     */
    public function getMethod(): string
    {
        return $this->config[IHaveMethod::FIELD__METHOD] ?? '';
    }

    public function setMethod(string $method): self
    {
        $this->config[IHaveMethod::FIELD__METHOD] = $method;

        return $this;
    }
}
