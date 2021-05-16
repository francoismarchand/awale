<?php
namespace App\Controller;

use App\Client\WsClient;
use App\Entity\Game;
use App\Entity\Player;
use App\Entity\User;
use App\Factory\GameFactory;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class GameController extends AbstractController
{
    /**
     * @Route("/partie/{uuid}", name="app_game")
     */
    public function partie(
        GameRepository $gameRepository, 
        EntityManagerInterface $entityManager,
        WsClient $wsClient,
        string $uuid
    ): Response {
        $game = $gameRepository->findOneBy(['uuid' => $uuid]);
        if (null === $game) {
            $this->createNotFoundException('Partie non trouvée');
        }

        $currentUser = $this->getUser();        
        if (null === $currentUser) {//TODO on pourrait faire la création du player ici pluttôt que dans Controllerlistener
            $this->createNotFoundException('Partie non trouvée');
        }

        if (!$this->playerIsInGame($game, $currentUser)) {
            if (\count($game->getPlayers()) > Game::MAX_PLAYERS) {
                throw new \Exception('Max players exceeded.');
            }

            //TODO PlayerFactory
            $player = (new Player)
                ->setuser($this->getUser())
                ->setGame($game)
                ->setScore(0)
            ;
            $entityManager->persist($player);
            $game->addPlayer($player);

            if (\count($game->getPlayers()) == Game::MAX_PLAYERS) {
                $game->setStatus(Game::STATUS_READY);
            }

            $entityManager->persist($game);
            $entityManager->flush();

            if (\count($game->getPlayers()) == Game::MAX_PLAYERS) {
                $wsClient->sendGameReady($game);
            }
        }

        return $this->render('game.html.twig', [
            'game' => $game,
            'player' => $currentUser,
            'url' => $this->getParameter('ws_awale_url')
        ]);
    }

    /**
     * @Route("/partie/creer", name="app_create_game", priority=10)
     */
    public function creer(GameFactory $gameFactory, EntityManagerInterface $entityManager): Response
    {
	    $game = $gameFactory->create();
        $entityManager->persist($game);
        $entityManager->flush();

        return $this->redirectToRoute('app_game', ['uuid' => $game->getUuid()]);
    }

    private function playerIsInGame(Game $game, User $user): bool
    {
        $players = $game->getPlayers()->filter(
            function($player) use ($user) {
                return $player->getUser() == $user; 
            }
        );

        return \count($players) > 0;
    }
}
