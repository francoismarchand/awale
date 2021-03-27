<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PartieController extends AbstractController
{
    /**
     * @Route("/partie/creer", name="partie")
     */
    public function creer(): Response
    {
        //TODO
        return $this->render('partie/creer.html.twig');
    }

    /**
     * @Route("/partie/{uuid}", name="partie")
     */
    public function partie(string $uuid): Response
    {
        $partie;//TODO load partie

        return $this->render('partie.html.twig', [
            'partie' => $partie
        ]);
    }
}
