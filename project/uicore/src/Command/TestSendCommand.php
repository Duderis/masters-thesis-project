<?php

namespace App\Command;

use App\Entity\Analysis;
use App\Service\Communication\PythonCommunicationInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TestSendCommand extends Command
{
    protected static $defaultName = 'app:test-send';

    /**
     * @var PythonCommunicationInterface
     */
    private $thing;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * TestSendCommand constructor.
     * @param PythonCommunicationInterface $thing
     * @param EntityManagerInterface $manager
     */
    public function __construct(PythonCommunicationInterface $thing, EntityManagerInterface $manager)
    {
        parent::__construct();
        $this->thing = $thing;
        $this->manager = $manager;
    }


    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }
        $analysisRepo = $this->manager->getRepository(Analysis::class);
        /** @var Analysis $analysis */
        $analysis = $analysisRepo->findOneBy([]);
        $this->thing->startAnalysis($analysis);

        return 0;
    }
}
