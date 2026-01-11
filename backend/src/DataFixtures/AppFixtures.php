<?php

namespace App\DataFixtures;

use App\Entity\Nick;
use App\Entity\Qualifier;
use App\Entity\Subject;
use App\Entity\Word;
use App\Enum\Lang;
use App\Enum\OffenseLevel;
use App\Enum\QualifierPosition;
use App\Enum\WordGender;
use App\Enum\WordStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectManager;
use ReflectionProperty;

class AppFixtures extends Fixture
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
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
     * @return list<ReflectionProperty>
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

        return [$wordIdReflectionProperty, $subjectIdReflectionProperty, $qualifierIdReflectionProperty, $nickIdReflectionProperty];
    }

    public function load(ObjectManager $manager): void
    {
        [$wordIdReflectionProperty, $subjectIdReflectionProperty, $qualifierIdReflectionProperty, $nickIdReflectionProperty] = $this->prepareMetadata();

        $wordsToCreate = $this->getWordsToCreate();
        $subjects = $qualifiers = [];

        foreach ($wordsToCreate as $wordToCreate) {
            $word = new Word(
                slug: $wordToCreate['slug'],
                label: $wordToCreate['label'],
                gender: $wordToCreate['gender'],
                lang: $wordToCreate['lang'],
                offenseLevel: $wordToCreate['offenseLevel'],
                status: WordStatus::APPROVED
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
            offenseLevel: OffenseLevel::MEDIUM
        );
        $nickIdReflectionProperty->setValue($nick, 1);
        $manager->persist($nick);

        $manager->flush();

        // update sequences to allow further creation with auto id generation
        $this->entityManager->getConnection()->executeStatement(
            "SELECT setval(pg_get_serial_sequence('word','id'), (SELECT MAX(id) FROM word))"
        );
        $this->entityManager->getConnection()->executeStatement(
            "SELECT setval(pg_get_serial_sequence('subject','id'), (SELECT MAX(id) FROM subject))"
        );
        $this->entityManager->getConnection()->executeStatement(
            "SELECT setval(pg_get_serial_sequence('qualifier','id'), (SELECT MAX(id) FROM qualifier))"
        );
        $this->entityManager->getConnection()->executeStatement(
            "SELECT setval(pg_get_serial_sequence('nick','id'), (SELECT MAX(id) FROM nick))"
        );
    }
}
