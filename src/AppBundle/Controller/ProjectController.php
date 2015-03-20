<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProjectController extends Controller
{
    /**
     * @Route("/project/add", name="app_project_add")
     */
    public function addAction()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/project/list", name="app_project_list")
     */
    public function listAction()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/project/view/{id}", name="app_project_view")
     * @Template("project/view.html.twig")
     * @param Project $project
     * @return array
     */
    public function viewAction(Project $project)
    {
        return ['project' => $project];
    }
}
