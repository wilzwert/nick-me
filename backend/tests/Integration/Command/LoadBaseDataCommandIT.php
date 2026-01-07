<?php

namespace App\Tests\Integration\Command;

use App\Enum\OffenseLevel;
use App\Enum\QualifierPosition;
use App\Enum\WordGender;
use App\Repository\QualifierRepositoryInterface;
use App\Repository\SubjectRepositoryInterface;
use App\Repository\WordRepositoryInterface;
use App\Service\Data\WordSluggerInterface;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @author Wilhelm Zwertvaegher
 */
class LoadBaseDataCommandIT extends KernelTestCase
{
    #[Test]
    public function shouldCreateWords(): void
    {
        self::bootKernel();
        $application = new Application(self::$kernel);

        $command = $application->find('app:load-base-data');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
        ]);

        $commandTester->assertCommandIsSuccessful();

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        self::assertStringContainsString('Loading subjects', $output);
        self::assertStringContainsString('Created subject Amibe', $output);

        /** @var WordSluggerInterface $slugger */
        $slugger = self::getContainer()->get(WordSluggerInterface::class);

        /** @var WordRepositoryInterface $wordRepository */
        $wordRepository = self::getContainer()->get(WordRepositoryInterface::class);

        /** @var SubjectRepositoryInterface $subjectRepository */
        $subjectRepository = self::getContainer()->get(SubjectRepositoryInterface::class);

        /** @var QualifierRepositoryInterface $qualifierRepository */
        $qualifierRepository = self::getContainer()->get(QualifierRepositoryInterface::class);

        $wordsToCheck = [
            ['Amibe', WordGender::F, OffenseLevel::HIGH, true, false, null],
            ['Animal', WordGender::M, OffenseLevel::LOW, true, false, null],
            ['Corsaire', WordGender::NEUTRAL, OffenseLevel::MEDIUM, true, false, null],
            ['Farceur', WordGender::AUTO, OffenseLevel::LOW, true, true, QualifierPosition::AFTER],
            ['Coulant', WordGender::AUTO, OffenseLevel::MEDIUM, false, true, QualifierPosition::AFTER],
            ['Humide', WordGender::NEUTRAL, OffenseLevel::HIGH, false, true, QualifierPosition::AFTER],
            ['Maligne', WordGender::F, OffenseLevel::MEDIUM, false, true, QualifierPosition::AFTER],
            ['Indiscret', WordGender::M, OffenseLevel::MEDIUM, false, true, QualifierPosition::AFTER],
        ];

        foreach ($wordsToCheck as $word) {
            list($label, $gender, $offenseLevel, $shouldBeSubject, $shouldBeQualifier, $qualifierPosition) = $word;
            $expectedSlug = $slugger->slug($label);
            $word = $wordRepository->findBySlug($expectedSlug);
            self::assertNotNull($word, $expectedSlug.' not found');
            self::assertEquals($gender, $word->getGender(), $expectedSlug.' should have gender '.$gender->value);
            self::assertEquals($offenseLevel, $word->getOffenseLevel(), $expectedSlug.' should have offense '.$offenseLevel->value);

            if ($shouldBeSubject) {
                self::assertNotNull($subjectRepository->findByWordId($word->getId()), $expectedSlug.' should be a subject with wordId '.$word->getId());
            }
            if ($shouldBeQualifier) {
                $qualifier = $qualifierRepository->findByWordId($word->getId());
                self::assertNotNull($qualifier, $expectedSlug.' should be a qualifier');
                self::assertEquals($qualifierPosition, $qualifier->getPosition(), $expectedSlug.' should have position '.$qualifierPosition->value);
            }
        }
    }
}
