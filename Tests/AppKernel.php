<?php

namespace Debril\FeedIoBundle\Tests;


use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{

    public function registerBundles()
    {
        $bundles = array(
            // Dependencies
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            // My Bundle to test
            new Debril\FeedIoBundle\DebrilFeedIoBundle(),
        );

        return $bundles;
    }
        
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        // We don't need that Environment stuff, just one config
        $loader->load(__DIR__ . '/Controller/App/config.yml');
    }
}