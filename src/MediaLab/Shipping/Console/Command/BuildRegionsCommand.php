<?php

namespace MediaLab\Shipping\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Guzzle\Http\Exception\ClientErrorResponseException;
use MediaLab\Shipping\SFExpress\Client;

class BuildRegionsCommand extends Command
{
    private $client;
    private $level;

    protected function configure()
    {
        $this
            ->setName('regions:build')
            ->setDescription('Builds regions list.')
            ->setDefinition(array(
                new InputArgument('level', InputArgument::OPTIONAL, 'Region level'),
            ))
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->level = $input->getArgument('level');

        file_put_contents(
            __DIR__.'../../../../../../data/regions.data',
            serialize($this->getRegions('A000000000', $output))
        );
    }

    private function getRegions($code, OutputInterface $output)
    {
        try {
            $regions = $this->getClient()->getSubregions($code);
        } catch (ClientErrorResponseException $e) {
            return [];
        }

        foreach ($regions as $region) {
            if (null === $region['name']) {
                continue;
            }

            $output->writeln(sprintf('%s <comment>%s</comment>', $region['name'], $region['code']));

            if (null !== $this->level && $region['level'] >= $this->level) {
                continue;
            }

            $regions = array_merge($regions, $this->getRegions($region['code'], $output));
        }

        return $regions;
    }

    private function getClient()
    {
        if (null === $this->client) {
            $this->client = new Client();
        }

        return $this->client;
    }
}
