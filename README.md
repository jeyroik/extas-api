![tests](https://github.com/jeyroik/extas-api/workflows/PHP%20Composer/badge.svg?branch=master&event=push)
![codecov.io](https://codecov.io/gh/jeyroik/extas-api/coverage.svg?branch=master)
<a href="https://github.com/phpstan/phpstan"><img src="https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat" alt="PHPStan Enabled"></a>
<a href="https://codeclimate.com/github/jeyroik/extas-api/maintainability"><img src="https://api.codeclimate.com/v1/badges/1363a8cd36dd22990793/maintainability" /></a>
<a href="https://github.com/jeyroik/extas-installer/" title="Extas Installer v3"><img alt="Extas Installer v3" src="https://img.shields.io/badge/installer-v4-green"></a>
[![Latest Stable Version](https://poser.pugx.org/jeyroik/extas-api/v)](//packagist.org/packages/jeyroik/extas-q-crawlers)
[![Total Downloads](https://poser.pugx.org/jeyroik/extas-api/downloads)](//packagist.org/packages/jeyroik/extas-q-crawlers)
[![Dependents](https://poser.pugx.org/jeyroik/extas-api/dependents)](//packagist.org/packages/jeyroik/extas-q-crawlers)

# Description

Api for extas.

# Using

- `php -S 0.0.0.0:8080 -t vendor/jeyroik/extas-api/public`.
- You can add your own routes with a plugin by stage `extas.api.app.init` (see `src/interfaces/extensions/IStageApiAppInit` for details).

Plugin example:

```php
use extas\components\plugins\Plugin;
use extas\interfaces\stages\IStageApiAppInit;
use Slim\App;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class PluginOwnRoute extends Plugin implements IStageApiAppInit
{
    /**
     * @param App $app
     */
    public function __invoke(App &$app): void
    {
        $app->post(// post/get/delete/put/patch/any/
            '/my/route',
            function (RequestInterface $request, ResponseInterface $response, array $args) {
                // dispatching 
            }
        );
    }
}
```

## Using routes

//extas.app.storage.json
```json
{
    "plugins": [
        {
            "class": "\\extas\\components\\plugins\\PluginRoutes",
            "stage": "extas.api.app.init"
        }
    ]
}
```

//extas.app.json
```json
{
    "routes": [
        {
            "name": "/json/v1/my-items",
            "title": "Items list",
            "description": "My items list",
            "method": "get",
            "class": "\\some\\routes\\ClassName"
        }
    ]
}
```

// ClassName.php - route dispatcher class
```php
<?php
namespace some\routes;

use extas\components\routes\dispatchers\JsonDispatcher;

/**
 * @api__input_method get
 * @api__input.id(required=true,validate=\extas\components\routes\validators\VUUID,description="ID",type=uuid,edges=[36])
 * @api__input.name(required=true,validate=0,description="Name",type=string,edges=[1,36])
 * 
 * @api__output.one \my\interfaces\IMyItem
 */
class ClassName extends JsonDispatcher
{
    use TRouteList;
    use TRouteHelp;

    protected string $repoName = 'my_items';
}
```

// my\interfaces\IMyItem
```php
<?php
namespace my\interfaces;

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
class IMyItem
{
    //...
}
```

From here you can touch 2 routes:

- GET /json/v1/my-items : you should see a list of my_items items.
- GET /json/v1/my-items/help : you should see request/response description.