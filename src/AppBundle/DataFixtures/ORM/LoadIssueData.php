<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Issue;
use AppBundle\Entity\IssuePriority;
use AppBundle\Entity\IssueResolution;
use AppBundle\Entity\IssueStatus;
use AppBundle\Entity\IssueType;
use AppBundle\Entity\Project;
use AppBundle\Entity\Role;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadIssueData extends AbstractOrderedContainerAwareFixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $bugParams =  [
            'summary' => 'Test bug summary',
            'description' => 'Test description of bug',
            'status' => 'issue_status_open',
            'type' => 'issue_type_bug',
            'reporter' => 'user_manager',
            'priority' => 'issue_priority_trivial',
            'project' => 'test_project',
            'assignee' => 'user_operator'
        ];

        $bug = $this->createEntityInstance($bugParams);
        $manager->persist($bug);

        $storyParams = [
            'summary' => 'Test story summary',
            'description' => 'Test description of story',
            'status' => 'issue_status_open',
            'type' => 'issue_type_story',
            'reporter' => 'user_manager',
            'priority' => 'issue_priority_major',
            'project' => 'test_project'
        ];

        $story = $this->createEntityInstance($storyParams);
        $manager->persist($story);
        $this->addReference('issue_story', $story);

        $subTaskParams = [
            'summary' => 'Test sub_task summary',
            'description' => 'Test description of sub_task',
            'status' => 'issue_status_open',
            'type' => 'issue_type_sub_task',
            'reporter' => 'user_manager',
            'priority' => 'issue_priority_major',
            'project' => 'test_project',
            'parent' => 'issue_story'
        ];
        $subTask = $this->createEntityInstance($subTaskParams);
        $manager->persist($subTask);
        $this->addReference('issue_sub_task', $story);

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 14;
    }

    /**
     * @param $name
     * @return Role
     */
    protected function getRoleReference($name)
    {
        return $this->getReference($name);
    }

    /**
     * @param array $params
     * @return Issue
     */
    private function createEntityInstance(array $params = [])
    {
        /** @var IssueStatus $status */
        $status = $this->getReference($params['status']);
        /** @var IssueType $type */
        $type = $this->getReference($params['type']);
        /** @var User $reporter */
        $reporter = $this->getReference($params['reporter']);
        /** @var IssuePriority $priority */
        $priority = $this->getReference($params['priority']);
        /** @var Project $project */
        $project = $this->getReference($params['project']);

        $issue = (new Issue())
            ->setSummary($params['summary'])
            ->setDescription($params['description'])
            ->setStatus($status)
            ->setType($type)
            ->setReporter($reporter)
            ->setPriority($priority)
            ->setProject($project);

        if (array_key_exists('assignee', $params)) {
            /** @var User $assignee */
            $assignee = $this->getReference($params['assignee']);
            $issue->setAssignee($assignee);
        }

        if (array_key_exists('resolution', $params)) {
            /** @var IssueResolution $resolution */
            $resolution = $this->getReference($params['resolution']);
            $issue->setResolution($resolution);
        }

        if (array_key_exists('parent', $params)) {
            /** @var Issue $parent */
            $parent = $this->getReference($params['parent']);
            $issue->setParent($parent);
        }

        return $issue;
    }
}
