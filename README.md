Introduction
============

This Bundle implements a LoadData class to simply fixture loading.

Installation
============

  1. Register the namespace `bicpi` to your project's autoloader bootstrap script:

          // app/autoload.php
          $loader->registerNamespaces(array(
                // ...
                'bicpi'    => __DIR__.'/../vendor/bundles',
                // ...
          ));

  2. Add this bundle to your application's kernel:

          // app/AppKernel.php
          public function registerBundles()
          {
              return array(
                  // ...
                  new bicpi\YamlFixturesLoaderBundle\YamlFixturesLoaderBundle(),
                  // ...
              );
          }
  3. Extend your fixture LoadData class from bicpi\YamlFixturesLoaderBundle\LoadData,
     implement getFixturesDir method returing the absolute path to your YAML fixtures dir
     ans implement a loadXXX() entity method per entity class you want to load from your
     fixture file(s)

          // YourBundle/DataFixtures/ORM/LoadData.php
          use bicpi\YamlFixturesLoaderBundle\LoadData as BaseLoadData;

          class LoadData extends BaseLoadData
          {
              public function getFixturesDir()
              {
                  // return dirname(__DIR__).'/fixtures';
              }

              public function loadMyEntity()
              {
                  $myEntity = new MyEntity();
                  $myEntity->setProperty1()
                  $this->manager->persist($myEntity);
              }
          }

  4. Put entity fixtures in your fixtures dir, e.g.

          // YourBundle/DataFixtures/fixtures/fixtures.yml
          MyEntity:
              my_entity_1:
                  property_1: Test
                  property_2: foo
              my_entity_2:
                  property_1: Bla bla
                  property_2: bar
              # ...
