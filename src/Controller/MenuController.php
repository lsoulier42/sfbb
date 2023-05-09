<?php

namespace App\Controller;

use App\Contract\Service\ConfigurationServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class MenuController extends BaseController
{
    public function renderMenu(
        ConfigurationServiceInterface $configurationService
    ): Response {
        return $this->render(
            'shared/layout/_menu.html.twig',
            [
                'forum_name' => $configurationService->getConfigValue('forum_name'),
                'forum_description' => $configurationService->getConfigValue('forum_description'),
                'user' => $this->getUser()
            ]
        );
    }
}
