<?php

namespace AppBundle\Tests\Unit\Service;

use AppBundle\Entity\User;
use AppBundle\Service\UserService;

class UserServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UserService
     */
    protected $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $emMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $paginatorMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $issuesMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $usersMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $issueActivitiesMock;

    protected function setUp()
    {
        $this->usersMock           = $this
            ->getMockBuilder('AppBundle\Entity\Repository\Users')
            ->disableOriginalConstructor()
            ->getMock();
        $this->issuesMock          = $this
            ->getMockBuilder('AppBundle\Entity\Repository\Issues')
            ->disableOriginalConstructor()
            ->getMock();
        $this->issueActivitiesMock = $this
            ->getMockBuilder('AppBundle\Entity\Repository\IssueActivities')
            ->disableOriginalConstructor()
            ->getMock();
        $this->emMock              = $this
            ->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();
        $this->emMock
            ->expects($this->any())
            ->method('getRepository')
            ->willReturnMap(
                [
                    ['AppBundle:User', $this->usersMock],
                    ['AppBundle:Issue', $this->issuesMock],
                    ['AppBundle:IssueActivity', $this->issueActivitiesMock]
                ]
            );
        $translatorMock      = $this
            ->getMockBuilder('Symfony\Component\Translation\TranslatorInterface')
            ->getMock();
        $this->paginatorMock = $this
            ->getMockBuilder('Knp\Component\Pager\PaginatorInterface')
            ->getMock();
        $this->object        = new UserService($this->emMock, $translatorMock, $this->paginatorMock);
    }

    /**
     * @covers AppBundle\Service\UserService::getUsersList
     */
    public function testGetUsersList()
    {
        $page  = 5;
        $limit = 5;
        $users = [new User()];
        $this->usersMock
            ->expects($this->once())
            ->method('getListQuery')
            ->willReturn($users);
        $this->paginatorMock
            ->expects($this->once())
            ->method('paginate')
            ->with($users, $page, $limit);
        $this->object->getUsersList($page, $limit);
    }

    /**
     * @covers AppBundle\Service\UserService::getUserIssues
     */
    public function testGetUserIssues()
    {
        $id = 100;
        $this->issuesMock
            ->expects($this->once())
            ->method('getNotClosedUserIssues')
            ->with($id);
        $this->object->getUserIssues($id);
    }

    /**
     * @covers AppBundle\Service\UserService::getUserAssignedIssues
     */
    public function testGetUserAssignedIssues()
    {
        $id = 101;
        $this->issuesMock
            ->expects($this->once())
            ->method('getNotClosedUserAssignedIssues')
            ->with($id);
        $this->object->getUserAssignedIssues($id);
    }

    /**
     * @covers AppBundle\Service\UserService::getUserActivities
     */
    public function testGetUserActivities()
    {
        $id = 102;
        $this->issueActivitiesMock
            ->expects($this->once())
            ->method('getUserActivities')
            ->with($id);
        $this->object->getUserActivities($id);
    }
}
