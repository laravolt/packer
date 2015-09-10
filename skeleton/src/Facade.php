<?php
namespace :VendorName\:PackageName;

class Facade extends \Illuminate\Support\Facades\Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return ':package_name';
    }
}
