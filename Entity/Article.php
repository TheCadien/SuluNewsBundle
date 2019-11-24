<?php
namespace App\Bundle\ArticleBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping\JoinColumn;
use Sulu\Bundle\TagBundle\Tag\TagInterface;

/**
 * @ORM\Entity(repositoryClass="App\Bundle\ArticleBundle\Repository\ArticleRepository")
 */
class Article
{
    const RESOURCE_KEY = 'articles';

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
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $enabled;


    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
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
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $published_at;

    /**
     * @var Collection|TagInterface[]
     *
     * @ManyToMany(targetEntity="Sulu\Bundle\TagBundle\Tag\TagInterface")
     * @JoinTable(name="article_tags",
     *      joinColumns={@JoinColumn(name="article_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="idTags", referencedColumnName="id")}
     *      )
     *
     * @Accessor(getter="getTagNameArray")
     */
    protected $tags;


    /**
     * Article constructor.
     */
    public function __construct()
    {
        $this->enabled  = false;
        $this->tags = new ArrayCollection();

    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
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

    /**
     * @param string $title
     */
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

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getPublishedAt(): ?\DateTime
    {
        return $this->published_at;
    }

    /**
     * @param \DateTime $published_at
     */
    public function setPublishedAt(\DateTime $published_at): void
    {
        $this->published_at = $published_at;
    }


    static function createEmptyArticle() : Article
    {
        //return new Article();
    }

    /**
     * @return string
     */
    public function getTeaser(): string
    {
        return $this->teaser;
    }

    /**
     * @param string $teaser
     */
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

    /**
     * {@inheritdoc}
     */
    public function addTag(TagInterface $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeTag(TagInterface $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * {@inheritdoc}
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * {@inheritdoc}
     */
    public function getTagNameArray()
    {
        $tags = [];

        if (!is_null($this->getTags())) {
            foreach ($this->getTags() as $tag) {
                $tags[] = $tag->getName();
            }
        }

        return $tags;
    }


}
