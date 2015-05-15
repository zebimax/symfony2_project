<?php

namespace AppBundle\Tests\Unit\Twig;

use AppBundle\Entity\Role;
use AppBundle\Twig\UserExtension;

class UserExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UserExtension
     */
    protected $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $translatorMock;

    protected function setUp()
    {
        $this->translatorMock = $this
            ->getMockBuilder('Symfony\Component\Translation\TranslatorInterface')
            ->getMock();
        $this->translatorMock
            ->expects($this->any())
            ->method('trans')
            ->willReturnArgument(0);
        $this->object = new UserExtension($this->translatorMock);
    }

    /**
     * @covers AppBundle\Twig\UserExtension::getName
     */
    public function testGetName()
    {
        $this->assertEquals('app_user_extension', $this->object->getName());
    }

    /**
     * @covers AppBundle\Twig\UserExtension::getFilters
     */
    public function testGetFilters()
    {
        $this->assertEquals(
            [
                new \Twig_SimpleFilter('renderPrimaryRole', [$this->object, 'renderPrimaryRole']),
                new \Twig_SimpleFilter('renderIsActive', [$this->object, 'renderIsActive']),
            ],
            $this->object->getFilters()
        );
    }

    /**
     * @covers       AppBundle\Twig\UserExtension::renderPrimaryRole
     * @dataProvider renderPrimaryRoleDataProvider
     * @param string $role
     * @param string $renderRole
     */
    public function testRenderPrimaryRole($role, $renderRole)
    {
        $this->assertEquals($renderRole, $this->object->renderPrimaryRole($role));
    }

    /**
     * @covers AppBundle\Twig\UserExtension::renderIsActive
     */
    public function testRenderIsActive()
    {
        $this->assertEquals('+', $this->object->renderIsActive(true));
        $this->assertEquals('-', $this->object->renderIsActive(false));
    }

    /**
     * @return array
     */
    public function renderPrimaryRoleDataProvider()
    {
        return [
            Role::ADMINISTRATOR => ['role' => Role::ADMINISTRATOR, 'renderRole' => 'role.administrator'],
            Role::MANAGER       => ['role' => Role::MANAGER, 'renderRole' => 'role.manager'],
            Role::OPERATOR      => ['role' => Role::OPERATOR, 'renderRole' => 'role.operator'],
            'incorrect_role'    => ['role' => 'incorrect_role', 'renderRole' => 'role.undefined']
        ];
    }
}
