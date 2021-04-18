<?php
namespace App\Command;

use App\Server\AwaleServer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class AwaleServerCommand extends Command
{
    private $awaleServer;
    private $awaleServerPort;

    public function __construct(AwaleServer $awaleServer, $awaleServerPort)
    {
        parent::__construct();

        $this->awaleServer = $awaleServer;
        $this->awaleServerPort = $awaleServerPort;
    }

    protected function configure()
    {
        $this
            ->setName('server:awale')
            ->setDescription('Start awale server');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $port = $this->awaleServerPort;

        $output->writeln(\sprintf('Démarrage port %s', $port));

        $server = IoServer::factory(
            new HttpServer(new WsServer($this->awaleServer)),
            $port,
            '127.0.0.1'
        );
        $server->run();
    }
}
