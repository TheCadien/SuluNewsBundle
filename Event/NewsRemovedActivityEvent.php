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

namespace TheCadien\Bundle\SuluNewsBundle\Event;

use Sulu\Bundle\ActivityBundle\Domain\Event\DomainEvent;
use TheCadien\Bundle\SuluNewsBundle\Admin\NewsAdmin;
use TheCadien\Bundle\SuluNewsBundle\Entity\News;

class NewsRemovedActivityEvent extends DomainEvent
{
    private News $news;

    private array $payload;

    public function __construct(
        News $book,
        array $payload
    ) {
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
        return (string) $this->news->getId();
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
