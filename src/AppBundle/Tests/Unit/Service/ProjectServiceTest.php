<?php

namespace AppBundle\Tests\Unit\Service;

use AppBundle\Entity\Project;
use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Service\ProjectService;

class ProjectServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProjectService
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
    protected $projectsMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $issueActivitiesMock;

    protected function setUp()
    {
        $this->projectsMock        = $this
            ->getMockBuilder('AppBundle\Entity\Repository\Projects')
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
                    ['AppBundle:Project', $this->projectsMock],
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
        $this->object        = new ProjectService($this->emMock, $this->paginatorMock, $translatorMock);
    }

    /**
     * @covers       AppBundle\Service\ProjectService::getProjectsList
     * @dataProvider getProjectsListDataProvider
     */
    public function testGetProjectsList(User $user, $method)
    {
        $page                   = 1;
        $limit                  = 100;
        $projects               = [new Project()];
        $issuesInvocationMocker = $this->projectsMock
            ->expects($this->once())
            ->method($method);
        $issuesInvocationMocker->willReturn($projects);

        $this->paginatorMock
            ->expects($this->once())
            ->method('paginate')
            ->with($projects, $page, $limit);
        $this->object->getProjectsList($user, $page, $limit);
    }

    /**
     * @covers AppBundle\Service\ProjectService::getAllProjectsQuery
     */
    public function testGetAllProjectsQuery()
    {
        $this->projectsMock
            ->expects($this->once())
            ->method('getAllProjectsQuery');
        $this->object->getAllProjectsQuery();
    }

    /**
     * @covers AppBundle\Service\ProjectService::getProjectIssues
     */
    public function testGetProjectIssues()
    {
        $this->issuesMock
            ->expects($this->once())
            ->method('getProjectIssues');
        $this->object->getProjectIssues(new Project());
    }

    /**
     * @covers AppBundle\Service\ProjectService::getProjectActivities
     */
    public function testGetProjectActivities()
    {
        $this->issueActivitiesMock
            ->expects($this->once())
            ->method('getProjectActivities');
        $this->object->getProjectActivities(new Project());
    }

    /**
     * @covers AppBundle\Service\ProjectService::getMembers
     */
    public function testGetMembers()
    {
        $page  = 10;
        $limit = 100;

        $project = new Project();
        foreach (range(0, 100) as $id) {
            $project->getUsers()->set($id, new User());
        }

        $this->paginatorMock
            ->expects($this->once())
            ->method('paginate')
            ->with($project->getUsers(), $page, $limit);
        $this->object->getMembers($project, $page, $limit);
    }

    /**
     * @covers AppBundle\Service\ProjectService::removeMember
     */
    public function testRemoveMember()
    {
        $user    = new User();
        $project = new Project();
        $project->getUsers()->set(1, $user);
        $this->emMock
            ->expects($this->once())
            ->method('flush');
        $this->object->removeMember($project, $user);
        $this->assertFalse($project->getUsers()->contains($user));
    }

    /**
     * @return array
     */
    public function getProjectsListDataProvider()
    {
        $adminRole    = (new Role())->setRole(Role::ADMINISTRATOR);
        $managerRole  = (new Role())->setRole(Role::MANAGER);
        $operatorRole = (new Role())->setRole(Role::OPERATOR);

        return [
            Role::ADMINISTRATOR => [
                'user' => (new User())->addRole($adminRole),
                'getAllProjectsQuery'
            ],
            Role::MANAGER       => [
                'user' => (new User())->addRole($managerRole),
                'getAllProjectsQuery'
            ],
            Role::OPERATOR      => [
                'user' => (new User())->addRole($operatorRole),
                'getUserProjectsQuery',
            ],
        ];
    }
}
