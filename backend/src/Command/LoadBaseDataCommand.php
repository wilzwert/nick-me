<?php

namespace App\Command;

use App\Dto\Command\MaintainWordCommand;
use App\Dto\Csv\CsvQualifier;
use App\Dto\Csv\CsvSubject;
use App\Enum\OffenseLevel;
use App\Enum\WordGender;
use App\Enum\QualifierPosition;
use App\Enum\Lang;
use App\Enum\WordStatus;
use App\Service\Data\WordSluggerInterface;
use App\UseCase\MaintainWord;
use League\Csv\Reader;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * @author Wilhelm Zwertvaegher
 */

#[AsCommand(name: 'app:load-base-data')]
class LoadBaseDataCommand extends Command
{
    public function __construct(
        private readonly MaintainWord $maintainWord,
        private readonly WordSluggerInterface $wordSlugger,
        #[Autowire('%base_data.subject_csv%')]
        private readonly string $subjectCsvPath,
        #[Autowire('%base_data.qualifier_csv%')]
        private readonly string $qualifierCsvPath
    )
    {
        parent::__construct();
    }

    public function __invoke(
        OutputInterface $output,
        #[Option('Append the data fixtures instead of deleting all data from the database first.')] bool $append = false
    ): int
    {
        $output->writeln('Loading base data...');
        $output->writeln('Loading subjects...');

        if (!$append) {
            // TODO : implement data deletion before loading
        }

        $reader = Reader::from($this->subjectCsvPath);
        $reader->setHeaderOffset(0);
        $subjectsSlugs = [];

        foreach ($reader->getRecordsAsObject(CsvSubject::class) as $record) {
            $fullWordDto = ($this->maintainWord)(
                new MaintainWordCommand(
                    trim($record->label),
                    WordGender::fromString($record->gender),
                    Lang::FR,
                    OffenseLevel::fromString($record->offenseLevel ?? 'MEDIUM'),
                    WordStatus::APPROVED,
                    true
                )
            );
            $output->writeln("Created subject {$fullWordDto->label} with word_id {$fullWordDto->id}");
            $subjectsSlugs[] = $fullWordDto->slug;
        }

        $output->writeln('Loading qualifiers...');

        $reader = Reader::from($this->qualifierCsvPath);
        $reader->setHeaderOffset(0);

        foreach ($reader->getRecordsAsObject(CsvQualifier::class) as $record) {
            $slug = $this->wordSlugger->slug($record->label);

            $fullWordDto = ($this->maintainWord)(
                new MaintainWordCommand(
                    trim($record->label),
                    WordGender::fromString($record->gender ?? 'AUTO'),
                    Lang::FR,
                    OffenseLevel::fromString($record->offenseLevel ?? 'MEDIUM'),
                    WordStatus::APPROVED,
                    in_array($slug, $subjectsSlugs),
                    true,
                    QualifierPosition::from($record->position ?? 'after')
                )
            );
            $output->writeln("Created qualifier {$fullWordDto->label} with id {$fullWordDto->id}");
        }

        $output->writeln('Base data loaded successfully.');
        return Command::SUCCESS;
    }
}
