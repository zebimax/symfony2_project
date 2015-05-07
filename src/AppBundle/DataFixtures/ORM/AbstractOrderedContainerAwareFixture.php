<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

abstract class AbstractOrderedContainerAwareFixture extends AbstractFixture implements
    ContainerAwareInterface,
    OrderedFixtureInterface
{
    use ContainerAwareTrait;
    abstract public function getOrder();
    abstract public function load(ObjectManager $manager);
}
