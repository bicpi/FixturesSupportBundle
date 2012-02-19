<?php

namespace bicpi\DataFixtures\FixturesSupportBundle;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

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
        $files = $finder->files()->in($this->getFixturesDir())->name('/.*\.yml$/i')->sortByName();

        foreach ($files as $file) {
            $fixtures = Yaml::parse(file_get_contents($file->getRealpath()));
            foreach ($fixtures as $entityName => $data) {
                $method = sprintf('load%s', (string)$entityName);
                call_user_func_array(array($this, $method), array($data));
            }
        }
    }

    public function __call($method, $arguments)
    {
        if ('load' === substr($method, 0, 4) && 4 < strlen($method)) {
            $msg = sprintf('Missing method implementation loadTeam() for entity "%s"', substr($method, 4));
            throw new \LogicException($msg);
        }
    }

    abstract public function getFixturesDir(); // return dirname(__DIR__).'/fixtures';

    public function getOrder()
    {
        return $this->order;
    }
}