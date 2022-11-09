<?php
namespace extas\interfaces\routes;

use extas\interfaces\IHasDescription;
use extas\interfaces\IHasName;
use extas\interfaces\IHaveMethod;
use extas\interfaces\IHaveUUID;
use extas\interfaces\IItem;

interface IRoute extends IItem, IHaveUUID, IHaveDispatcher, IHasDescription, IHasName, IHaveMethod
{
    public const SUBJECT = 'extas.route';
}
