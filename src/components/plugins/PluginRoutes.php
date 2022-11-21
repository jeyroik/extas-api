<?php
namespace extas\components\plugins;

use extas\components\extensions\TExtendable;
use extas\components\Plugins;
use extas\components\plugins\Plugin;
use extas\components\routes\dispatchers\HelpDispatcher;
use extas\interfaces\stages\IStageApiAppInit;
use extas\interfaces\routes\IRoute;
use extas\interfaces\routes\IRouteDispatcher;
use extas\interfaces\stages\IStageApiAfterRouteExecute;
use extas\interfaces\stages\IStageApiBeforeRouteExecute;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
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
            $this->addRoute($app, $route);
        }

        $app->get('/help', function($request, $response, array $args) {
            
            return $this->getHelpDispatcher($request, $response, $args)->execute();
        });
    }

    protected function addRoute(App &$app, IRoute $route): void
    { 
        $method = $route->getMethod();

        $app->$method($route->getName(), function($request, $response, array $args) use ($route) {
            if (!$this->beforeRouteExecute($request, $response, $args, $route)) {
                return $response;
            }
            $dispatcher = $route->buildDispatcher($request, $response, $args);
            $response = $dispatcher->execute();
            
            $this->afterRouteExecute($request, $response, $args, $route);

            return $response;
        });

        $this->addHelpRoute($app, $route, $method);
    }

    protected function addHelpRoute(App &$app, IRoute $route, string $method): void
    {
        $app->$method($route->getName() . '/help', function($request, $response, array $args) use ($route) {
            return $route->buildDispatcher($request, $response, $args)->help();
        });
    }

    protected function beforeRouteExecute(
        RequestInterface $request, 
        ResponseInterface &$response, 
        array &$args, 
        IRoute $route
    ): bool
    {
        foreach (Plugins::byStage(IStageApiBeforeRouteExecute::NAME) as $plugin) {
            $plugin($request, $response, $args, $route);

            if ($response->getStatusCode() !== 200) {
                return false;
            }
        }

        foreach (Plugins::byStage(IStageApiBeforeRouteExecute::NAME . '.' . $route->getName()) as $plugin) {
            $plugin($request, $response, $args, $route);

            if ($response->getStatusCode() !== 200) {
                return false;
            }
        }

        return true;
    }

    protected function afterRouteExecute($request, &$response, array &$args, IRoute $route): void
    {
        foreach (Plugins::byStage(IStageApiAfterRouteExecute::NAME) as $plugin) {
            $plugin($request, $response, $args, $route);
        }

        foreach (Plugins::byStage(IStageApiAfterRouteExecute::NAME . '.' . $route->getName()) as $plugin) {
            $plugin($request, $response, $args, $route);
        }
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
