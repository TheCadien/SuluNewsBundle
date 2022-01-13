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

namespace TheCadien\Bundle\SuluNewsBundle\Admin;

use Sulu\Bundle\ActivityBundle\Infrastructure\Sulu\Admin\View\ActivityViewBuilderFactoryInterface;
use Sulu\Bundle\AdminBundle\Admin\Admin;
use Sulu\Bundle\AdminBundle\Admin\Navigation\NavigationItem;
use Sulu\Bundle\AdminBundle\Admin\Navigation\NavigationItemCollection;
use Sulu\Bundle\AdminBundle\Admin\View\TogglerToolbarAction;
use Sulu\Bundle\AdminBundle\Admin\View\ToolbarAction;
use Sulu\Bundle\AdminBundle\Admin\View\ViewBuilderFactoryInterface;
use Sulu\Bundle\AdminBundle\Admin\View\ViewCollection;
use Sulu\Component\Security\Authorization\PermissionTypes;
use Sulu\Component\Security\Authorization\SecurityCheckerInterface;
use Sulu\Component\Webspace\Manager\WebspaceManagerInterface;
use TheCadien\Bundle\SuluNewsBundle\Entity\News;

class NewsAdmin extends Admin
{
    public const SECURITY_CONTEXT = 'sulu.news';

    public const NEWS_LIST_KEY = 'news';

    public const NEWS_FORM_KEY_ADD = 'news_details_add';

    public const NEWS_FORM_KEY_EDIT = 'news_details_edit';

    public const NEWS_LIST_VIEW = 'app.news_list';

    public const NEWS_ADD_FORM_VIEW = 'app.news_add_form';

    public const NEWS_EDIT_FORM_VIEW = 'app.news_edit_form';

    public const NEWS_FORM_KEY_SETTINGS = 'news_settings';

    /**
     * @var ViewBuilderFactoryInterface
     */
    private $viewBuilderFactory;

    /**
     * @var WebspaceManagerInterface
     */
    private $webspaceManager;
    /**
     * @var SecurityCheckerInterface
     */
    private $securityChecker;

    /**
     * @var ActivityViewBuilderFactoryInterface
     */
    private $activityViewBuilderFactory;

    /**
     * ArticleAdmin constructor.
     */
    public function __construct(
        ViewBuilderFactoryInterface $viewBuilderFactory,
        WebspaceManagerInterface $webspaceManager,
        SecurityCheckerInterface $securityChecker,
        ActivityViewBuilderFactoryInterface $activityViewBuilderFactory,
    ) {
        $this->viewBuilderFactory = $viewBuilderFactory;
        $this->webspaceManager = $webspaceManager;
        $this->securityChecker = $securityChecker;
        $this->activityViewBuilderFactory = $activityViewBuilderFactory;
    }

    public function configureNavigationItems(NavigationItemCollection $navigationItemCollection): void
    {
        if ($this->securityChecker->hasPermission(static::SECURITY_CONTEXT, PermissionTypes::VIEW)) {
            $module = new NavigationItem('sulu.news');
            $module->setPosition(20);
            $module->setIcon('su-newspaper');
            $module->setView(static::NEWS_LIST_VIEW);

            $navigationItemCollection->add($module);
        }
    }

