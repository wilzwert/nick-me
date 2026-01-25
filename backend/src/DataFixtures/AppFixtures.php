<?php

namespace App\DataFixtures;

use App\Entity\Nick;
use App\Entity\Notification;
use App\Entity\Qualifier;
use App\Entity\Subject;
use App\Entity\Word;
use App\Enum\Lang;
use App\Enum\NotificationStatus;
use App\Enum\NotificationType;
use App\Enum\OffenseLevel;
use App\Enum\QualifierPosition;
use App\Enum\WordGender;
use App\Enum\WordStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectManager;
use Psr\Clock\ClockInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ClockInterface $clock,
    ) {
    }

    /**
     * Description of words, subjects and qualifiers to create.
     *
     * @return list<array>
     */
    private function getWordsToCreate(): array
    {
        return [
            ['id' => 1, 'slug' => 'coquin', 'label' => 'Coquin', 'offenseLevel' => OffenseLevel::MEDIUM, 'lang' => Lang::FR, 'gender' => WordGender::AUTO, 'asSubject' => true, 'asQualifier' => true],

            ['id' => 2, 'slug' => 'banane', 'label' => 'Banane', 'offenseLevel' => OffenseLevel::LOW, 'lang' => Lang::FR, 'gender' => WordGender::F, 'asSubject' => true],
            ['id' => 3, 'slug' => 'camembert', 'label' => 'Camembert', 'offenseLevel' => OffenseLevel::MEDIUM, 'lang' => Lang::FR, 'gender' => WordGender::M, 'asSubject' => true],
            ['id' => 4, 'slug' => 'heretique', 'label' => 'Hérétique', 'offenseLevel' => OffenseLevel::MAX, 'lang' => Lang::FR, 'gender' => WordGender::NEUTRAL, 'asSubject' => true],
            ['id' => 9, 'slug' => 'corsaire', 'label' => 'Corsaire', 'offenseLevel' => OffenseLevel::MEDIUM, 'lang' => Lang::FR, 'gender' => WordGender::NEUTRAL, 'asSubject' => true],

            ['id' => 5, 'slug' => 'peureux', 'label' => 'Peureux', 'offenseLevel' => OffenseLevel::LOW, 'lang' => Lang::FR, 'gender' => WordGender::AUTO, 'asQualifier' => true],
            ['id' => 6, 'slug' => 'indiscrete', 'label' => 'Indiscrète', 'offenseLevel' => OffenseLevel::MEDIUM, 'lang' => Lang::FR, 'gender' => WordGender::F, 'asQualifier' => true],
            ['id' => 7, 'slug' => 'interrogateur', 'label' => 'Interrogateur', 'offenseLevel' => OffenseLevel::MEDIUM, 'lang' => Lang::FR, 'gender' => WordGender::M, 'asQualifier' => true],
            ['id' => 8, 'slug' => 'fataliste', 'label' => 'Fataliste', 'offenseLevel' => OffenseLevel::MAX, 'lang' => Lang::FR, 'gender' => WordGender::NEUTRAL, 'asQualifier' => true],
            ['id' => 10, 'slug' => 'nucleaire', 'label' => 'Nucléaire', 'offenseLevel' => OffenseLevel::MAX, 'lang' => Lang::FR, 'gender' => WordGender::NEUTRAL, 'asQualifier' => true],
            ['id' => 11, 'slug' => 'humide', 'label' => 'Humide', 'offenseLevel' => OffenseLevel::MEDIUM, 'lang' => Lang::FR, 'gender' => WordGender::NEUTRAL, 'asQualifier' => true],
        ];
    }

    /**
     * @return list<\ReflectionProperty>
     */
    private function prepareMetadata(): array
    {
        $classMetadata = $this->entityManager->getClassMetadata(Word::class);
        $classMetadata->setIdGenerator(new AssignedGenerator());
        $classMetadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);

        $classMetadata = $this->entityManager->getClassMetadata(Subject::class);
        $classMetadata->setIdGenerator(new AssignedGenerator());
        $classMetadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);

        $classMetadata = $this->entityManager->getClassMetadata(Qualifier::class);
        $classMetadata->setIdGenerator(new AssignedGenerator());
        $classMetadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);

        $classMetadata = $this->entityManager->getClassMetadata(Nick::class);
        $classMetadata->setIdGenerator(new AssignedGenerator());
        $classMetadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);

        $classMetadata = $this->entityManager->getClassMetadata(Notification::class);
        $classMetadata->setIdGenerator(new AssignedGenerator());
        $classMetadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);

        $reflectionClass = new \ReflectionClass(Word::class);
        $wordIdReflectionProperty = $reflectionClass->getProperty('id');
        $wordIdReflectionProperty->setAccessible(true);

        $reflectionClass = new \ReflectionClass(Subject::class);
        $subjectIdReflectionProperty = $reflectionClass->getProperty('id');
        $subjectIdReflectionProperty->setAccessible(true);

        $reflectionClass = new \ReflectionClass(Qualifier::class);
        $qualifierIdReflectionProperty = $reflectionClass->getProperty('id');
        $qualifierIdReflectionProperty->setAccessible(true);

        $reflectionClass = new \ReflectionClass(Nick::class);
        $nickIdReflectionProperty = $reflectionClass->getProperty('id');
        $nickIdReflectionProperty->setAccessible(true);

        $reflectionClass = new \ReflectionClass(Notification::class);
        $notificationIdReflectionProperty = $reflectionClass->getProperty('id');
        $notificationIdReflectionProperty->setAccessible(true);

        return [
            $wordIdReflectionProperty,
            $subjectIdReflectionProperty,
            $qualifierIdReflectionProperty,
            $nickIdReflectionProperty,
            $notificationIdReflectionProperty,
        ];
    }

    private function updateSequences(): void
    {
        $tables = ['word', 'subject', 'qualifier', 'nick', 'notification'];

        foreach ($tables as $table) {
            $this->entityManager->getConnection()->executeStatement(
                sprintf(
                    'SELECT setval(pg_get_serial_sequence(\'%1$s\',\'id\'), (SELECT MAX(id) FROM %1$s))',
                    $table
                )
            );
        }
    }

    public function load(ObjectManager $manager): void
    {
        [
            $wordIdReflectionProperty,
            $subjectIdReflectionProperty,
            $qualifierIdReflectionProperty,
            $nickIdReflectionProperty,
            $notificationIdReflectionProperty,
        ] = $this->prepareMetadata();

        $wordsToCreate = $this->getWordsToCreate();
        $subjects = $qualifiers = [];

        $now = $this->clock->now();

        foreach ($wordsToCreate as $wordToCreate) {
            $word = new Word(
                slug: $wordToCreate['slug'],
                label: $wordToCreate['label'],
                gender: $wordToCreate['gender'],
                lang: $wordToCreate['lang'],
                offenseLevel: $wordToCreate['offenseLevel'],
                status: WordStatus::APPROVED,
                createdAt: $now,
                updatedAt: $now,
            );

            $wordIdReflectionProperty->setValue($word, $wordToCreate['id']);
            $manager->persist($word);

            if (!empty($wordToCreate['asSubject'])) {
                $subject = new Subject($word);
                $subjectIdReflectionProperty->setValue($subject, $wordToCreate['id']);
                $manager->persist($subject);
                $subjects[$wordToCreate['id']] = $subject;
            }

            if (!empty($wordToCreate['asQualifier'])) {
                $qualifier = new Qualifier($word, $wordToCreate['qualifierPosition'] ?? QualifierPosition::AFTER);
                $qualifierIdReflectionProperty->setValue($qualifier, $wordToCreate['id']);
                $manager->persist($qualifier);
                $qualifiers[$wordToCreate['id']] = $qualifier;
            }
        }

        // create a Nick
        $nick = new Nick(
            label: 'Camembert Interrogateur',
            subject: $subjects[3],
            qualifier: $qualifiers[7],
            targetGender: WordGender::M,
            offenseLevel: OffenseLevel::MEDIUM,
            createdAt: $now,
            lastUsedAt: $now
        );
        $nickIdReflectionProperty->setValue($nick, 1);
        $manager->persist($nick);

        // create a Notification
        $notification = new Notification(
            type: NotificationType::CONTACT,
            recipientEmail: 'test@example.com',
            subject: 'Contact notification subject',
            content: 'Contact notification content',
            status: NotificationStatus::PENDING,
            createdAt: $now,
            statusUpdatedAt: $now
        );
        $notificationIdReflectionProperty->setValue($notification, 1);
        $manager->persist($notification);

        $notification = new Notification(
            type: NotificationType::SUGGESTION,
            recipientEmail: 'test@example.com',
            subject: 'Suggestion notification subject',
            content: 'Suggestion notification content',
            status: NotificationStatus::HANDLED,
            createdAt: $now,
            statusUpdatedAt: $now
        );
        $notificationIdReflectionProperty->setValue($notification, 2);
        $manager->persist($notification);

        $manager->flush();

        // update id sequences to allow further entities creation
        $this->updateSequences();
    }
}
