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

namespace TheCadien\Bundle\SuluNewsBundle\Entity\Factory;

use Sulu\Bundle\MediaBundle\Entity\Media;
use Sulu\Bundle\MediaBundle\Entity\MediaRepositoryInterface;
use Sulu\Component\Rest\Exception\EntityNotFoundException;

class MediaFactory extends AbstractFactory implements MediaFactoryInterface
{
    /**
     * NewsFactory constructor.
     */
    public function __construct(private readonly MediaRepositoryInterface $mediaRepository)
    {
    }

    /**
     * @param mixed $header
     *
     * @throws EntityNotFoundException
     */
    public function generateMedia($header): ?Media
    {
        $mediaEntity = null;
        if (\is_array($header) && $this->getProperty($header, 'id')) {
            $mediaId = $this->getProperty($header, 'id');
            $mediaEntity = $this->mediaRepository->findMediaById($mediaId);

            if (!$mediaEntity instanceof Media) {
                throw new EntityNotFoundException($this->mediaRepository->getClassName(), $mediaId);
            }
        }

        return $mediaEntity;
    }
}
