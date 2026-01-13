<?php

namespace App\Command;

use App\Dto\Command\GenerateNickCommand;
use App\Enum\Lang;
use App\Enum\OffenseLevel;
use App\Enum\WordGender;
use App\UseCase\GenerateNickInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * @author Wilhelm Zwertvaegher
 */
#[AsCommand(name: 'app:generate-nicks')]
class GenerateNicksCommand extends Command
{
    public function __construct(
        private readonly GenerateNickInterface $generateNick,
        private readonly Stopwatch $stopwatch,
    ) {
        parent::__construct();
    }

    public function __invoke(
        InputInterface $input,
        OutputInterface $output,
        #[Option('Number of nicks to generate', 'number')] string $numberOfNicksToGenerateStr = '10',
        #[Option('Target lang.')] string $targetLangStr = 'fr',
        #[Option('Target offense level.', 'offense-level')] ?string $offenseLevelStr = null,
        #[Option('Target gender.')] ?string $targetGenderStr = null,
    ): int {
        $offenseLevel = $targetGender = null;

        if (!filter_var($numberOfNicksToGenerateStr, FILTER_VALIDATE_INT)) {
            throw new \InvalidArgumentException('Number of nicks to generate must be an integer');
        }
        if (null !== $offenseLevelStr && !filter_var($offenseLevelStr, FILTER_VALIDATE_INT)) {
            throw new \InvalidArgumentException('Offense level must be a known value of OffenseLevel enum');
        }

        if (null !== $offenseLevelStr) {
            try {
                $offenseLevel = OffenseLevel::from((int) $offenseLevelStr);
            } catch (\ValueError $exception) {
                throw new \InvalidArgumentException('Offense level must be a known value of OffenseLevel enum');
            }
        }

        if (null !== $targetGenderStr) {
            try {
                $targetGender = WordGender::from($targetGenderStr);
            } catch (\ValueError $exception) {
                throw new \InvalidArgumentException('Gender must be a known value of WordGender enum');
            }
        }

        try {
            $targetLang = Lang::from($targetLangStr);
        } catch (\ValueError $exception) {
            throw new \InvalidArgumentException('Target lang must be a known value of Lang enum');
        }

        $io = new SymfonyStyle($input, $output);

        $numberOfNicksToGenerate = (int) $numberOfNicksToGenerateStr;

        $output->writeln("Generating {$numberOfNicksToGenerate} nicks");
        $io->section('Nicks generation');
        $io->comment('Warming up services...');

        // first generation to warm up services and DI
        ($this->generateNick)(new GenerateNickCommand($targetLang, $targetGender, $offenseLevel));

        $this->stopwatch->start('global');
        $this->stopwatch->start('nick');

        for ($i = 0; $i < $numberOfNicksToGenerate; ++$i) {
            $nickData = ($this->generateNick)(
                new GenerateNickCommand($targetLang, $targetGender, $offenseLevel)
            );

            $event = $this->stopwatch->lap('nick');
            $lastPeriod = $event->getPeriods()[array_key_last($event->getPeriods())];

            $io->text(sprintf(
                '<info>%d</info> →  %s <comment>%.2f ms</comment>',
                $i + 1,
                $nickData->getNick()->getLabel(),
                $lastPeriod->getDuration()
            ));
        }

        $globalEvent = $this->stopwatch->stop('global');
        $nickEvent = $this->stopwatch->getEvent('nick');
        $durations = array_map(fn ($p) => $p->getDuration(), $nickEvent->getPeriods());

        $io->section('Summary');
        $io->definitionList(
            ['Total nicks' => count($durations)],
            ['Total duration' => sprintf('%.2f ms', $globalEvent->getDuration())],
            ['Min duration' => sprintf('%.2f ms', min($durations))],
            ['Max duration' => sprintf('%.2f ms', max($durations))],
        );

        return Command::SUCCESS;
    }
}
