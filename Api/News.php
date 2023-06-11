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

final class News
{
    public function __construct(
        public int $id,
        public ?string $title = null,
        public ?string $teaser = null,
        public ?array $content = null,
        public ?bool $enabled = null,
        public ?\DateTime $publishedAt = null,
        public ?string $route = null,
        public ?array $tags = null,
        public ?array $header = null,
        public ?\DateTime $authored = null,
        public ?\DateTime $created = null,
        public ?\DateTime $changed = null,
        public ?int $author = null,
        public ?string $ext = null
    ) {
    }
}
