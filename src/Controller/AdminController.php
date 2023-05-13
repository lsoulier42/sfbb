<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin'), IsGranted('ROLE_ADMIN')]
class AdminController extends BaseController
{
    #[Route(path: '/', name: 'admin_index')]
    public function index(): Response
    {
        return $this->redirectToRoute('admin_configuration_index');
    }
}
