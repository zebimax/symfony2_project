<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\DBAL\IssuePriorityEnumType;
use AppBundle\DBAL\IssueTypeEnumType;
use AppBundle\Entity\Issue;
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
            'type' => IssueTypeEnumType::BUG,
            'reporter' => 'user_manager',
            'project' => 'test_project',
            'assignee' => 'user_operator'
        ];

        $bug = $this->createEntityInstance($bugParams);
        $manager->persist($bug);

        $storyParams = [
            'summary' => 'Test story summary',
            'description' => 'Test description of story',
            'type' => IssueTypeEnumType::STORY,
            'reporter' => 'user_manager',
            'priority' => IssuePriorityEnumType::MAJOR,
            'project' => 'test_project'
        ];

        $story = $this->createEntityInstance($storyParams);
        $manager->persist($story);
        $this->addReference('issue_story', $story);

        $subTaskParams = [
            'summary' => 'Test sub_task summary',
            'description' => 'Test description of sub_task',
            'type' => IssueTypeEnumType::SUB_TASK,
            'reporter' => 'user_manager',
            'priority' => IssuePriorityEnumType::MAJOR,
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
        /** @var User $reporter */
        $reporter = $this->getReference($params['reporter']);
        /** @var Project $project */
        $project = $this->getReference($params['project']);

        $issue = (new Issue())
            ->setSummary($params['summary'])
            ->setDescription($params['description'])
            ->setReporter($reporter)
            ->setProject($project);

        if (array_key_exists('assignee', $params)) {
            /** @var User $assignee */
            $assignee = $this->getReference($params['assignee']);
            $issue->setAssignee($assignee);
        }

        if (array_key_exists('resolution', $params)) {
            $issue->setResolution($params['resolution']);
        }

        if (array_key_exists('status', $params)) {
            $issue->setStatus($params['status']);
        }

        if (array_key_exists('type', $params)) {
            $issue->setType($params['type']);
        }

        if (array_key_exists('priority', $params)) {
            $issue->setPriority($params['priority']);
        }

        if (array_key_exists('parent', $params)) {
            /** @var Issue $parent */
            $parent = $this->getReference($params['parent']);
            $issue->setParent($parent);
        }

        return $issue;
    }
}
