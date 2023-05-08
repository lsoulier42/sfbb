<?php

namespace App\Controller;

use App\Contract\Service\UserServiceInterface;
use App\Dto\User\MemberFilterDto;
use App\Dto\User\UserLoginDto;
use App\Dto\User\UserRegisterDto;
use App\Entity\User;
use App\Form\User\MemberFilterType;
use App\Form\User\UserEditProfileType;
use App\Form\User\UserRegisterType;
use App\Form\User\UserLoginType;
use Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/user')]
class UserController extends BaseController
{
    #[Route(path: '/login', name: 'user_login')]
    public function login(): Response
    {
        $dto = new UserLoginDto();
        $form = $this->createForm(
            UserLoginType::class,
            $dto
        );
        return $this->render(
            'user/login.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    #[Route(path: '/logout', name: 'user_logout')]
    public function logout(): void
    {
    }

    #[Route(path: '/register', name: 'user_register')]
    public function register(
        Request $request,
        UserServiceInterface $userService
    ): Response|RedirectResponse {
        $dto = new UserRegisterDto();
        $form = $this->createForm(
            UserRegisterType::class,
            $dto
        );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userService->createNewUser($dto);
            $this->addFlash(
                'success',
                'user.register.success'
            );
            return $this->redirectToRoute('homepage');
        }
        return $this->render(
            'user/register.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    #[Route(path: '/edit-profile', name: 'user_edit_profile')]
    public function editProfile(
        Request $request,
        #[CurrentUser] User $user,
        UserServiceInterface $userService
    ): Response|RedirectResponse {
        $dto = $userService->getUserEditProfileDtoFromUser($user);
        $form = $this->createForm(
            UserEditProfileType::class,
            $dto
        );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $userService->editUserProfile($dto, $user);
                $this->addFlash(
                    'success',
                    'user.edit_profile.success'
                );
            } catch (Exception $exception) {
                $this->addFlash(
                    'danger',
                    $exception->getMessage()
                );
            }
            return $this->redirectToRoute('user_edit_profile');
        }
        return $this->render(
            'user/edit-profile.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user
            ]
        );
    }

    #[Route(path: '/{user}/profile', name: 'user_profile')]
    public function profile(User $user): Response
    {
        return $this->render(
            'user/profile.html.twig',
            ['user' => $user]
        );
    }
    #[Route(path: '/members-list', name: 'user_members_list')]
    public function membersList(
        Request $request,
        UserServiceInterface $userService
    ): Response {
        $dto = new MemberFilterDto();
        self::hydrateFilterDto($request, $dto);
        $form = $this->createForm(MemberFilterType::class, $dto);
        $form->handleRequest($request);
        $members = $userService->findByFilterDtoPaginated($dto);
        return $this->render(
            'user/member_list.html.twig',
            [
                'members' => $members,
                'form' => $form->createView()
            ]
        );
    }
}
