<?php
namespace App\Tests\Service;

use App\Entity\Game;
use App\Game\AwaleGame;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AwaleGameTest extends KernelTestCase
{
    public function testMove()
    {
        $awaleGame = new AwaleGame();

        $tests = [
            [
                'player' => Game::PLAYER_1,
                'case' => 0,
                'board' => [4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4],
                'boardExpected' => [0, 5, 5, 5, 5, 4, 4, 4, 4, 4, 4, 4],
                'scoresExpected' => [0, 0]
            ],[
                'player' => Game::PLAYER_2,
                'case' => 7,
                'board' => [0, 5, 5, 5, 5, 4, 4, 4, 4, 4, 4, 4],
                'boardExpected' => [0, 5, 5, 5, 5, 4, 4, 0, 5, 5, 5, 5],
                'scoresExpected' => [0, 0]
            ],[
                'player' => Game::PLAYER_1,
                'case' => 3,
                'board' => [0, 5, 5, 5, 5, 4, 4, 0, 5, 5, 5, 5],
                'boardExpected' => [0, 5, 5, 0, 6, 5, 5, 1, 6, 5, 5, 5],
                'scoresExpected' => [0, 0]
            ],[
                'player' => Game::PLAYER_2,
                'case' => 6,
                'board' => [0, 5, 5, 0, 6, 5, 5, 1, 6, 5, 5, 5],
                'boardExpected' => [0, 5, 5, 0, 6, 5, 0, 2, 7, 6, 6, 6],
                'scoresExpected' => [0, 0]
            ],[
                'player' => Game::PLAYER_1,
                'case' => 2,
                'board' => [0, 5, 5, 0, 6, 5, 0, 2, 7, 6, 6, 6],
                'boardExpected' => [0, 5, 0, 1, 7, 6, 1, 0, 7, 6, 6, 6],
                'scoresExpected' => [3, 0]
            ]
        ];

        foreach ($tests as $test) {
            $game = new Game();
            ;
            $game = $awaleGame->play(
                $game->setBoard($test['board']), 
                $test['player'],
                $test['case']
            );

            $this->assertEquals($test['boardExpected'], $game->getBoard());
            $this->assertEquals($test['scoresExpected'], $game->getScores());
        }
    }
}