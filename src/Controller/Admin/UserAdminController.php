<?php

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/user'), IsGranted('ROLE_ADMIN')]
class UserAdminController extends BaseController
{
    #[Route(path: '/', name: 'admin_user_index', methods: ['GET'])]
    public function index(
        Request $request,
        UserRepository $userRepository
    ): Response {
        $dto = self::hydratePagerDto($request);
        $users = $userRepository->findAllPaginated($dto);
        return $this->render(
            'admin/user/index.html.twig',
            [
                'users' => $users
            ]
        );
    }
}
