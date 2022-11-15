<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index_en')]
    public function en(): Response
    {
        return $this->render('index.html.twig', [
            'locale' => [
              'full' => 'en_US.UTF-8',
              'two_letter' => 'en',
              'json_ld' => 'en-US',
            ],
            'last_modified' => (new \DateTimeImmutable('now', new \DateTimeZone('Europe/Berlin')))->format('Y-m-d'),
        ]);
    }

    #[Route('/de', name: 'app_index_de')]
    public function de(): Response
    {
        return $this->render('index.html.twig', [
            'locale' => [
              'full' => 'de_DE.UTF-8',
              'two_letter' => 'de',
              'json_ld' => 'de-DE',
            ],
            'last_modified' => (new \DateTimeImmutable('now', new \DateTimeZone('Europe/Berlin')))->format('Y-m-d'),
        ]);
    }

    #[Route('/sitemap.xml', name: 'app_index_sitemap')]
    public function sitemap(): Response
    {
        return $this->render('sitemap.xml.twig', [
            'last_modified' => (new \DateTimeImmutable('now', new \DateTimeZone('Europe/Berlin')))->format('Y-m-d'),
        ]);
    }
}