    public function configureViews(ViewCollection $viewCollection): void
    {
        $locales = $this->webspaceManager->getAllLocales();

        // Configure news List View
        $listToolbarActions = [new ToolbarAction('sulu_admin.add'), new ToolbarAction('sulu_admin.delete')];
        $listView = $this->viewBuilderFactory->createListViewBuilder(self::NEWS_LIST_VIEW, '/news/:locale')
            ->setResourceKey(News::RESOURCE_KEY)
            ->setListKey(self::NEWS_LIST_KEY)
            ->setTitle('sulu.news')
            ->addListAdapters(['table'])
            ->addLocales($locales)
            ->setDefaultLocale($locales[0])
            ->setAddView(static::NEWS_ADD_FORM_VIEW)
            ->setEditView(static::NEWS_EDIT_FORM_VIEW)
            ->addToolbarActions($listToolbarActions)
        ;
        $viewCollection->add($listView);

        $addFormView = $this->viewBuilderFactory->createResourceTabViewBuilder(self::NEWS_ADD_FORM_VIEW, '/news/:locale/add')
            ->setResourceKey(News::RESOURCE_KEY)
            ->setBackView(static::NEWS_LIST_VIEW)
            ->addLocales($locales)
        ;
        $viewCollection->add($addFormView);

        $addDetailsFormView = $this->viewBuilderFactory->createFormViewBuilder(self::NEWS_ADD_FORM_VIEW.'.details', '/details')
            ->setResourceKey(News::RESOURCE_KEY)
            ->setFormKey(self::NEWS_FORM_KEY_ADD)
            ->setTabTitle('sulu_admin.details')
            ->setEditView(static::NEWS_EDIT_FORM_VIEW)
            ->addToolbarActions([new ToolbarAction('sulu_admin.save')])
            ->setParent(static::NEWS_ADD_FORM_VIEW)
        ;
        $viewCollection->add($addDetailsFormView);

        // Configure news Edit View
        $editFormView = $this->viewBuilderFactory->createResourceTabViewBuilder(static::NEWS_EDIT_FORM_VIEW, '/news/:locale/:id')
            ->setResourceKey(News::RESOURCE_KEY)
            ->setBackView(static::NEWS_LIST_VIEW)
            ->setTitleProperty('title')
            ->addLocales($locales)
        ;
        $viewCollection->add($editFormView);

        $formToolbarActions = [
            new ToolbarAction('sulu_admin.save'),
            new ToolbarAction('sulu_admin.delete'),
            new TogglerToolbarAction(
                'sulu.news.enable_news',
                'enabled',
                'enable',
                'disable'
            ),
        ];

        $viewCollection->add(
            $this->viewBuilderFactory->createPreviewFormViewBuilder(static::NEWS_EDIT_FORM_VIEW.'.details', '/details')
                ->setResourceKey(News::RESOURCE_KEY)
                ->setFormKey(self::NEWS_FORM_KEY_EDIT)
                ->setTabTitle('sulu_admin.details')
                ->addToolbarActions($formToolbarActions)
                ->setParent(static::NEWS_EDIT_FORM_VIEW)
        );

        $viewCollection->add(
            $this->viewBuilderFactory->createPreviewFormViewBuilder(static::NEWS_EDIT_FORM_VIEW.'.details_settings', '/details-settings')
                ->setResourceKey(News::RESOURCE_KEY)
                ->setFormKey(self::NEWS_FORM_KEY_SETTINGS)
                ->setTabTitle('sulu_admin.settings')
                ->addToolbarActions($formToolbarActions)
                ->setParent(static::NEWS_EDIT_FORM_VIEW)
        );
        if ($this->activityViewBuilderFactory->hasActivityListPermission()) {
            $viewCollection->add(
                $this->activityViewBuilderFactory
                    ->createActivityListViewBuilder(
                        static::NEWS_EDIT_FORM_VIEW.'.activity',
                        '/activity',
                        News::RESOURCE_KEY
                    )
                    ->setParent(static::NEWS_EDIT_FORM_VIEW)
            );
        }

        $viewCollection->add(
            $this->viewBuilderFactory
                ->createPreviewFormViewBuilder(static::SOCIAL_POST_EDIT_FORM_VIEW.'.details_seo', '/seo')
                ->disablePreviewWebspaceChooser()
                ->setResourceKey(News::RESOURCE_KEY)
                ->setFormKey('news_seo')
                ->setTabTitle('sulu_page.seo')
                ->addToolbarActions($formToolbarActions)
                ->setTitleVisible(true)
                ->setTabOrder(2048)
                ->setParent(static::NEWS_EDIT_FORM_VIEW)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getSecurityContexts()
    {
        return [
            'Sulu' => [
                'News' => [
                    static::SECURITY_CONTEXT => [
                        PermissionTypes::VIEW,
                        PermissionTypes::ADD,
                        PermissionTypes::EDIT,
                        PermissionTypes::DELETE,
                    ],
                ],
            ],
        ];
    }

    public function getConfigKey(): ?string
    {
        return 'sulu_news';
    }
}
