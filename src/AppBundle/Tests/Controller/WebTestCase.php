<?php

namespace AppBundle\Tests\Controller;

use Doctrine\Common\DataFixtures\Executor\AbstractExecutor;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class WebTestCase extends BaseWebTestCase
{
    /** @var Client */
    protected $client;

    /** @var ReferenceRepository */
    private $referenceRepository;

    /** @var array */
    protected $fixtures = [];

    protected function setUp()
    {
        $this->setFixtures();
        if (count($this->fixtures)) {
            /** @var AbstractExecutor $executor */
            $executor = $this->loadFixtures($this->fixtures);
            $this->referenceRepository = $executor->getReferenceRepository();
        }
        $this->initClient();
    }

    protected function initClient()
    {
        $this->client = static::makeClient(true);
    }

    /**
     * @param $name
     * @return object
     */
    protected function getReference($name)
    {
        if ($this->referenceRepository === null) {
            throw new \LogicException('Reference repository doesn\'t set');
        }
        return $this->referenceRepository->getReference($name);
    }

    protected function setFixtures()
    {

    }
}
