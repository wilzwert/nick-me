<?php

namespace App\DataFixtures;

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

class AppFixtures extends Fixture
{
    public function __construct(private EntityManagerInterface $manager)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $classMetadata = $this->manager->getClassMetadata(Word::class);
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

        // description of words, subjects and qualifiers to create
        $wordsToCreate = [
            ['id' => 1, 'slug' => 'coquin', 'label' => 'Coquin', 'gender' => WordGender::AUTO, 'asSubject' => true, 'asQualifier' => true],

            ['id' => 2, 'slug' => 'banane', 'label' => 'Banane', 'gender' => WordGender::F, 'asSubject' => true],
            ['id' => 3, 'slug' => 'camembert', 'label' => 'Camembert', 'gender' => WordGender::M, 'asSubject' => true],
            ['id' => 4, 'slug' => 'heretique', 'label' => 'Hérétique', 'gender' => WordGender::NEUTRAL, 'asSubject' => true],

            ['id' => 5, 'slug' => 'peureux', 'label' => 'Peureux', 'gender' => WordGender::AUTO, 'asQualifier' => true],
            ['id' => 6, 'slug' => 'indiscrete', 'label' => 'Indiscrète', 'gender' => WordGender::F, 'asSubject' => true],
            ['id' => 7, 'slug' => 'interrogateur', 'label' => 'Interrogateur', 'gender' => WordGender::M, 'asSubject' => true],
            ['id' => 8, 'slug' => 'fataliste', 'label' => 'Fataliste', 'gender' => WordGender::NEUTRAL, 'asSubject' => true],
        ];

        foreach ($wordsToCreate as $wordToCreate) {
            $word = new Word(
                slug: $wordToCreate['slug'],
                label: $wordToCreate['label'],
                gender: $wordToCreate['gender'],
                lang: $wordToCreate['lang'] ?? Lang::FR,
                offenseLevel: $wordToCreate['offenseLevel'] ?? OffenseLevel::MEDIUM,
                status: $wordToCreate['status'] ?? WordStatus::APPROVED
            );

            $wordIdReflectionProperty->setValue($word, $wordToCreate['id']);
            $manager->persist($word);

            if (!empty($wordToCreate['asSubject'])) {
                $subject = new Subject($word);
                $subjectIdReflectionProperty->setValue($subject, $wordToCreate['id']);
                $manager->persist($subject);
            }

            if (!empty($wordToCreate['asQualifier'])) {
                $qualifier = new Qualifier($word, $wordToCreate['qualifierPosition'] ?? QualifierPosition::AFTER);
                $qualifierIdReflectionProperty->setValue($qualifier, $wordToCreate['id']);
                $manager->persist($qualifier);
            }
        }

        $manager->flush();
    }
}
