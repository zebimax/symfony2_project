<?php

namespace AppBundle\Service;

use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use AppBundle\Service\Form\AbstractFormService;
use Symfony\Component\Form\FormInterface;

class ProjectFormsService extends AbstractFormService
{
    /**
     * @param Project $project
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getMembersForm(Project $project)
    {
        return $this->factory->create(
            'app_project_member',
            null,
            ['data' => array_reduce(
                $this->getUsersRepository()->getNotProjectUsers($project->getId()),
                function ($carry, $item) {
                    $carry[$item['id']] = $item['username'];

                    return $carry;
                },
                []
            )]
        );
    }

    /**
     * @param Project $project
     *
     * @return FormInterface
     */
    public function getProjectForm(Project $project)
    {
        return $this->factory->create('app_project', $project);
    }

    /**
     * @param Project       $project
     * @param FormInterface $form
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function addMember(Project $project, FormInterface $form)
    {
        /* @var User $user */
        $id = $form->get('users')->getData();
        $user = $this->getUsersRepository()->find($id);
        $project->addUser($user);
        $this->manager->persist($project);
        $this->manager->flush();
    }

    /**
     * @param Project $project
     */
    public function saveProject(Project $project)
    {
        $this->manager->persist($project);
        $this->manager->flush();
    }
}
