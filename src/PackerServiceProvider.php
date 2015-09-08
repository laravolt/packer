<?php

namespace Laravolt\Packer;

use Illuminate\Support\ServiceProvider;

/**
 * This is the service provider.
 *
 * Place the line below in the providers array inside app/config/app.php
 * <code>'Laravolt\Packer\PackerServiceProvider',</code>
 *
 * @package Packer
 * @author uyab
 *
 **/
class PackerServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * The console commands.
     *
     * @var bool
     */
    protected $commands = [
        'Laravolt\Packer\PackerNewCommand'
    ];

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Not really anything to boot.
    }

    /**
     * Register the command.
     *
     * @return void
     */
    public function register()
    {
        $this->commands($this->commands);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('laravolt.packer');
    }
}
