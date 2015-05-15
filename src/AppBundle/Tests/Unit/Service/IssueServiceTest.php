<?php

namespace AppBundle\Tests\Unit\Service;

use AppBundle\Entity\Issue;
use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Service\IssueService;

class IssueServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IssueService
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
    protected $issueActivitiesMock;

    protected function setUp()
    {
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
        $this->object        = new IssueService($this->emMock, $this->paginatorMock, $translatorMock);
    }

    /**
     * @covers       AppBundle\Service\IssueService::getIssuesList
     * @dataProvider getIssuesListDataProvider
     *
     * @param User $user
     * @param      $method
     */
    public function testGetIssuesList(User $user, $method)
    {
        $page                   = 1;
        $limit                  = 100;
        $issues                 = [new Issue()];
        $issuesInvocationMocker = $this->issuesMock
            ->expects($this->once())
            ->method($method);
        $issuesInvocationMocker->willReturn($issues);

        $this->paginatorMock
            ->expects($this->once())
            ->method('paginate')
            ->with($issues, $page, $limit);
        $this->object->getIssuesList($user, $page, $limit);
    }

    /**
     * @covers AppBundle\Service\IssueService::getIssueActivities
     */
    public function testGetIssueActivities()
    {
        $this->issueActivitiesMock
            ->expects($this->once())
            ->method('getIssueActivities');
        $this->object->getIssueActivities(new Issue());
    }

    /**
     * @return array
     */
    public function getIssuesListDataProvider()
    {
        $adminRole    = (new Role())->setRole(Role::ADMINISTRATOR);
        $managerRole  = (new Role())->setRole(Role::MANAGER);
        $operatorRole = (new Role())->setRole(Role::OPERATOR);

        return [
            Role::ADMINISTRATOR => [
                'user' => (new User())->addRole($adminRole),
                'findAll'
            ],
            Role::MANAGER       => [
                'user' => (new User())->addRole($managerRole),
                'findAll'
            ],
            Role::OPERATOR      => [
                'user' => (new User())->addRole($operatorRole),
                'getUserProjectsIssues',
            ],
        ];
    }
}
