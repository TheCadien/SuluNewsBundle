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

class NewsCreatedActivityEvent extends DomainEvent
{
    public function __construct(
        private readonly News $news,
        private readonly array $payload
    ) {
        parent::__construct();
    }

    public function getEventType(): string
    {
        return 'created';
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
