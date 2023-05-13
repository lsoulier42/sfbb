<?php

namespace App\Controller\Admin;

use App\Contract\Service\ConfigurationServiceInterface;
use App\Controller\BaseController;
use App\Entity\Configuration;
use App\Form\Admin\ConfigurationCollectionType;
use App\Form\Admin\ConfigurationType;
use App\Repository\ConfigurationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/configuration'), IsGranted('ROLE_ADMIN')]
class ConfigurationController extends BaseController
{
    #[Route(path: '/', name: 'admin_configuration_index')]
    public function index(
        Request $request,
        ConfigurationServiceInterface $configurationService
    ): Response {
        $dto = $configurationService->getConfigurationCollection();
        $ids = array();
        foreach ($dto->getConfigurations() as $configuration) {
            $ids[$configuration->getConfigKey()] = $configuration->getId();
        }
        $form = $this->createForm(ConfigurationCollectionType::class, $dto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $configurationService->computeConfigurationCollection($dto);
            $this->addSuccessMessage('configuration.label.edit_value_success');
        }
        return $this->render(
            'admin/configuration/index.html.twig',
            [
                'form' => $form->createView(),
                'ids' => $ids
            ]
        );
    }

    #[Route(path: '/new', name: 'admin_configuration_new')]
    public function new(
        Request $request,
        ConfigurationRepository $configurationRepository
    ): Response {
        $configuration = new Configuration();
        $form = $this->createForm(ConfigurationType::class, $configuration);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $configurationRepository->createOrUpdate($configuration);
            $this->addSuccessMessage('configuration.label.create_success');
        }
        return $this->render(
            'admin/configuration/new.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    #[Route(path: '/{configuration}/edit', name: 'admin_configuration_edit')]
    public function edit(
        Request $request,
        Configuration $configuration,
        ConfigurationRepository $configurationRepository
    ): Response {
        $form = $this->createForm(ConfigurationType::class, $configuration);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $configurationRepository->createOrUpdate($configuration);
            $this->addSuccessMessage('configuration.label.edit_success');
        }
        return $this->render(
            'admin/configuration/edit.html.twig',
            [
                'form' => $form->createView(),
                'configuration' => $configuration
            ]
        );
    }
}
