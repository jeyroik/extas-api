<?php
namespace extas\interfaces\routes;

use extas\interfaces\IHasDescription;
use extas\interfaces\IHasName;
use extas\interfaces\IHaveMethod;
use extas\interfaces\IHaveUUID;
use extas\interfaces\IItem;

/**
 * Route
 * 
 * @field.id(description="Route ID",type=uuid,edges=[36])
 * @field.name(description="Route name",type=string,edges[1,50])
 * @field.title(description="Route title",type=string,edges[1,30])
 * @field.description(description="Route description",type=string,edges[1,100])
 * @field.method(description="Route method",type=string,edges[3,6])
 * @field.class(description="Route dispatcher class",type=string,edges[200])
 */
interface IRoute extends IItem, IHaveUUID, IHaveDispatcher, IHasDescription, IHasName, IHaveMethod
{
    public const SUBJECT = 'extas.route';
}
