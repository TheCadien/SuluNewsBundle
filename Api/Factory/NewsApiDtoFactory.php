<?php

namespace TheCadien\Bundle\SuluNewsBundle\Api\Factory;

use TheCadien\Bundle\SuluNewsBundle\Api\News;
use TheCadien\Bundle\SuluNewsBundle\Entity\News as NewsEntity;

final class NewsApiDtoFactory
{
    public function generate(NewsEntity $entity): News
    {
        return new News(
            $entity->getId(),
            $entity->getTitle(),
            $entity->getTeaser(),
            $entity->getContent(),
            $entity->isEnabled(),
            $entity->getPublishedAt(),
            $entity->getRoute()?->getPath(),
            $entity->getTagNameArray(),
            [
                'id' => $entity->getHeader()?->getId(),
            ],
            $entity->getCreated(),
            $entity->getCreated(),
            $entity->getChanged(),
            $entity->getCreator()?->getId(),
            $entity->getSeo()
        );
    }
}
