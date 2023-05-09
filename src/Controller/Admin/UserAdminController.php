<?php

namespace App\Controller\Admin;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/user'), IsGranted('ROLE_ADMIN')]
class UserAdminController extends BaseController
{
    #[Route(path: '/', name: 'admin_user_index')]
    public function index(): Response
    {
        return $this->render(
            'admin/user/index.html.twig'
        );
    }
}
