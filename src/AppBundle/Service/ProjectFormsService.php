<?php

namespace AppBundle\Service;

use AppBundle\Entity\Project;
use AppBundle\Service\Form\AbstractFormService;

class ProjectFormsService extends AbstractFormService
{
    public function getMembersForm(Project $project)
    {
        return $this->factory->create(
            'app_project_member'
        );
    }
}
