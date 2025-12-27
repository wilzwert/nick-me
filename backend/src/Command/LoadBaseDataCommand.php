<?php

namespace App\Command;

use App\Entity\Word;
use App\Entity\Qualifier;
use App\Entity\Subject;
use App\Enum\WordGender;
use App\Enum\QualifierPosition;
use App\Enum\Lang;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Wilhelm Zwertvaegher
 */

class LoadBaseDataCommand extends Command
{
    protected static $defaultName = 'app:load-base-data';
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Loading base data...');

        // TODO : load data from csv and create Subjects and Qualifiers
        $this->em->flush();

        $output->writeln('Base data loaded successfully.');
        return Command::SUCCESS;
    }
}
