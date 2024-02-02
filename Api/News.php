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

use Symfony\Component\Validator\Constraints as Assert;

final class News
{
    public function __construct(
        #[Assert\NotBlank(groups: ['edit'])]
        public ?int $id,
        public string $title,
        public ?string $teaser,
        public ?array $content,
        public ?bool $enabled,
        public ?\DateTime $publishedAt = null,
        public ?string $route,
        public ?array $tags,
        public ?array $header,
        public ?\DateTime $authored = null,
        public ?\DateTime $created = null,
        public ?\DateTime $changed = null,
        public ?int $author,
        public ?string $ext,
        public ?string $locale = null
    ) {
    }
}
