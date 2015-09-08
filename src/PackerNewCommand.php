<?php

namespace Laravolt\Packer;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;


/**
 * Create a brand new package.
 *
 * @package Packager
 * @author uyab
 *
 **/
class PackerNewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "packer:new {vendor} {name}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new package.';

    /**
     * Packager helper class
     * @var object
     */
    protected $helper;

    /**
     * Create a new command instance.
     *
     * @param \Laravolt\Packer\PackerHelper $helper
     */
    public function __construct(PackerHelper $helper)
    {
        parent::__construct();
        $this->helper = $helper;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Start the progress bar
        $bar = $this->helper->barSetup($this->output->createProgressBar(7));
        $bar->start();

        // Common variables
        $vendor = $this->argument('vendor');
        $name = $this->argument('name');
        $path = getcwd().'/packages/';
        $fullPath = $path.$vendor.'/'.$name;
        $cVendor = ucfirst($vendor);
        $cName = ucfirst($name);
        $requirement = '"psr-4": {
            "'.$cVendor.'\\\\'.$cName.'\\\\": "packages/'.$vendor.'/'.$name.'/src",';
        $appConfigLine = 'App\Providers\RouteServiceProvider::class,
        '.$cVendor.'\\'.$cName.'\\'.'ServiceProvider::class,';

        // Start creating the package
        $this->info('Creating package '.$vendor.'\\'.$name.'...');
            $this->helper->checkExistingPackage($path, $vendor, $name);
        $bar->advance();

        // Create the package directory
        $this->info('Creating packages directory...');
            $this->helper->makeDir($path);
        $bar->advance();

        // Create the vendor directory
        $this->info('Creating vendor...');
         $this->helper->makeDir($path.$vendor);
        $bar->advance();

        // Copying package skeleton
        $this->info('Copying package skeleton...');
        File::copyDirectory(__DIR__ . '/../skeleton', $fullPath);

        foreach(File::allFiles($fullPath) as $file) {
            $search = [':vendor_name', ':VendorName', ':package_name', ':PackageName'];
            $replace = [$vendor, $cVendor, $name, $cName];
            $this->helper->replaceAndSave($file, $search, $replace);
        }

        $bar->advance();


        // Add it to composer.json
        $this->info('Adding package to composer and app...');
        $this->helper->replaceAndSave(getcwd().'/composer.json', '"psr-4": {', $requirement);
         //And add it to the providers array in config/app.php
        $this->helper->replaceAndSave(getcwd().'/config/app.php', 'App\Providers\RouteServiceProvider::class,', $appConfigLine);
        $bar->advance();

        // Finished creating the package, end of the progress bar
        $bar->finish();
        $this->info('Package created successfully!');
        $this->output->newLine(2);
        $bar = null;
    }
}
