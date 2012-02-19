<?php

namespace bicpi\Bundle\YamlFixturesLoaderBundle;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;
use Doctrine\Common\Persistence\ObjectManager;

abstract class LoadData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    protected $order = 1;
    protected $container;
    protected $manager;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $finder = new Finder();
        $files = $finder->files()->in(dirname(__DIR__).'/fixtures')->name('/.*\.yml$/i')->sortByName();

        foreach ($files as $file) {
            $fixtures = Yaml::parse(file_get_contents($file->getRealpath()));
            foreach ($fixtures as $entityName => $data) {
                $method = sprintf('load%s', (string)$entityName);
                call_user_func_array(array($this, $method), array($data));
            }
        }
    }

    public function getOrder()
    {
        return $this->order;
    }
}