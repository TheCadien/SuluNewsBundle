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

namespace TheCadien\Bundle\SuluNewsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use TheCadien\Bundle\SuluNewsBundle\Entity\News;

/**
 * Class NewsWebsiteController.
 */
class NewsWebsiteController extends AbstractController
{
    public function indexAction(News $news): Response
    {
        if (!$news) {
            throw new NotFoundHttpException();
        }

        return $this->render('news/index.html.twig', ['news' => $news]);
    }
}
