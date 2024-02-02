<?php

declare(strict_types=1);

/*
 * This file is part of TheCadien/SuluNewsBundle.
 *
 * by Oliver Kossin and contributors.
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TheCadien\Bundle\SuluNewsBundle\Entity\Factory;

use Sulu\Bundle\ContactBundle\Entity\ContactRepositoryInterface;
use Sulu\Component\Persistence\RelationTrait;
use TheCadien\Bundle\SuluNewsBundle\Api\News as NewsApi;
use TheCadien\Bundle\SuluNewsBundle\Entity\News;

class NewsFactory implements NewsFactoryInterface
{
    use RelationTrait;

    /**
     * NewsFactory constructor.
     */
    public function __construct(private readonly MediaFactoryInterface $mediaFactory, private readonly TagFactoryInterface $tagFactory, private readonly ContactRepositoryInterface $contactRepository)
    {
    }

    /**
     * @param mixed|null $state
     *
     * @throws \Exception
     */
    public function generateNewsFromRequest(News $news, NewsApi $data, string $locale = null, $state = null): News
    {
        $news->setTitle($data->title);
        $news->setTeaser($data->teaser);
        $news->setHeader($data->header);
        $news->setPublishedAt($data->publishedAt);
        $news->setContent($data->content);
        $news->setSeo($data->ext);

        if ($data->tags) {
            $this->tagFactory->processTags($news, $data->tags);
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

        if ($data->authored) {
            $news->setCreated($data->authored);
        }

        if ($data->author) {
            $news->setCreator($this->contactRepository->find($data->author));
        }

        return $news;
    }
}
