<?php
namespace extas\components\plugins;

use extas\components\extensions\TExtendable;
use extas\components\plugins\Plugin;
use extas\interfaces\stages\IStageApiAppInit;
use extas\interfaces\routes\IRoute;
use Slim\App;

class PluginRoutes extends Plugin implements IStageApiAppInit
{
    use TExtendable;

    public function __invoke(App &$app): void
    {  
        /**
         * @var IRoute[] $routes
         */
        $routes = $this->routes()->all([]);

        foreach ($routes as $route) {
            $method = $route->getMethod();
            
            $app->$method($route->getName(), function($request, $response, array $args) use ($route) {
                $route = $route->buildDispatcher($request, $response, $args);
                return $route->execute();
            });

            $app->$method($route->getName() . '/help', function($request, $response, array $args) use ($route) {
                $route = $route->buildDispatcher($request, $response, $args);
                return $route->help();
            });
        }
    }

    protected function getSubjectForExtension(): string
    {
        return 'extas.api.plugin.routes';
    }
}
