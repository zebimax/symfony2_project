<?php

namespace AppBundle\Tests\Unit\Service;

use AppBundle\DBAL\IssueStatusEnumType;
use AppBundle\Entity\Issue;
use AppBundle\Entity\IssueActivity;
use AppBundle\Entity\User;
use AppBundle\EventListener\Event\IssueActivityEvent;
use AppBundle\Service\IssueFormService;
use Symfony\Component\Form\FormEvents;

class IssueFormServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IssueFormService
     */
    protected $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $formFactoryMock;

    protected function setUp()
    {
        $emMock         = $this
            ->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();
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

        $this->object = new IssueFormService($emMock, $translatorMock, $this->formFactoryMock);
    }

    /**
     * @covers AppBundle\Service\IssueFormService::getIssueForm
     */
    public function testGetIssueForm()
    {
        $isCallableConstraint = new \PHPUnit_Framework_Constraint_IsType(
            \PHPUnit_Framework_Constraint_IsType::TYPE_CALLABLE
        );

        $issue = new Issue();
        $user  = new User();

        $projectBuilderMock = $this
            ->getMockBuilder('Symfony\Component\Form\FormBuilderInterface')
            ->getMock();
        $projectBuilderMock->expects($this->once())
            ->method('addEventListener')
            ->withConsecutive(
                [
                    FormEvents::POST_SUBMIT,
                    $isCallableConstraint
                ]
            );

        $builderMock = $this
            ->getMockBuilder('Symfony\Component\Form\FormBuilderInterface')
            ->getMock();
        $builderMock
            ->expects($this->at(0))
            ->method('add')
            ->with(
                'project',
                'entity',
                [
                    'class'       => 'AppBundle:Project',
                    'property'    => 'label',
                    'label'       => null,
                    'choices'     => $user->getProjects(),
                    'required'    => true,
                    'placeholder' => null,
                    'empty_data'  => null,
                    'attr'        => ['class' => 'form-control']
                ]
            );
        $builderMock
            ->expects($this->at(1))
            ->method('get')
            ->with('project')
            ->willReturn($projectBuilderMock);
        $builderMock
            ->expects($this->at(2))
            ->method('addEventListener')
            ->withConsecutive(
                [
                    FormEvents::PRE_SET_DATA,
                    $isCallableConstraint
                ]
            );
        $builderMock
            ->expects($this->at(3))
            ->method('addEventListener')
            ->withConsecutive(
                [
                    FormEvents::SUBMIT,
                    $isCallableConstraint
                ]
            );
        $builderMock
            ->expects($this->at(4))
            ->method('addEventListener')
            ->withConsecutive(
                [
                    FormEvents::SUBMIT,
                    $isCallableConstraint
                ]
            );
        $builderMock
            ->expects($this->at(5))
            ->method('getForm');

        $this->formFactoryMock
            ->expects($this->once())
            ->method('createBuilder')
            ->with('app_issue', $issue)
            ->willReturn($builderMock);

        $this->object->getIssueForm($issue, $user);
    }

    /**
     * @covers AppBundle\Service\IssueFormService::addIssue
     */
    public function testAddIssue()
    {
        $dispatcherMock = $this
            ->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
            ->getMock();
        $dispatcherMock
            ->expects($this->once())
            ->method('dispatch')
            ->withConsecutive(
                [
                    IssueActivityEvent::ISSUE_ACTIVITY,
                    new \PHPUnit_Framework_Constraint_IsInstanceOf(
                        'AppBundle\EventListener\Event\IssueActivityEvent'
                    )
                ]
            );
        $this->object->setEventDispatcher($dispatcherMock);
        $issue = new Issue();
        $user  = new User();
        $this->object->addIssue($issue, $user);

        /** @var IssueActivity $activity */
        $activity = $issue->getActivities()->current();

        $this->assertTrue($issue->getCollaborators()->contains($user));
        $this->assertTrue($activity->getType() === IssueActivity::CREATE_ISSUE);
        $this->assertSame($issue->getCreated(), $activity->getCreated());
        $this->assertSame($user, $issue->getReporter());
        $this->assertEquals(IssueStatusEnumType::OPEN, $issue->getStatus());
    }
}
