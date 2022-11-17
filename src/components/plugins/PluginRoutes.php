<?php
namespace extas\components\plugins;

use extas\components\extensions\TExtendable;
use extas\components\plugins\Plugin;
use extas\components\routes\dispatchers\HelpDispatcher;
use extas\interfaces\stages\IStageApiAppInit;
use extas\interfaces\routes\IRoute;
use extas\interfaces\routes\IRouteDispatcher;
use Slim\App;

class PluginRoutes extends Plugin implements IStageApiAppInit
{
    use TExtendable;

    protected array $routesList = [[
        'route' => '/help',
        'title' => 'Routes list',
        'description' => 'The current route, shows all available routes',
        'method' => 'get'
    ]];

    public function __invoke(App &$app): void
    {  
        /**
         * @var IRoute[] $routes
         */
        $routes = $this->routes()->all([]);

        foreach ($routes as $route) {
            $this->attachToRouteslist($route);
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

        $app->get('/help', function($request, $response, array $args) {
            
            return $this->getHelpDispatcher($request, $response, $args)->execute();
        });
    }

    protected function getHelpDispatcher($request, $response, array $args): IRouteDispatcher
    {
        HelpDispatcher::$routesList = $this->routesList;
        return new HelpDispatcher($request, $response, $args);
    }

    protected function attachToRouteslist(IRoute $route): void
    {
        $this->routesList[] = [
            'route' => $route->getName(),
            'title' => $route->getTitle(),
            'description' => $route->getDescription(),
            'method' => $route->getMethod()
        ];
        $this->routesList[] = [
            'route' => $route->getName() . '/help',
            'title' => 'Docs for ' . $route->getTitle(),
            'description' => 'Help for route ' . $route->getTitle(),
            'method' => 'get'
        ];
    }

    protected function getSubjectForExtension(): string
    {
        return 'extas.api.plugin.routes';
    }
}
