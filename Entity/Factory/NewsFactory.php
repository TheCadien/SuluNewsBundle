<?php

declare(strict_types=1);

/*
 * This file is part of TheCadien/SuluNewsBundle.
 *
 * (c) Oliver Kossin
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TheCadien\Bundle\SuluNewsBundle\Entity\Factory;

use Sulu\Bundle\ContactBundle\Entity\ContactRepositoryInterface;
use Sulu\Component\Persistence\RelationTrait;
use TheCadien\Bundle\SuluNewsBundle\Entity\News;

class NewsFactory extends AbstractFactory implements NewsFactoryInterface
{
    use RelationTrait;

    private MediaFactoryInterface $mediaFactory;

    private TagFactoryInterface $tagFactory;

    private ContactRepositoryInterface $contactRepository;

    /**
     * NewsFactory constructor.
     */
    public function __construct(
        MediaFactoryInterface $mediaFactory,
        TagFactoryInterface $tagFactory,
        ContactRepositoryInterface $contactRepository
    ) {
        $this->mediaFactory = $mediaFactory;
        $this->tagFactory = $tagFactory;
        $this->contactRepository = $contactRepository;
    }

    /**
     * @param null|mixed $state
     *
     * @throws \Exception
     */
    public function generateNewsFromRequest(News $news, array $data, string $locale = null, $state = null): News
    {
        if ($this->getProperty($data, 'title')) {
            $news->setTitle($this->getProperty($data, 'title'));
        }

        if ($this->getProperty($data, 'teaser')) {
            $news->setTeaser($this->getProperty($data, 'teaser'));
        }

        if ($this->getProperty($data, 'header')) {
            $news->setHeader($this->mediaFactory->generateMedia($data['header']));
        }

        if ($this->getProperty($data, 'publishedAt')) {
            $news->setPublishedAt(new \DateTime($this->getProperty($data, 'publishedAt')));
        }

        if ($this->getProperty($data, 'content')) {
            $news->setContent($this->getProperty($data, 'content'));
        }

        if ($this->getProperty($data, 'ext')) {
            $news->setSeo($this->getProperty($data['ext'], 'seo'));
        }

        if ($tags = $this->getProperty($data, 'tags')) {
            $this->tagFactory->processTags($news, $tags);
        }

        if (!$news->getId()) {
            $news->setCreated(new \DateTime());
        }

        if ($locale) {
            $news->setLocale($locale);
        }

        if (null !== $state) {
            $news->setEnabled($state);
        }

        if ($authored = $this->getProperty($data, 'authored')) {
            $news->setCreated(new \DateTime($authored));
        }

        if ($author = $this->getProperty($data, 'author')) {
            // @var Contact $contact
            $news->setCreator($this->contactRepository->find($author));
        }

        return $news;
    }
}
