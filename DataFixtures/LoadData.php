<?php

namespace bicpi\FixturesSupportBundle\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

abstract class LoadData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    protected $container;
    protected $manager;
    protected $order = 1;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        foreach ($this->getYamlFiles() as $file) {
            $fixtures = Yaml::parse(file_get_contents($file->getRealpath()));
            foreach ($fixtures as $entityName => $data) {
                $this->loadEntityFixtures($entityName, $data);
            }
        }
    }

    protected function getYamlFiles()
    {
        $finder = new Finder();
        return $finder
            ->files()
            ->in($this->getFixturesDir())
            ->name('/.*\.yml$/i')
            ->sortByName()
        ;
    }

    protected function loadEntityFixtures($entityName, array $data)
    {
        $method = 'load'.$entityName;
        if (!method_exists($this, $method)) {
            $msg = sprintf('Missing method implementation load%s() for entity "%s"', $entityName, $entityName);
            throw new \LogicException($msg);
        }
        call_user_func_array(array($this, $method), array($data));
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return $this->order;
    }

    /**
     * @abstract
     *
     * Return the directory where your Yaml fixtures live in
     *
     * @return string
     */
    abstract public function getFixturesDir();
}
