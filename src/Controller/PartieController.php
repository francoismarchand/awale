<?php
namespace App\Controller;

use App\Factory\PartieFactory;
use App\Repository\PartieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class PartieController extends AbstractController
{
    /**
     * @Route("/partie/{uuid}", name="partie")
     */
    public function partie(string $uuid, PartieRepository $partieRepository, SerializerInterface $serializer): Response
    {
        $partie = $partieRepository->findOneBy(['uuid' => $uuid]);
        if (null === $partie) {
            $this->createNotFoundException('Partie non trouvée');
        }

        return $this->render('partie.html.twig', [
            'partie' => $partie,
            'joueur' => 0, //TODO voir comment on détermine le joueur courant
            'url' => 'ws://127.0.0.1:8081',//TODO paramètre
        ]);
    }

    /**
     * @Route("/partie/creer", name="create_partie", priority=10)
     */
    public function creer(PartieFactory $partieFactory, EntityManagerInterface $entityManager): Response
    {
        //TODO récupérer le use courant (même guest)
        //TODO choisi aléatoirement le joueur qui commence
	    $partie = $partieFactory->create();
        $entityManager->persist($partie);
        $entityManager->flush();

        return $this->redirectToRoute('partie', ['uuid' => $partie->getUuid()]);
    }
}
