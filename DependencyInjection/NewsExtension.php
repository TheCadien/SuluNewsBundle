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

namespace TheCadien\Bundle\SuluNewsBundle\DependencyInjection;

use Sulu\Bundle\PersistenceBundle\DependencyInjection\PersistenceExtensionTrait;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use TheCadien\Bundle\SuluNewsBundle\Admin\NewsAdmin;
use TheCadien\Bundle\SuluNewsBundle\Entity\News;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class NewsExtension extends Extension implements PrependExtensionInterface
{
    use PersistenceExtensionTrait;

    /**
     * Allow an extension to prepend the extension configurations.
     */
    public function prepend(ContainerBuilder $container): void
    {
        if ($container->hasExtension('sulu_search')) {
            $container->prependExtensionConfig(
                'sulu_search',
                [
                    'indexes' => [
                        'news' => [
                            'name' => 'News',
                            'icon' => 'su-newspaper',
                            'view' => [
                                'name' => NewsAdmin::NEWS_EDIT_FORM_VIEW,
                                'result_to_view' => [
                                    'id' => 'id',
                                    'locale' => 'locale',
                                ],
                            ],
                            'security_context' => NewsAdmin::SECURITY_CONTEXT,
                        ],
                    ],
                ]
            );
        }

        if ($container->hasExtension('sulu_route')) {
            $container->prependExtensionConfig(
                'sulu_route',
                [
                    'mappings' => [
                        News::class => [
                            'generator' => 'schema',
                            'options' => ['route_schema' => '/news/{object.getId()}'],
                            'resource_key' => News::RESOURCE_KEY,
                        ],
                    ],
                ]
            );
        }

        if ($container->hasExtension('sulu_admin')) {
            $container->prependExtensionConfig(
                'sulu_admin',
                [
                    'lists' => [
                        'directories' => [
                            __DIR__.'/../Resources/config/lists',
                        ],
                    ],
                    'forms' => [
                        'directories' => [
                            __DIR__.'/../Resources/config/forms',
                        ],
                    ],
                    'resources' => [
                        'news' => [
                            'routes' => [
                                'list' => 'app.get_news',
                                'detail' => 'app.get_news',
                            ],
                        ],
                    ],
                    'field_type_options' => [
                        'selection' => [
                            'news_selection' => [
                                'default_type' => 'list_overlay',
                                'resource_key' => News::RESOURCE_KEY,
                                'view' => [
                                    'name' => 'app.news_edit_form',
                                    'result_to_view' => [
                                        'id' => 'id',
                                    ],
                                ],
                                'types' => [
                                    'auto_complete' => [
                                        'display_property' => 'title',
                                        'search_properties' => ['title'],
                                    ],
                                    'list_overlay' => [
                                        'adapter' => 'table',
                                        'list_key' => 'news',
                                        'display_properties' => ['title'],
                                        'label' => 'sulu_news.news_select',
                                        'icon' => 'su-newspaper',
                                        'overlay_title' => 'sulu_news.single_news_selection_overlay_title',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]
            );
        }

        $container->prependExtensionConfig(
            'sulu_news',
            ['templates' => ['view' => 'news/index.html.twig']]
        );

        $container->loadFromExtension('framework', [
            'default_locale' => 'en',
            'translator' => ['paths' => [__DIR__.'/../Resources/config/translations/']],
            // ...
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        $loader->load('controller.xml');

        $this->configurePersistence($config['objects'], $container);
    }
}
