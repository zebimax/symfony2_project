<?php

namespace AppBundle\Tests\Unit\Service;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Service\UserFormService;

class UserFormServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UserFormService
     */
    protected $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $formFactoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $emMock;

    protected function setUp()
    {
        $this->emMock          = $this
            ->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();
        $translatorMock        = $this
            ->getMockBuilder('Symfony\Component\Translation\TranslatorInterface')
            ->getMock();
        $this->formFactoryMock = $this
            ->getMockBuilder('Symfony\Component\Form\FormFactoryInterface')
            ->getMock();
        $this->object          = new UserFormService($this->emMock, $translatorMock, $this->formFactoryMock);
    }

    /**
     * @covers       AppBundle\Service\UserFormService::getUserForm
     * @dataProvider getUserFormDataProvider
     * @param User $currentUser
     * @param bool $willAddRoles
     */
    public function testGetUserForm(User $currentUser, $willAddRoles)
    {
        $user = new User();

        $formInvocationMocker = $this->formFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with('app_user', $user);
        if ($willAddRoles) {
            $builderMock = $this
                ->getMockBuilder('Symfony\Component\Form\FormInterface')
                ->getMock();
            $builderMock
                ->expects($this->once())
                ->method('add')
                ->with(
                    'roles',
                    'entity',
                    [
                        'required' => true,
                        'class'    => 'AppBundle:Role',
                        'property' => 'name',
                        'multiple' => true,
                        'attr'     => ['class' => 'form-control'],
                    ]
                );
            $formInvocationMocker->willReturn($builderMock);
        }
        $this->formFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with('app_user', $user);
        $this->object->getUserForm($user, $currentUser);
    }

    /**
     * @covers AppBundle\Service\UserFormService::saveUser
     */
    public function testSaveUser()
    {
        $user = new User();
        $this->emMock
            ->expects($this->once())
            ->method('persist')
            ->with($user);
        $this->emMock
            ->expects($this->once())
            ->method('flush');
        $this->object->saveUser($user);
    }

    /**
     * @return array
     */
    public function getUserFormDataProvider()
    {
        $admin    = (new User())
            ->addRole((new Role())->setRole(Role::ADMINISTRATOR));
        $manager  = (new User())
            ->addRole((new Role())->setRole(Role::MANAGER));
        $operator = (new User())
            ->addRole((new Role())->setRole(Role::OPERATOR));

        return [
            Role::ADMINISTRATOR => [
                'currentUser'  => $admin,
                'willAddRoles' => true
            ],
            Role::MANAGER       => [
                'currentUser'  => $manager,
                'willAddRoles' => false
            ],
            Role::OPERATOR      => [
                'currentUser'  => $operator,
                'willAddRoles' => false
            ]
        ];
    }
}
