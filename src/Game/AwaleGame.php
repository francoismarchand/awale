<?php
namespace App\Game;

use App\Entity\Game;

class AwaleGame
{
    const NB_CASES = 12;

    public function play(Game $game, int $player, int $case): Game
    {
        //TODO vérifications
            //Biens les cases du joueurs courant
            //case non vide

        $game = $this->move($game, $player, $case);
        $game = $this->changeCurrentPlayer($game);
        $game = $this->calculateWinner($game);

        return $game;
    }

    //gestion du déplacement
    private function move(Game $game, int $player, int $case): Game
    {
        $handPosition = 0;
        $board = $game->getBoard();
        $nbStones = $board[$case];
        $nbLaps = \floor($nbStones / self::NB_CASES);

        $nbStones += $nbLaps;//On ajoute un déplacement pour sauter la case de départ
        $board[$case] = 0;

        for ($i = 1; $i <= $nbStones; $i++) {
            $handPosition = $case + $i;
           
            if ($handPosition >= self::NB_CASES) {//On boucle le tour
                $handPosition -= self::NB_CASES * ($nbLaps == 0 ? 1 : $nbLaps);
            } 

            if ($handPosition != $case) {
                $board[$handPosition] += 1;
            }        
        }

        $game->setBoard($board);

        $game = $this->eat($game, $player, $handPosition);

        return $game;
    }

    //Gestion des pions mangés
    private function eat(Game $game, int $currentPlayer, int $handPosition): Game
    {
        $board = $game->getBoard();

        while(
            ($board[$handPosition] == 2 || $board[$handPosition] == 3)// si on deux ou trois pierres
            &&
            ($currentPlayer == 0 && $handPosition > 5 || $currentPlayer == 1 && $handPosition < 6)//Et qu'on est du bon côté du plateau en fonction du joueur courant
         ) {
            $game->addScore($currentPlayer, $board[$handPosition]);
            $board[$handPosition] = 0;
            $handPosition--;
        }

        return $game->setBoard($board);
    }

    private function changeCurrentPlayer(Game $game)
    {
        if ($game->getCurrentPlayer() == Game::PLAYER_1) {
            return $game->setCurrentPlayer(Game::PLAYER_2);
        }

        if ($game->getCurrentPlayer() == Game::PLAYER_2) {
            return $game->setCurrentPlayer(Game::PLAYER_1);
        }
    }

    private function calculateWinner(Game $game): Game
    {
        //TODO
        //en fonction du board
        return $game;
    }
}