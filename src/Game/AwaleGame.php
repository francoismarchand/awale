<?php
namespace App\Game;

use App\Entity\Partie;

class AwaleGame
{
    const NB_CASES = 12;

    public function play(Partie $partie, int $player, int $case): Partie
    {
        $partie = $this->move($partie, $player, $case);
        $partie = $this->calculateWinner($partie);

        return $partie;
    }

    //gestion du déplacement
    private function move(Partie $partie, int $player, int $case): Partie
    {
        $handPosition = 0;
        $board = $partie->getBoard();
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

        $partie->setBoard($board);

        $partie = $this->eat($partie, $player, $handPosition);
        //TODO on change le joueur courant

        return $partie;
    }

    //Gestion des pions mangés
    private function eat(Partie $partie, int $currentPlayer, int $handPosition): Partie
    {
        $board = $partie->getBoard();

        while(
            ($board[$handPosition] == 2 || $board[$handPosition] == 3)// si on deux ou trois pierres
            &&
            ($currentPlayer == 0 && $handPosition > 5 || $currentPlayer == 1 && $handPosition < 6)//Et qu'on est du bon côté du plateau en fonction du joueur courant
         ) {
            $partie->addScore($currentPlayer, $board[$handPosition]);
            $board[$handPosition] = 0;
            $handPosition--;
        }

        return $partie->setBoard($board);
    }

    private function calculateWinner(Partie $partie): Partie
    {
        //TODO
        //en fonction du board
        return $partie;
    }
}