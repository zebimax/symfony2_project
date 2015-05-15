<?php

namespace AppBundle\Tests\Unit\Service;

use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use AppBundle\Service\ProjectFormsService;

class ProjectFormsServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProjectFormsService
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

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $usersMock;

    protected function setUp()
    {
        $this->usersMock = $this
            ->getMockBuilder('AppBundle\Entity\Repository\Users')
            ->disableOriginalConstructor()
            ->getMock();
        $this->emMock    = $this
            ->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();
        $this->emMock
            ->expects($this->any())
            ->method('getRepository')
            ->willReturn($this->usersMock);
        $translatorMock = $this
            ->getMockBuilder('Symfony\Component\Translation\TranslatorInterface')
            ->getMock();
        $translatorMock
            ->expects($this->any())
            ->method('trans')
            ->willReturn(null);
        $this->formFactoryMock = $this
            ->getMockBuilder('Symfony\Component\Form\FormFactoryInterface')
            ->getMock();
        $this->object          = new ProjectFormsService($this->emMock, $translatorMock, $this->formFactoryMock);
    }

    /**
     * @covers AppBundle\Service\ProjectFormsService::getMembersForm
     */
    public function testGetMembersForm()
    {
        $users = [
            ['id' => 1, 'username' => 'test_username1'],
            ['id' => 2, 'username' => 'test_username2'],
            ['id' => 3, 'username' => 'test_username3']
        ];
        $this->usersMock
            ->expects($this->once())
            ->method('getNotProjectUsers')
            ->willReturn($users);
        $this->formFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with(
                'app_project_member',
                null,
                [
                    'data' => [
                        $users[0]['id'] => $users[0]['username'],
                        $users[1]['id'] => $users[1]['username'],
                        $users[2]['id'] => $users[2]['username']
                    ]
                ]
            );
        $this->object->getMembersForm(new Project());
    }

    /**
     * @covers AppBundle\Service\ProjectFormsService::getProjectForm
     */
    public function testGetProjectForm()
    {
        $project = new Project();
        $this->formFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with('app_project', $project);
        $this->object->getProjectForm($project);
    }

    /**
     * @covers AppBundle\Service\ProjectFormsService::addMember
     */
    public function testAddMember()
    {
        $user          = new User();
        $project       = new Project();
        $formMock      = $this
            ->getMockBuilder('Symfony\Component\Form\FormInterface')
            ->getMock();
        $usersFormMock = $this
            ->getMockBuilder('Symfony\Component\Form\FormInterface')
            ->getMock();

        $usersFormMock
            ->expects($this->once())
            ->method('getData');
        $formMock
            ->expects($this->once())
            ->method('get')
            ->with('users')
            ->willReturn($usersFormMock);
        $this->usersMock
            ->expects($this->once())
            ->method('find')
            ->willReturn($user);
        $this->emMock
            ->expects($this->once())
            ->method('persist')
            ->with($project);
        $this->emMock
            ->expects($this->once())
            ->method('flush');

        $this->object->addMember($project, $formMock);
    }
}
