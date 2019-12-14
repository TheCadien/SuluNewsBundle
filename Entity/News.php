<?php
namespace App\Bundle\NewsBundle\Entity;

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
 * @ORM\Entity(repositoryClass="App\Bundle\NewsBundle\Repository\NewsRepository")
 */
class News
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


}