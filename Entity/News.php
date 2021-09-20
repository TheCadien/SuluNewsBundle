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

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation\Accessor;
use Sulu\Bundle\ContentBundle\Content\Domain\Model\ContentRichEntityInterface;
use Sulu\Bundle\ContentBundle\Content\Domain\Model\DimensionContentInterface;
use Sulu\Bundle\MediaBundle\Entity\MediaInterface;
use Sulu\Bundle\RouteBundle\Model\RoutableInterface;
use Sulu\Bundle\RouteBundle\Model\RouteInterface;
use Sulu\Bundle\TagBundle\Tag\TagInterface;
use Sulu\Component\Persistence\Model\AuditableInterface;
use Sulu\Component\Security\Authentication\UserInterface;

class News implements NewsInterface, ContentRichEntityInterface, AuditableInterface, RoutableInterface
{
    public const RESOURCE_KEY = 'news';

    /**
     * @Accessor(getter="getTagNameArray")
     */
    protected $tags;

    /**
     * @var int
     */
    private $id;

    /**
     * @var MediaInterface
     */
    private $header;

    /**
     * @var UserInterface
     */
    private $creator;

    /**
     * @var UserInterface
     */
    private $changer;

    /**
     * @var bool
     */
    private $enabled;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $teaser;

    /**
     * @var string
     */
    private $content;

    /**
     * @var DateTime
     */
    private $publishedAt;

    /**
     * @var DateTime
     */
    private $created;

    /**
     * @var DateTime
     */
    private $changed;

    /**
     * @var RouteInterface
     */
    private $route;

    /**
     * @var string
     */
    private $locale;

    /**
     * News constructor.
     */
    public function __construct()
    {
        $this->enabled = false;
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
        if (!$this->content) {
            return [];
        }

        return $this->content;
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

    /**
     * @param mixed $header
     */
    public function setHeader($header): void
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
    public function getCreator(): UserInterface
    {
        return $this->creator;
    }

    /**
     * @param mixed $creator
     */
    public function setCreator($creator): void
    {
        $this->creator = $creator;
    }

    /**
     * @return mixed
     */
    public function getChanger(): UserInterface
    {
        return $this->changer;
    }

    /**
     * @param mixed $changer
     */
    public function setChanger($changer): void
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

    public function getDimensionContents(): Collection
    {
        // TODO: Implement getDimensionContents() method.
    }

    public function createDimensionContent(): DimensionContentInterface
    {
        // TODO: Implement createDimensionContent() method.
    }

    public function addDimensionContent(DimensionContentInterface $dimensionContent): void
    {
        // TODO: Implement addDimensionContent() method.
    }

    public function removeDimensionContent(DimensionContentInterface $dimensionContent): void
    {
        // TODO: Implement removeDimensionContent() method.
    }
}
