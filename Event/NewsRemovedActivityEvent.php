<?php


namespace TheCadien\Bundle\SuluNewsBundle\Event;

use Sulu\Bundle\ActivityBundle\Domain\Event\DomainEvent;
use TheCadien\Bundle\SuluNewsBundle\Admin\NewsAdmin;
use TheCadien\Bundle\SuluNewsBundle\Entity\News;


class NewsRemovedActivityEvent extends DomainEvent
{

    /**
     * @var News
     */
    private $news;


    private $payload;

    /**
     * @param News $book
     */
    public function __construct(
        News $book,
        array $payload
    )
    {
        parent::__construct();

        $this->news = $book;
        $this->payload = $payload;
    }

    public function getEventType(): string
    {
        return 'removed';
    }

    public function getResourceKey(): string
    {
        return News::RESOURCE_KEY;
    }

    public function getResourceId(): string
    {
        return (string)$this->news->getId();
    }

    public function getEventPayload(): ?array
    {
        return $this->payload;
    }

    public function getResourceTitle(): ?string
    {
        return $this->news->getTitle();
    }

    public function getResourceSecurityContext(): ?string
    {
        return NewsAdmin::SECURITY_CONTEXT;
    }
}