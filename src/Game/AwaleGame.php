<?php
namespace App\Game;

use App\Entity\Game;

class AwaleGame
{
    const NB_CASES = 12;
    const POINTS_TO_WIN = 25;
    const HOLES_PLAYER_1 = [0, 1, 2, 3, 4 ,5];
    const HOLES_PLAYER_2 = [6, 7, 8, 9, 10 ,11];

    public function play(Game $game, int $player, int $case): Game
    {
        if ($this->invalidMove($game, $case)) {
            echo "INVALID MOVE\n";
            return $game;
        }

        $game = $this->move($game, $player, $case);
        $game = $this->changeCurrentPlayer($game);

        if ($this->checkEndBecauseNoSeeds($game)) {
            $game = $game->giveAllSeedsToOpponent($game);
            $game->setStatus(Game::STATUS_FINISHED);
        }

        $game = $this->calculateWinner($game);

        return $game;
    }

    //gestion du déplacement
    private function move(Game $game, int $player, int $case): Game
    {
        $handPosition = 0;
        $board = $game->getBoard();
        $nbStones = $board[$case];
        $nbLaps = (int)\floor($nbStones / self::NB_CASES);
        $nbStones += $nbLaps;//On ajoute un déplacement pour sauter la case de départ
        $board[$case] = 0;

        for ($i = 1; $i <= $nbStones; $i++) {
            $handPosition = $case + $i;

            while ($handPosition >= self::NB_CASES) {
                $handPosition -= self::NB_CASES;
            } 

            if ($handPosition != $case) {//on ne remet pas de pierre dans la case de départ
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
            (//Et qu'on est du bon côté du plateau en fonction du joueur courant
                ($currentPlayer == Game::PLAYER_1 && $handPosition > 5) || 
                ($currentPlayer == Game::PLAYER_2 && $handPosition < 6)
            )
         ) {
            $game->addScore($currentPlayer, $board[$handPosition]);
            $board[$handPosition] = 0;
            $handPosition--;

            if ($handPosition < 0) {
                $handPosition += self::NB_CASES;
            }
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

    private function invalidMove(Game $game, int $case): bool
    {
        if (!\in_array($game->getStatus(), [Game::STATUS_READY, Game::STATUS_IN_PROGRESS])) {
            echo "INVALID STATUS\n";
            return true;
        }

        if ($game->getCurrentPlayer() == Game::PLAYER_1 && $case > 5) {
            echo "INVALID PLAYER \n";
            return true;
        }

        if ($game->getCurrentPlayer() == Game::PLAYER_2 && $case < 6) {
            echo "INVALID PLAYER \n";
            return true;
        }

        if (empty($game->getBoard()[$case])) {
            echo "INVALID Empty case\n";
            return true;
        }

        return false;
    }

    private function calculateWinner(Game $game): Game
    {
        $scores = $game->getScores();

        if ($scores[Game::PLAYER_1] > self::POINTS_TO_WIN) {
            $game->setStatus(Game::STATUS_FINISHED);
            $game->setWinner($game->getPlayers()[Game::PLAYER_1]->getUser());
        }

        if ($scores[Game::PLAYER_2] >= self::POINTS_TO_WIN) {
            $game->setStatus(Game::STATUS_FINISHED);
            $game->setWinner($game->getPlayers()[Game::PLAYER_2]->getUser());
        }

        return $game;
    }

    private function checkEndBecauseNoSeeds(Game $game)
    {
        $board = $game->getBoard();

        if ($game->getCurrentPlayer() == game::PLAYER_1) {
            foreach(self::HOLES_PLAYER_1 as $hole) {
                if ($board[$hole] > 0) {
                    return false;
                }
            }
        }

        if ($game->getCurrentPlayer() == game::PLAYER_2) {
            foreach(self::HOLES_PLAYER_2 as $hole) {
                if ($board[$hole] > 0) {
                    return false;
                }
            }
        }

        return true;
    }

    private function giveAllSeedsToOpponent(Game $game): Game
    {
        //TODO
        //On prend toute les pierres de l'adeversaire pour les donner au joueur courant (en fait on inverse le point de vue)
        //On ajoute bien les pierres au score du joueur courant

        return $game;
    }
}