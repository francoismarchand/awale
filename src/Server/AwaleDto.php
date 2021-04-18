<?php

namespace App\Server;

class AwaleDto
{
    const ACTION_PLAY = 'play';
    const ACTION_READY = 'action_ready';

    public $game;
    public $player;
    public $case;
    public $status;
    public $action;
}