<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\WebpackEncoreBundle\Asset\EntrypointLookupCollectionInterface;

class IndexController extends AbstractController
{
    public function __construct(private readonly EntrypointLookupCollectionInterface $entrypointLookupCollection)
    {
    }

    #[\Symfony\Component\Routing\Attribute\Route('/', name: 'app_index_en')]
    public function en(): Response
    {
        $result = $this->render('index.html.twig', [
            'locale' => [
                'full' => 'en_US.UTF-8',
                'two_letter' => 'en',
                'json_ld' => 'en-US',
            ],
            'last_modified' => (new \DateTimeImmutable('now', new \DateTimeZone('Europe/Berlin')))->format('Y-m-d'),
        ]);

        $this->entrypointLookupCollection->getEntrypointLookup()->reset();

        return $result;
    }

    #[\Symfony\Component\Routing\Attribute\Route('/sitemap.xml', name: 'app_index_sitemap')]
    public function sitemap(): Response
    {
        $result = $this->render('sitemap.xml.twig', [
            'last_modified' => (new \DateTimeImmutable('now', new \DateTimeZone('Europe/Berlin')))->format('Y-m-d'),
        ]);

        $this->entrypointLookupCollection->getEntrypointLookup()->reset();

        return $result;
    }
}
