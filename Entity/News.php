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

namespace TheCadien\Bundle\SuluNewsBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation\Accessor;
use Sulu\Bundle\MediaBundle\Entity\MediaInterface;
use Sulu\Bundle\RouteBundle\Model\RoutableInterface;
use Sulu\Bundle\RouteBundle\Model\RouteInterface;
use Sulu\Bundle\TagBundle\Tag\TagInterface;
use Sulu\Component\Persistence\Model\AuditableInterface;

class News implements NewsInterface, AuditableInterface, RoutableInterface
{
    final public const RESOURCE_KEY = 'news';

    /**
     * @Accessor(getter="getTagNameArray")
     */
    protected $tags;

    private ?int $id = null;

    /**
     * @var MediaInterface
     */
    private $header;

    /**
     * @var ContactInterface
     */
    private $creator;

    /**
     * @var ContactInterface
     */
    private $changer;

    private bool $enabled = false;

    private ?string $title = null;

    private ?string $teaser = null;

    /**
     * @var string
     */
    private $content;

    private ?\DateTime $publishedAt = null;

    private ?\DateTime $created = null;

    private ?\DateTime $changed = null;

    private ?RouteInterface $route = null;

    private ?string $locale = null;

    /**
     * @var string
     */
    private $seo;

    /**
     * News constructor.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getContent()
    {
        return $this->content ?: [];
    }

    public function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * @return DateTime
     */
    public function getCreated(): ?DateTime
    {
        return $this->created;
    }

    public function setCreated(DateTime $created): void
    {
        $this->created = $created;
    }

    /**
     * @return DateTime
     */
    public function getPublishedAt(): ?DateTime
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(DateTime $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }

    public function getTeaser(): ?string
    {
        return $this->teaser;
    }

    public function setTeaser(string $teaser): void
    {
        $this->teaser = $teaser;
    }

    /**
     * @return mixed
     */
    public function getHeader()
    {
        return $this->header;
    }

    public function setHeader(mixed $header): void
    {
        $this->header = $header;
    }

    public function addTag(TagInterface $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    public function removeTag(TagInterface $tag): void
    {
        $this->tags->removeElement($tag);
    }

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function getTagNameArray(): array
    {
        $tags = [];

        if (null !== $this->getTags()) {
            foreach ($this->getTags() as $tag) {
                $tags[] = $tag->getName();
            }
        }

        return $tags;
    }

    /**
     * @return mixed
     */
    public function getCreator()
    {
        return $this->creator;
    }

    public function setCreator(mixed $creator): void
    {
        $this->creator = $creator;
    }

    /**
     * @return mixed
     */
    public function getchanger()
    {
        return $this->changer;
    }

    public function setchanger(mixed $changer): void
    {
        $this->changer = $changer;
    }

    public function getChanged(): DateTime
    {
        return $this->changed;
    }

    public function setChanged(DateTime $changed): void
    {
        $this->changed = $changed;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function setRoute(RouteInterface $route): void
    {
        $this->route = $route;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    public function getSeo()
    {
        return $this->seo;
    }

    public function setSeo($seo): void
    {
        $this->seo = $seo;
    }
}
