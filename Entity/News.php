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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\Groups;
use Sulu\Bundle\TagBundle\Tag\TagInterface;
use Sulu\Component\Persistence\Model\AuditableInterface;

/**
 * @ORM\Entity(repositoryClass="TheCadien\Bundle\SuluNewsBundle\Repository\NewsRepository")
 */
class News implements NewsInterface, AuditableInterface
{
    const RESOURCE_KEY = 'news';

    /**
     * @var int|null
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"partialMedia"})
     * @ORM\ManyToOne(targetEntity="Sulu\Bundle\MediaBundle\Entity\MediaInterface")
     * @ORM\JoinColumn(name="header_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $header;

    /**
     * @Groups({"partialMedia"})
     * @ORM\ManyToOne(targetEntity="Sulu\Component\Security\Authentication\UserInterface")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $creator;

    /**
     * @Groups({"partialMedia"})
     * @ORM\ManyToOne(targetEntity="Sulu\Component\Security\Authentication\UserInterface")
     * @ORM\JoinColumn(name="changer_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $changer;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $enabled;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255 , nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     */
    private $teaser;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=false)
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $published_at;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $changed;

    /**
     * @var Collection|TagInterface[]
     *
     * @ManyToMany(targetEntity="Sulu\Bundle\TagBundle\Tag\TagInterface")
     * @JoinTable(name="news_tags",
     *      joinColumns={@JoinColumn(name="news_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="idTags", referencedColumnName="id")}
     *      )
     *
     * @Accessor(getter="getTagNameArray")
     */
    protected $tags;

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

    /**
     * @return string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return \DateTime
     */
    public function getCreated(): ?\DateTime
    {
        return $this->created;
    }

    public function setCreated(\DateTime $created): void
    {
        $this->created = $created;
    }

    /**
     * @return \DateTime
     */
    public function getPublishedAt(): ?\DateTime
    {
        return $this->published_at;
    }

    public function setPublishedAt(\DateTime $published_at): void
    {
        $this->published_at = $published_at;
    }

    public function getTeaser(): string
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

    public function removeTag(TagInterface $tag)
    {
        $this->tags->removeElement($tag);
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function getTagNameArray()
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
    public function getChanger()
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

    public function getChanged(): \DateTime
    {
        return $this->changed;
    }

    public function setChanged(\DateTime $changed): void
    {
        $this->changed = $changed;
    }
}
