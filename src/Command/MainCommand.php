<?php

namespace App\Command;


use App\Entity\Post;
use App\Service\PostHandler;
use Symfony\Component\Console\Attribute\AsCommand;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'app:main',
    description: 'Test ETL',
    hidden: false,
)]
class MainCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private HttpClientInterface $client,
        private PostHandler $ph
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'Start ETL',
            '============',
        ]);

        $response = $this->client->request('GET', 'https://pokeapi.co/api/v2/pokemon', [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        $pokemons = $response->toArray();
        $progressBar = new ProgressBar($output, sizeof($pokemons['results']));
        $progressBar->setFormat('debug');
        $progressBar->start();

        foreach ($pokemons['results'] as $pokemon) {
            $this->ph->createPostFromData($pokemon);
        }

        $progressBar->finish();

        $output->writeln([
            '',
            'End ETL',
            '============',
        ]);

        return Command::SUCCESS;
    }
}