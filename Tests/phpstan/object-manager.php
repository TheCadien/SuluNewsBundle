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

use Symfony\Component\DependencyInjection\ContainerInterface;
use TheCadien\Bundle\SuluNewsBundle\Tests\Application\Kernel;

require \dirname(__DIR__) . '/bootstrap.php';

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG'], Kernel::CONTEXT_ADMIN);
$kernel->boot();

/** @var ContainerInterface $container */
$container = $kernel->getContainer();

return $container->get('doctrine')->getManager();
