<?php

namespace App\Tests\Integration\Command;

use App\Dto\Command\GenerateNickCommand;
use App\Dto\Result\GeneratedNickData;
use App\Dto\Result\GeneratedNickWord;
use App\Entity\Nick;
use App\Entity\Qualifier;
use App\Entity\Subject;
use App\Entity\Word;
use App\Enum\GrammaticalRoleType;
use App\Enum\OffenseLevel;
use App\Enum\QualifierPosition;
use App\Enum\WordGender;
use App\Enum\WordStatus;
use App\UseCase\GenerateNickInterface;
use PHPUnit\Framework\Attributes\Test;
use Psr\Clock\ClockInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Clock\MockClock;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @author Wilhelm Zwertvaegher
 */
class GenerateNicksCommandIT extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();

        self::getContainer()->set(GenerateNickInterface::class,
            new class(new MockClock()) implements GenerateNickInterface {
                private static $counter = 0;

                public function __construct(private readonly ClockInterface $clock)
                {
                }

                public function __invoke(GenerateNickCommand $generateNickCommand): GeneratedNickData
                {
                    $gender = $generateNickCommand->getGender() ?? WordGender::NEUTRAL;
                    $offenseLevel = $generateNickCommand->getOffenseLevel() ?? OffenseLevel::MEDIUM;
                    $now = $this->clock->now();
                    $subjectWord = new Word(
                        'subject-'.self::$counter,
                        'Subject'.self::$counter,
                        $gender,
                        $generateNickCommand->getLang(),
                        $offenseLevel,
                        WordStatus::APPROVED,
                        $now,
                        $now
                    );

                    $qualifierWord = new Word(
                        'qualifier-'.self::$counter,
                        'Qualifier'.self::$counter,
                        $gender,
                        $generateNickCommand->getLang(),
                        $offenseLevel,
                        WordStatus::APPROVED,
                        $now,
                        $now
                    );

                    $subject = new Subject($subjectWord);
                    $qualifier = new Qualifier($qualifierWord, QualifierPosition::AFTER);

                    $result = new GeneratedNickData(
                        $subjectWord->getGender(),
                        $subjectWord->getOffenseLevel(),
                        new Nick(
                            $subjectWord->getLabel().' '.$qualifierWord->getLabel(),
                            $subject,
                            $qualifier,
                            $subjectWord->getGender(),
                            $subjectWord->getOffenseLevel(),
                            $now,
                            $now
                        ),
                        [
                            new GeneratedNickWord(self::$counter, $subjectWord->getLabel(), GrammaticalRoleType::SUBJECT),
                            new GeneratedNickWord(self::$counter, $qualifierWord->getLabel(), GrammaticalRoleType::QUALIFIER),
                        ]
                    );

                    ++self::$counter;

                    return $result;
                }
            }
        );
    }

    #[Test]
    public function shouldGenerateNicks(): void
    {
        $application = new Application(self::$kernel);

        $command = $application->find('app:generate-nicks');
        $commandTester = new CommandTester($command);

        $commandTester->execute(['--number' => '2']);

        self::assertSame(Command::SUCCESS, $commandTester->getStatusCode());
        self::assertStringContainsString('Generating 2 nicks', $commandTester->getDisplay());
        self::assertStringContainsString('1 →  Subject1 Qualifier1', $commandTester->getDisplay());
        self::assertStringContainsString('2 →  Subject2 Qualifier2', $commandTester->getDisplay());
    }
}
