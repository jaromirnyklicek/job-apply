<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class VueAppController extends AbstractController
{
    #[Route('/', name: 'vue_app', methods: ['GET'])]
    #[Route('/job/{vueRouting}', name: 'vue_app_vue', requirements: ['vueRouting' => '.+'], methods: ['GET'])]
    public function __invoke(): Response
    {
        return $this->render('frontend/vueapp.html.twig');
    }
}
