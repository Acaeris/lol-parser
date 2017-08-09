<?php

namespace LeagueOfData\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use bandwidthThrottle\tokenBucket\TokenBucket;
use bandwidthThrottle\tokenBucket\storage\FileStorage;
use bandwidthThrottle\tokenBucket\Rate;

class ResetBucketCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('bucket:reset')
            ->setDescription('Reset the API bucket');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $storage = new FileStorage(__DIR__ . "/../Adapters/api.bucket");
        $rate = new Rate(10, Rate::MINUTE);
        $bucket = new TokenBucket(10, $rate, $storage);
        $bucket->bootstrap(10);
        $output->writeln("Bucket has been reset");
    }
}
