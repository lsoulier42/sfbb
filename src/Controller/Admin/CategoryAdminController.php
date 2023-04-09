<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/category')]
class CategoryAdminController extends AbstractController
{
    public function index(Request $request): Response
    {

    }

    public function new(Request $request): Response
    {

    }
}