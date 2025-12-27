<?php

namespace App\Service\Data;

use App\Entity\Word;
use App\Repository\WordRepositoryInterface;
use App\Specification\MaintainWordSpec;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @author Wilhelm Zwertvaegher
 */
class WordService implements WordServiceInterface
{
    public function __construct(
        private readonly WordRepositoryInterface $wordRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly SluggerInterface $slugger,
    ) {
    }


    public function createOrUpdate(MaintainWordSpec $spec): Word
    {
        $slug = strtolower($this->slugger->slug($spec->getLabel()));
        if ($id = $spec->getWordId()) {
            $word = $this->wordRepository->findById($id);
        }
        else {
            $word = $this->wordRepository->findBySlug($slug);
        }

        if ($word) {
            $word->setSlug($slug);
            $word->setLabel($spec->getLabel());
            $word->setGender($spec->getGender());
            $word->setLang($spec->getLang());
            $word->setOffenseLevel($spec->getOffenseLevel());
            $word->setStatus($spec->getStatus());
        }
        else {
            $word =new Word(
                slug: $slug,
                label: $spec->getLabel(),
                gender: $spec->getGender(),
                lang: $spec->getLang(),
                offenseLevel: $spec->getOffenseLevel(),
                status: $spec->getStatus()
            );
        }

        $this->save($word);
        return $word;
    }

    public function save(Word $word): void
    {
        $this->entityManager->persist($word);
    }
}
