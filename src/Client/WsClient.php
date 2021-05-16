<?php
namespace App\Client;

use App\Entity\Game;
use App\Server\AwaleDto;

class WsClient
{
    private $awaleServerUrl;

    public function __construct(string $awaleServerUrl)
    {
        $this->awaleServerUrl = $awaleServerUrl;
    }

    public function sendGameReady(Game $game)
    {
        $awaleDto = new AwaleDto();
        $awaleDto->player = 0;
        $awaleDto->game = $game->getUuid();
        $awaleDto->action = AwaleDto::ACTION_READY;

        $this->sendWebSocket($awaleDto);
    }

    private function sendWebSocket(AwaleDto $awaleDto): void
    {
        $data = \json_encode($awaleDto);
        $url = \sprintf(
            '%s/%s/%s',
            $this->awaleServerUrl,
            $awaleDto->game,
            $awaleDto->player
        );
        \Ratchet\Client\connect($url)->then(function($connexion) use ($data) {
            $connexion->send($data);
            $connexion->close();
        }, function ($e) {

        });
    }
}
