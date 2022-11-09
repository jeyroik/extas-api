<?php
namespace extas\components\routes;

use extas\components\Item;
use extas\components\THasDescription;
use extas\components\THasMethod;
use extas\components\THasName;
use extas\components\THasStringId;
use extas\interfaces\routes\IRoute;

class Route extends Item implements IRoute
{
    use THasStringId;
    use THasDescription;
    use THasDispatcher;
    use THasName;
    use THasMethod;
    
    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
