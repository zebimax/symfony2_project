<?php

namespace AppBundle\Tests\Unit\Twig;

use AppBundle\DBAL\IssueStatusEnumType;
use AppBundle\Entity\Issue;
use AppBundle\Entity\IssueActivity;
use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use AppBundle\Twig\ActivityExtension;

class ActivityExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ActivityExtension
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
        $this->object         = new ActivityExtension($this->translatorMock);
    }

    /**
     * @covers AppBundle\Twig\ActivityExtension::getName
     */
    public function testGetName()
    {
        $this->assertEquals('app_activity_extension', $this->object->getName());
    }

    /**
     * @covers AppBundle\Twig\ActivityExtension::getFilters
     */
    public function testGetFilters()
    {
        $this->assertEquals(
            [new \Twig_SimpleFilter('getActivityMessage', [$this->object, 'getActivityMessage'])],
            $this->object->getFilters()
        );
    }

    /**
     * @covers       AppBundle\Twig\ActivityExtension::getActivityMessage
     * @dataProvider getActivityMessageDataProvider
     * @param IssueActivity $activity
     * @param               $message
     * @param array         $values
     * @param bool|null     $willTranslateStatus
     * @param bool          $willTranslate
     */
    public function testGetActivityMessage(
        IssueActivity $activity,
        $message,
        array $values,
        $willTranslateStatus = null,
        $willTranslate = true
    ) {
        $i = 0;
        if ($willTranslate) {
            if ($willTranslateStatus) {
                $this->translatorMock
                    ->expects($this->at($i))
                    ->method('trans')
                    ->willReturnArgument(0);
                $i++;
            }
            $this->translatorMock
                ->expects($this->at($i))
                ->method('trans')
                ->with($message, $values)
                ->willReturnArgument(0);
        }
        $this->assertEquals($message, $this->object->getActivityMessage($activity));
    }

    public function getActivityMessageDataProvider()
    {
        $username = 'activity_user';
        $user     = (new User())->setUsername($username);
        $issue    = (new Issue())->setProject(new Project());

        return [
            IssueActivity::CREATE_ISSUE                                                        => [
                'activity' => (new IssueActivity($issue, $user))->setType(IssueActivity::CREATE_ISSUE),
                'message'  => 'app.messages.activities.templates.create_issue',
                'values'   => ['%user%' => $username, '%issue%' => '-']
            ],
            IssueActivity::COMMENT_ISSUE                                                       => [
                'activity' => (new IssueActivity($issue, $user))->setType(IssueActivity::COMMENT_ISSUE),
                'message'  => 'app.messages.activities.templates.comment_issue',
                'values'   => ['%user%' => $username, '%issue%' => '-']
            ],
            IssueActivity::CHANGE_ISSUE_STATUS . '_status_' . IssueStatusEnumType::OPEN        => [
                'activity'            => (new IssueActivity($issue, $user))
                    ->setType(IssueActivity::CHANGE_ISSUE_STATUS)
                    ->setDetails(['new' => ['status' => IssueStatusEnumType::OPEN]]),
                'message'             => 'app.messages.activities.templates.change_issue_activity',
                'values'              => ['%user%' => $username, '%issue%' => '-', '%status%' => '"app.issue.statuses.open"'],
                'willTranslateStatus' => true
            ],
            IssueActivity::CHANGE_ISSUE_STATUS . '_status_' . IssueStatusEnumType::IN_PROGRESS => [
                'activity'            => (new IssueActivity($issue, $user))
                    ->setType(IssueActivity::CHANGE_ISSUE_STATUS)
                    ->setDetails(['new' => ['status' => IssueStatusEnumType::IN_PROGRESS]]),
                'message'             => 'app.messages.activities.templates.change_issue_activity',
                'values'              => [
                    '%user%' => $username, '%issue%' => '-', '%status%' => '"app.issue.statuses.in_progress"'
                ],
                'willTranslateStatus' => true
            ],
            IssueActivity::CHANGE_ISSUE_STATUS . '_status_' . IssueStatusEnumType::CLOSED      => [
                'activity'            => (new IssueActivity($issue, $user))
                    ->setType(IssueActivity::CHANGE_ISSUE_STATUS)
                    ->setDetails(['new' => ['status' => IssueStatusEnumType::CLOSED]]),
                'message'             => 'app.messages.activities.templates.change_issue_activity',
                'values'              => ['%user%' => $username, '%issue%' => '-', '%status%' => '"app.issue.statuses.closed"'],
                'willTranslateStatus' => true
            ],
            'status_undefined'                                                                 => [
                'activity'            => (new IssueActivity($issue, $user))
                    ->setType(IssueActivity::CHANGE_ISSUE_STATUS)
                    ->setDetails(['new' => ['status' => 'status_undefined']]),
                'message'             => 'app.messages.activities.templates.change_issue_activity',
                'values'              => ['%user%' => $username, '%issue%' => '-', '%status%' => '"app.statuses.undefined"'],
                'willTranslateStatus' => true
            ],
            'incorrect_type'                                                                   => [
                'activity'            => (new IssueActivity($issue, $user))->setType('incorrect_type'),
                'message'             => '',
                'values'              => ['%user%' => $username, '%issue%' => '-'],
                'willTranslateStatus' => null,
                'willTranslate'       => false
            ],
        ];
    }
}
