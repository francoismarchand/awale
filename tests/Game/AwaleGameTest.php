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
            ],[
                'player' => Game::PLAYER_2,
                'case' => 10,
                'board' => [1, 1, 0, 0, 12, 0, 0, 1, 0, 1, 16, 11],
                'boardExpected' => [0, 0, 0, 0, 13, 1, 1, 2, 1, 2, 0, 13],
                'scoresExpected' => [0, 10]
            ]
        ];

        foreach ($tests as $test) {
            $game = new Game();
            $game->setBoard($test['board']);
            $game->setCurrentPlayer($test['player']);
            $game->setStatus(Game::STATUS_IN_PROGRESS);
            $game = $awaleGame->play(
                $game, 
                $test['player'],
                $test['case']
            );

            $this->assertEquals($test['boardExpected'], $game->getBoard());
            $this->assertEquals($test['scoresExpected'], $game->getScores());
        }
    }

    //TODO test win
    //TODO test on no seeds to play
    //TODO test
}