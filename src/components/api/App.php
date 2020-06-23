<?php
namespace extas\components\api;

use extas\components\Plugins;
use extas\interfaces\stages\IStageApiAppInit;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Factory\AppFactory;
use Slim\Interfaces\CallableResolverInterface;
use Slim\Interfaces\MiddlewareDispatcherInterface;
use Slim\Interfaces\RouteCollectorInterface;
use Slim\Interfaces\RouteResolverInterface;

/**
 * Class App
 *
 * @package extas\components\api
 * @author jeyroik@gmail.com
 */
class App extends AppFactory
{
    /**
     * @param ResponseFactoryInterface|null $responseFactory
     * @param ContainerInterface|null $container
     * @param CallableResolverInterface|null $callableResolver
     * @param RouteCollectorInterface|null $routeCollector
     * @param RouteResolverInterface|null $routeResolver
     * @param MiddlewareDispatcherInterface|null $middlewareDispatcher
     * @return \Slim\App
     */
    public static function create(
        ?ResponseFactoryInterface $responseFactory = null,
        ?ContainerInterface $container = null,
        ?CallableResolverInterface $callableResolver = null,
        ?RouteCollectorInterface $routeCollector = null,
        ?RouteResolverInterface $routeResolver = null,
        ?MiddlewareDispatcherInterface $middlewareDispatcher = null
    ): \Slim\App
    {
        $app = parent::create(
            $responseFactory,
            $container,
            $callableResolver,
            $routeCollector,
            $routeResolver,
            $middlewareDispatcher
        );

        foreach (Plugins::byStage(IStageApiAppInit::NAME) as $plugin) {
            /**
             * @var IStageApiAppInit $plugin
             */
            $plugin($app);
        }

        return $app;
    }
}
