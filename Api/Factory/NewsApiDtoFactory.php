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
            route: $entity->getRoute()?->getPath(),
            tags: $entity->getTagNameArray(),
            header: [
                'id' => $entity->getHeader()?->getId(),
            ],
            authored: $entity->getCreated(),
            created: $entity->getCreated(),
            changed: $entity->getChanged(),
            author: $entity->getCreator()?->getId(),
            ext: $entity->getSeo()
        );
    }
}
