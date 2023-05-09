<?php

namespace App\Controller\Admin;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/access'), IsGranted('ROLE_ADMIN')]
class AccessAdminController extends BaseController
{
    #[Route(path: '/', name: 'admin_access_index')]
    public function index(): Response
    {
        return $this->render(
            'admin/access/index.html.twig'
        );
    }
}
