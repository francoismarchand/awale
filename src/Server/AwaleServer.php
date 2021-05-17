<?php
namespace App\Server;

use App\Entity\Game;
use App\Game\AwaleGame;
use App\Server\AwaleDto;
use App\Repository\GameRepository;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Doctrine\ORM\EntityManagerInterface;

class AwaleServer implements MessageComponentInterface
{
    private $gameRepository;
    private $awaleGame;
    private $entityManager;
    private $connexions = [];

    public function __construct(GameRepository $gameRepository, AwaleGame $awaleGame, EntityManagerInterface $entityManager)
    {
        $this->gameRepository = $gameRepository;
        $this->awaleGame = $awaleGame;
        $this->entityManager = $entityManager;
    }

    public function onOpen(ConnectionInterface $connexion)
    {
        $params = \explode('/', $connexion->httpRequest->getUri()->getPath());
        $gameUuid = $params[1];
        $playerId = $params[2];

        //on load la partie
        $game = $this->gameRepository->findOneBy(['uuid' => $gameUuid]);
        if (null === $game) {
            echo \sprintf("ERREUR - Partie '%s' non trouvée.\n", $gameUuid);
        }

		$this->connexions[$gameUuid][$playerId][$connexion->resourceId] = $connexion;

        echo \sprintf("Nouvelle connexion - PARTIE :%s, joueur, %d\n", $gameUuid, $playerId);

        $this->sendGamePlayers($game);

        $this->entityManager->clear();
        //TODO on signale à l'autre joueur que l'adversaire est connecté
    }

    public function onMessage(ConnectionInterface $from, $message)
    {
        $awaleDto = $this->hydrateSocketMessage($message);

        $game = $this->gameRepository->findOneBy(['uuid' => $awaleDto->game]);
        if (null === $game) {
            echo \sprintf("ERREUR - Partie '%s' non trouvée.\n", $awaleDto->game);

            return;
        }

        echo \sprintf("ACTION - '%s'\n", $awaleDto->action);

        switch ($awaleDto->action) {
            case AwaleDto::ACTION_PLAY:
                //TODO vérifier que les joueurs correspondent bien à la partie

                if ($awaleDto->player != $game->getCurrentPlayer()) {
                    echo \sprintf("Joueur differant (%s) (%s)\n", $awaleDto->player, $game->getCurrentPlayer());
                    break;
                }

                $game = $this->awaleGame->play($game, $awaleDto->player, $awaleDto->case);
                
                break;

            case AwaleDto::ACTION_READY:
                //TODO détermine aléatoirement le joueur qui commence
                break;
        }

        $this->sendGamePlayers($game);

        $this->entityManager->persist($game);
        $this->entityManager->flush();
        $this->entityManager->clear();
    }

    public function onClose(ConnectionInterface $connexion)
    {
       
    }

    public function onError(ConnectionInterface $connexion, \Exception $e)
    {

    }

    private function sendGamePlayers(Game $game): void
    {
        foreach ($this->connexions[$game->getUuid()] as $joueurId => $userConnexion) {
            foreach ($userConnexion as $userConnexion) {
                echo "SEND \n";
                $userConnexion->send(\json_encode($game));
            }
        }

        var_dump(\json_encode($game));
    }

    private function hydrateSocketMessage(string $message): AwaleDto
    {
        $message = \json_decode($message);

        $awaleDto = new AwaleDto();//TODO use serializer
        $awaleDto->game = $message->game ?? '';
        $awaleDto->player = $message->player ?? '';
        $awaleDto->case = $message->case ?? '';
        $awaleDto->status = $message->status ?? '';
        $awaleDto->action = $message->action ?? '';

        return $awaleDto;
    }
}