![tests](https://github.com/jeyroik/extas-api/workflows/PHP%20Composer/badge.svg?branch=master&event=push)
![codecov.io](https://codecov.io/gh/jeyroik/extas-api/coverage.svg?branch=master)
<a href="https://github.com/phpstan/phpstan"><img src="https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat" alt="PHPStan Enabled"></a>
<a href="https://codeclimate.com/github/jeyroik/extas-api/maintainability"><img src="https://api.codeclimate.com/v1/badges/1363a8cd36dd22990793/maintainability" /></a>
<a href="https://github.com/jeyroik/extas-installer/" title="Extas Installer v3"><img alt="Extas Installer v3" src="https://img.shields.io/badge/installer-v3-green"></a>
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