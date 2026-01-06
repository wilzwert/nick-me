<?php

namespace App\Service\Data;

use App\Dto\Properties\MaintainWordProperties;
use App\Entity\Word;
use App\Exception\WordNotFoundException;
use App\Repository\WordRepositoryInterface;
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
        private readonly WordSluggerInterface $slugger,
    ) {
    }


    /**
     * @throws WordNotFoundException
     */
    public function createOrUpdate(MaintainWordProperties $spec): Word
    {
        $slug = $this->slugger->slug($spec->getLabel());
        if ($id = $spec->getWordId()) {
            $word = $this->wordRepository->findById($id);
            if (!$word) {
                throw new WordNotFoundException();
            }
        }
        else {
            $word = $this->wordRepository->findBySlug($slug);
        }

        $label = ucwords(strtolower($spec->getLabel()));

        if ($word) {
            $word->setSlug($slug);
            $word->setLabel($label);
            $word->setGender($spec->getGender());
            $word->setLang($spec->getLang());
            $word->setOffenseLevel($spec->getOffenseLevel());
            $word->setStatus($spec->getStatus());
        }
        else {
            $word = new Word(
                slug: $slug,
                label: $label,
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
