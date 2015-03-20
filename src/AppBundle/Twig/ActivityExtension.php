<?php

namespace AppBundle\Twig;

use AppBundle\Entity\IssueActivity;

class ActivityExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_activity_extension';
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('getActivityMessage', [$this, 'getActivityMessage']),
        ];
    }

    /**
     * @param IssueActivity $activity
     * @return string
     */
    public function getActivityMessage(IssueActivity $activity)
    {
        $map =  [
            IssueActivity::CREATE_ISSUE => 'app.messages.activities.templates.create_issue',
            IssueActivity::CHANGE_ISSUE_STATUS => 'app.messages.activities.templates.change_issue_activity',
            IssueActivity::COMMENT_ISSUE => 'app.messages.activities.templates.comment_issue'
        ];
        $message = '';
        if (array_key_exists($activity->getType(), $map)) {
            $values = [
                '%user%' => $activity->getUser()->getUserName(),
                '%issue%' => $activity->getIssue()->getCode()
            ];
            $type = $activity->getType();
            if ($type === IssueActivity::CHANGE_ISSUE_STATUS) {
                $values['%issue_status%'] = $this->translator->trans($activity->getIssue()->getStatus()->getCode());
            }
            $message = $this->translator->trans($map[$type], $values);
        }
        return $message;
    }
}
