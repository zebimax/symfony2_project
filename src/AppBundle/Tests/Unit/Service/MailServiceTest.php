<?php

namespace AppBundle\Tests\Unit\Service;

use AppBundle\Entity\Issue;
use AppBundle\Entity\IssueActivity;
use AppBundle\Entity\User;
use AppBundle\Service\MailService;

class MailServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MailService
     */
    protected $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $mailerMock;

    protected function setUp()
    {
        $translatorMock = $this
            ->getMockBuilder('Symfony\Component\Translation\TranslatorInterface')
            ->getMock();
        $translatorMock
            ->expects($this->any())
            ->method('trans')
            ->willReturn(null);
        $this->mailerMock = $this
            ->getMockBuilder('Swift_Mailer')
            ->disableOriginalConstructor()
            ->getMock();
        $engineMock       = $this
            ->getMockBuilder('Symfony\Bundle\TwigBundle\TwigEngine')
            ->disableOriginalConstructor()
            ->getMock();

        $this->object = new MailService($translatorMock, $this->mailerMock, $engineMock, 'email@email.com');
    }

    /**
     * @covers AppBundle\Service\MailService::sendCreateUserMail
     */
    public function testSendCreateUserMail()
    {
        $this->mailerMock
            ->expects($this->once())
            ->method('send')
            ->withConsecutive([new \PHPUnit_Framework_Constraint_IsInstanceOf('Swift_Message')]);
        $this->object->sendCreateUserMail(new User(), 'pass');
    }

    /**
     * @covers AppBundle\Service\MailService::sendIssueActivityMail
     */
    public function testSendIssueActivityMail()
    {
        $issue = new Issue();
        $users = [new User(), new User(), new User(), new User()];
        foreach ($users as $i => $user) {
            $issue->getCollaborators()->set($i, $user);
            $this->mailerMock
                ->expects($this->at($i))
                ->method('send')
                ->withConsecutive([new \PHPUnit_Framework_Constraint_IsInstanceOf('Swift_Message')]);
        }

        $activity = new IssueActivity($issue, new User());
        $this->object->sendIssueActivityMail($activity);
    }
}
