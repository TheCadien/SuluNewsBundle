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

namespace TheCadien\Bundle\SuluNewsBundle\Entity;

use Sulu\Bundle\TagBundle\Tag\TagInterface;

interface NewsInterface
{
    public function getId(): ?int;

    public function setId(?int $id): void;

    public function isEnabled(): bool;

    public function setEnabled(bool $enabled): void;

    public function getTitle(): ?string;

    public function setTitle(string $title): void;

    public function getContent(): ?string;

    public function setContent(string $content): void;

    public function getCreated(): ?\DateTime;

    public function setCreated(\DateTime $created): void;

    public function getPublishedAt(): ?\DateTime;

    public function setPublishedAt(\DateTime $published_at): void;

    public function getTeaser(): string;

    public function setTeaser(string $teaser): void;

    public function getHeader();

    public function setHeader($header): void;

    public function addTag(TagInterface $tag);

    public function removeTag(TagInterface $tag);

    public function getTags();

    public function getTagNameArray();

    public function getCreator();

    public function setCreator($creator): void;

    public function getChanger();

    public function setChanger($changer): void;

    public function getChanged(): \DateTime;

    public function setChanged(\DateTime $changed): void;
}
