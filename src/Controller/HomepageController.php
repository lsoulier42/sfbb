<?php

namespace App\Controller;

use App\Contract\Service\BreadcrumbServiceInterface;
use App\Contract\Service\CategoryServiceInterface;
use App\Contract\Service\ForumServiceInterface;
use App\Contract\Service\HomepageServiceInterface;
use App\Dto\Homepage\BreadcrumbElement;
use App\Dto\User\UserLoginDto;
use App\Entity\Forum;
use App\Entity\Topic;
use App\Form\User\UserLoginType;
use App\Repository\ForumRepository;
use App\Repository\PostRepository;
use App\Repository\TopicRepository;
use App\Service\BreadcrumbService;
use Doctrine\Common\Collections\ArrayCollection;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends BaseController
{
    #[Route(path: '/', name: 'homepage')]
    public function index(
        CategoryServiceInterface $categoryService,
        HomepageServiceInterface $homepageService
    ): Response {
        $dto = new UserLoginDto();
        $form = $this->createForm(
            UserLoginType::class,
            $dto,
            [
                'action' => $this->generateUrl('user_login')
            ]
        );
        $categoriesVms = $categoryService->getCategoryViewModels();
        $whoIsOnlineVm = $homepageService->getWhoIsOnlineVm();
        return $this->render(
            'homepage/index.html.twig',
            [
                'categories' => $categoriesVms,
                'form' => $form->createView(),
                'who_is_online_vm' => $whoIsOnlineVm
            ]
        );
    }

    public function breadcrumb(
        Request $request,
        BreadcrumbServiceInterface $breadcrumbService
    ): Response {
        return $this->render(
            'shared/layout/_breadcrumb.html.twig',
            ['breadcrumb_list' => $breadcrumbService->generateBreadcrumb($request)]
        );
    }
}
