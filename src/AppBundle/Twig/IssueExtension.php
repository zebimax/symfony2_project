<?php

namespace AppBundle\Twig;

class IssueExtension extends AbstractExtension
{
    const DEFAULT_MAX_DESCRIPTION = 100;

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_issue_extension';
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('renderIssueStatus', [$this, 'getStatus']),
            new \Twig_SimpleFilter('renderIssueType', [$this, 'getType']),
            new \Twig_SimpleFilter('renderIssuePriority', [$this, 'getPriority']),
            new \Twig_SimpleFilter('renderIssueResolution', [$this, 'getResolution']),
            new \Twig_SimpleFilter('renderShortIssueDescription', [$this, 'shortIssueDescription']),
        ];
    }

    /**
     * @param string $status
     *
     * @return string
     */
    public function getStatus($status)
    {
        return $this->translator->trans('app.issue.statuses.' . $status);
    }

    /**
     * @param string $type
     *
     * @return string
     */
    public function getType($type)
    {
        return $this->translator->trans('app.issue.types.' . $type);
    }

    /**
     * @param string $priority
     *
     * @return string
     */
    public function getPriority($priority)
    {
        return $this->translator->trans('app.issue.priorities.' . $priority);
    }

    /**
     * @param string $resolution
     *
     * @return string
     */
    public function getResolution($resolution)
    {
        return $this->translator->trans('app.issue.resolutions.' . $resolution);
    }

    /**
     * @param string $description
     * @param int $maxLen
     *
     * @return string
     */
    public function shortIssueDescription($description, $maxLen = self::DEFAULT_MAX_DESCRIPTION)
    {
        return substr($description, 0, $maxLen);
    }
}
