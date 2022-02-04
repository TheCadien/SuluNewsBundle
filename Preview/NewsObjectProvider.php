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

namespace TheCadien\Bundle\SuluNewsBundle\Preview;

use Sulu\Bundle\PreviewBundle\Preview\Object\PreviewObjectProviderInterface;
use TheCadien\Bundle\SuluNewsBundle\Admin\NewsAdmin;
use TheCadien\Bundle\SuluNewsBundle\Entity\News;
use TheCadien\Bundle\SuluNewsBundle\Repository\NewsRepository;

class NewsObjectProvider implements PreviewObjectProviderInterface
{
    /**
     * @var NewsRepository
     */
    private $newsRepository;

    public function __construct(NewsRepository $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    public function getObject($id, $locale): ?News
    {
        return $this->newsRepository->findById((int) $id);
    }

    public function getId($object): string
    {
        return $object->getId();
    }

    public function setValues($object, $locale, array $data): void
    {
        // TODO: Implement setValues() method.
    }

    public function setContext($object, $locale, array $context): News
    {
        if (\array_key_exists('template', $context)) {
            $object->setStructureType($context['template']);
        }

        return $object;
    }

    public function serialize($object): string
    {
        return serialize($object);
    }

    public function deserialize($serializedObject, $objectClass): News
    {
        return unserialize($serializedObject);
    }

    public function getSecurityContext($id, $locale): ?string
    {
        return NewsAdmin::SECURITY_CONTEXT;
    }
}
