<?php
namespace App\Server;

use App\Game\AwaleGame;
use App\Server\AwaleDto;
use App\Repository\PartieRepository;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class AwaleServer implements MessageComponentInterface
{
    private $partieRepository;
    private $awaleGame;
    private $parties = [];
    private $connexions = [];

    public function __construct(PartieRepository $partieRepository, AwaleGame $awaleGame)
    {
        $this->partieRepository = $partieRepository;
        $this->awaleGame = $awaleGame;
    }

    public function onOpen(ConnectionInterface $connexion)
    {
        $params = \explode('/', $connexion->httpRequest->getUri()->getPath());
        $partieId = $params[1];
        $joueurId = $params[2];

        //on load la partie
        if (!isset($parties[$partieId])) {
            $partie = $this->partieRepository->findOneBy(['uuid' => $partieId]);//TODO voir si on ne stock pas plus les parties dans redis
            //TODO gestion erreur si n'existe pas
            if (null === $partie) {
                echo \sprintf("ERREUR - Partie '%s' non trouvée.\n", $partieId);
            }

            $this->parties[$partieId] = $partie;
        }
		$this->connexions[$partieId][$joueurId][$connexion->resourceId] = $connexion;

        echo \sprintf("Nouvelle connexion - PARTIE :%s, joueur, %d\n", $partieId, $joueurId);
        //TODO, on envoie la partie courante
        //TODO on signale à l'autre joueur que l'adversaire est connecté
        //TODO on signale que la partie commence (si elle ne l'ai pas déjà, voir le status de la partie, qui doit être mis à jour ici aussi)
    }

    public function onMessage(ConnectionInterface $from, $message)
    {
        $awaleDto = $this->hydrateSocketMessage($message);
        $partie = $this->parties[$awaleDto->partie] ?? null;
        
        if (null === $partie) {
            echo \sprintf("ERREUR - Partie '%s' non trouvée.\n", $awaleDto->uuid);

            return;
        }

        $partie = $this->awaleGame->play($partie, $awaleDto->joueur, $awaleDto->case);
        $this->parties[$awaleDto->partie] = $partie;
        //TODO vérifier que les joueurs correspondent bien à la partie
        //Mise à jour de la partie (bien mettre à jour ans el tableau)
        
        //on change le joueur courant
        foreach ($this->connexions[$awaleDto->partie] as $joueurId => $userConnexion) {
            //if ($joueurId !== $socketMessage->joueur) {
                foreach ($userConnexion as $userConnexion) {
                    echo "SEND \n";
                    $userConnexion->send(\json_encode($partie));
                }
            //}
        }
    }

    public function onClose(ConnectionInterface $connexion)
    {
       
    }

    public function onError(ConnectionInterface $connexion, \Exception $e)
    {

    }

    private function hydrateSocketMessage(string $message): AwaleDto
    {
        $message = \json_decode($message);

        $awaleDto = new AwaleDto();
        $awaleDto->partie = $message->partie ?? '';
        $awaleDto->joueur = $message->player ?? '';
        $awaleDto->case = $message->case ?? '';

        return $awaleDto;
    }
}