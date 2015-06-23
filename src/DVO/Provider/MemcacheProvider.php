<?php

namespace DVO\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Memcache Provider
 *
 * @package Cache Class
 * @author
 **/
class MemcacheProvider implements ServiceProviderInterface
{
    /**
     * Boot.
     *
     * @param Application $app The application.
     *
     * @return void
     */
    public function boot(Application $app)
    {

    }

    /**
     * Register the provider.
     *
     * @param Application $app The application.
     *
     * @return \DVO\Cache
     */
    public function register(Application $app)
    {
        $app['memcache'] = $app->share(function () use ($app) {
            $memcache          = $app['cache'];
            $memcache->enabled = $app['caching'];
            $servers           = $app['config']['memcache_servers'];

            array_walk($servers, function ($server) use ($memcache) {
                $memcache->addServer($server['host'], $server['port']);
            });
            return $memcache;
        });
    }
}
