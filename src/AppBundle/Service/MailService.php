<?php

namespace AppBundle\Service;

use AppBundle\Entity\IssueActivity;
use AppBundle\Entity\User;

use Swift_Mailer;

use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Translation\TranslatorInterface;

class MailService
{
    /** @var TranslatorInterface */
    protected $translator;

    /** @var TwigEngine */
    protected $templating;

    /** @var Swift_Mailer */
    protected $mailer;

    /** @var string */
    protected $fromEmail;

    /**
     * @param TranslatorInterface $translator
     * @param Swift_Mailer        $mailer
     * @param TwigEngine          $engine
     * @param string              $from
     */
    public function __construct(TranslatorInterface $translator, Swift_Mailer $mailer, TwigEngine $engine, $from)
    {
        $this->translator = $translator;
        $this->mailer = $mailer;
        $this->templating = $engine;
        $this->fromEmail = $from;
    }

    /**
     * @param User $user
     * @param $password
     */
    public function sendCreateUserMail(User $user, $password)
    {
        $this->sendMessage(
            $this->fromEmail,
            $user->getEmail(),
            [
                'subject' => $this->translator->trans('app.mail.create_user.subject'),
                'template' => '@App/Mail/create_user.html.twig',
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
                    $this->fromEmail,
                    $user->getEmail(),
                    [
                        'subject' => $this->translator->trans('app.mail.issue_activity.subject'),
                        'template' => '@App/Mail/issue_activity.html.twig',
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
                $this->templating->render(
                    $messageParams['template'],
                    $messageParams['params']
                )
            )
            ->setContentType('text/html');
        $this->mailer->send($message);
    }
}
