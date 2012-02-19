Introduction
============

This Bundle enables ...

Installation
============

  1. Register the namespace `FOS` to your project's autoloader bootstrap script:

          //app/autoload.php
          $loader->registerNamespaces(array(
                // ...
                'bicpi'    => __DIR__.'/../vendor/bundles',
                // ...
          ));

  2. Add this bundle to your application's kernel:

          //app/AppKernel.php
          public function registerBundles()
          {
              return array(
                  // ...
                  new bicpi\YamlFixturesLoaderBundle\YamlFixturesLoaderBundle(),
                  // ...
              );
          }
