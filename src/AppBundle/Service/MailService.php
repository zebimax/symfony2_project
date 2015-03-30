<?php

namespace AppBundle\Service;

use AppBundle\Entity\IssueActivity;
use AppBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MailService
{
    /** @var ContainerInterface */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param User $user
     * @param $password
     */
    public function sendCreateUserMail(User $user, $password)
    {
        $this->sendMessage(
            $this->container->getParameter('app.mail.default_email'),
            $user->getEmail(),
            [
                'subject' => $this->container->get('translator')->trans('app.mail.create_user.subject'),
                'template' => 'mail/create_user.html.twig',
                'params' => [
                    'user' => $user,
                    'password' => $password,
                ],
            ]
        );
    }

    /**
     * @param IssueActivity $activity
     */
    public function sendIssueActivityMail(IssueActivity $activity)
    {
        array_map(
            function (User $user) use ($activity) {
                $this->sendMessage(
                    $this->container->getParameter('app.mail.default_email'),
                    $user->getEmail(),
                    [
                        'subject' => $this->container->get('translator')->trans('app.mail.issue_activity.subject'),
                        'template' => 'mail/issue_activity.html.twig',
                        'params' => ['activity' => $activity],
                    ]
                );
            },
            $activity->getIssue()->getCollaborators()->toArray()
        );
    }

    /**
     * @param $from
     * @param $to
     * @param array $messageParams
     */
    protected function sendMessage($from, $to, array $messageParams)
    {
        $message = \Swift_Message::newInstance();
        $message
            ->setFrom($from)
            ->setTo($to)
            ->setSubject($messageParams['subject'])
            ->setBody(
                $this->container->get('templating')->render(
                    $messageParams['template'],
                    $messageParams['params']
                )
            )
            ->setContentType('text/html');
        $this->container->get('mailer')->send($message);
    }
}
