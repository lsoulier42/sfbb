<?php

namespace App\Controller\Admin;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/moderator'), IsGranted('ROLE_ADMIN')]
class ModeratorAdminController extends BaseController
{
    #[Route(path: '/', name: 'admin_moderator_index')]
    public function index(): Response
    {
        return $this->render(
            'admin/moderator/index.html.twig'
        );
    }
}
