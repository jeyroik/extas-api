<?php
namespace extas\components\routes;

use extas\components\exceptions\MissedOrUnknown;
use extas\components\THasClass;

trait THasDispatcher
{
    use THasClass;

    public function buildDispatcher(...$params): mixed
    {
        $className = $this->getClass();
        $class = class_exists($className)
            ? new $className(...$params)
            : $this->getByMagic($className, $params);

        if (!$class) {
            throw new MissedOrUnknown('class "' . $className . '"');
        }

        return $class;
    }
}
