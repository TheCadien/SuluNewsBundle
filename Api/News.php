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

namespace TheCadien\Bundle\SuluNewsBundle\Api;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;
use TheCadien\Bundle\SuluNewsBundle\Entity\News as NewsEntity;

#[ExclusionPolicy('all')]
class News
{
    public function __construct(private readonly NewsEntity $entity)
    {
    }

    #[SerializedName('id')]
    #[Groups(['fullNews'])]
    public function getId(): ?int
    {
        return $this->entity->getId();
    }

    #[SerializedName('title')]
    #[Groups(['fullNews'])]
    public function getTitle(): ?string
    {
        return $this->entity?->getTitle();
    }

    #[SerializedName('teaser')]
    #[Groups(['fullNews'])]
    public function getTeaser(): ?string
    {
        return $this->entity->getTeaser();
    }

    #[SerializedName('content')]
    #[Groups(['fullNews'])]
    public function getContent(): array
    {
        if (!$this->entity->getContent()) {
            return [];
        }

        return $this->entity->getContent();
    }

    #[SerializedName('enabled')]
    #[Groups(['fullNews'])]
    public function isEnabled(): bool
    {
        return $this->entity?->isEnabled();
    }

    #[SerializedName('publishedAt')]
    #[Groups(['fullNews'])]
    public function getPublishedAt(): ?\DateTime
    {
        return $this->entity?->getPublishedAt();
    }

    #[SerializedName('route')]
    #[Groups(['fullNews'])]
    public function getRoute(): string
    {
        if ($this->entity?->getRoute()) {
            return $this->entity->getRoute()?->getPath();
        }

        return '';
    }

    #[SerializedName('tags')]
    #[Groups(['fullNews'])]
    public function getTags(): array
    {
        return $this->entity->getTagNameArray();
    }

    #[SerializedName('header')]
    #[Groups(['fullNews'])]
    public function getHeader(): array
    {
        if ($this->entity->getHeader()) {
            return [
                'id' => $this->entity->getHeader()->getId(),
            ];
        }

        return [];
    }

    #[SerializedName('authored')]
    #[Groups(['fullNews'])]
    public function getAuthored(): \DateTime
    {
        return $this->entity->getCreated();
    }

    #[SerializedName('created')]
    #[Groups(['fullNews'])]
    public function getCreated(): \DateTime
    {
        return $this->entity->getCreated();
    }

    #[SerializedName('changed')]
    #[Groups(['fullNews'])]
    public function getChanged(): \DateTime
    {
        return $this->entity->getChanged();
    }

    #[SerializedName('author')]
    #[Groups(['fullNews'])]
    public function getAuthor(): ?int
    {
        return $this->entity?->getCreator()?->getId();
    }

    #[SerializedName('ext')]
    #[Groups(['fullNews'])]
    public function getSeo(): array
    {
        $seo = ['seo'];
        $seo['seo'] = $this->entity->getSeo();

        return $seo;
    }
}
