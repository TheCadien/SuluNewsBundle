<?php

declare(strict_types=1);

namespace App\Bundle\ArticleBundle\Admin;

use App\Bundle\ArticleBundle\Entity\Article;
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

class ArticleAdmin extends Admin
{
    const ARTICLE_SECURITY_CONTEXT = 'sulu.article';

    const IMAGE_LIST_KEY = 'articles';

    const IMAGE_FORM_KEY = 'article_details';

    const IMAGE_LIST_VIEW = 'app.articles_list';

    const IMAGE_ADD_FORM_VIEW = 'app.article_add_form';

    const IMAGE_EDIT_FORM_VIEW = 'app.article_edit_form';

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

    public function __construct(
        ViewBuilderFactoryInterface $viewBuilderFactory,
        WebspaceManagerInterface $webspaceManager,
        SecurityCheckerInterface $securityChecker
    )
    {
        $this->viewBuilderFactory = $viewBuilderFactory;
        $this->webspaceManager = $webspaceManager;
        $this->securityChecker = $securityChecker;
    }

    /**
     * @param NavigationItemCollection $navigationItemCollection
     */
    public function configureNavigationItems(NavigationItemCollection $navigationItemCollection): void
    {
        if ($this->securityChecker->hasPermission(static::ARTICLE_SECURITY_CONTEXT, PermissionTypes::VIEW)) {
            $module = new NavigationItem('app.article');
            $module->setPosition(20);
            $module->setIcon('su-newspaper');

            // Configure a NavigationItem with a View
            $events = new NavigationItem('app.article');
            $events->setPosition(10);
            $events->setView(static::IMAGE_LIST_VIEW);

            $module->addChild($events);

            $navigationItemCollection->add($module);
        }
    }

    /**
     * @param ViewCollection $viewCollection
     */
    public function configureViews(ViewCollection $viewCollection): void
    {
        $locales = $this->webspaceManager->getAllLocales();

        // Configure article List View
        $listToolbarActions = [new ToolbarAction('sulu_admin.add'), new ToolbarAction('sulu_admin.delete')];
        $listView = $this->viewBuilderFactory->createListViewBuilder(self::IMAGE_LIST_VIEW, '/articles/:locale')
            ->setResourceKey(Article::RESOURCE_KEY)
            ->setListKey(self::IMAGE_LIST_KEY)
            ->setTitle('app.article')
            ->addListAdapters(['table'])
            ->addLocales($locales)
            ->setDefaultLocale($locales[0])
            ->setAddView(static::IMAGE_ADD_FORM_VIEW)
            ->setEditView(static::IMAGE_EDIT_FORM_VIEW)
            ->addToolbarActions($listToolbarActions);
        $viewCollection->add($listView);


        $addFormView = $this->viewBuilderFactory->createResourceTabViewBuilder(self::IMAGE_ADD_FORM_VIEW, '/articles/:locale/add')
            ->setResourceKey(Article::RESOURCE_KEY)
            ->setBackView(static::IMAGE_LIST_VIEW)
            ->addLocales($locales);
        $viewCollection->add($addFormView);

        $addDetailsFormView = $this->viewBuilderFactory->createFormViewBuilder(self::IMAGE_ADD_FORM_VIEW . '.details', '/details')
            ->setResourceKey(Article::RESOURCE_KEY)
            ->setFormKey(self::IMAGE_FORM_KEY)
            ->setTabTitle('sulu_admin.details')
            ->setEditView(static::IMAGE_EDIT_FORM_VIEW)
            ->addToolbarActions([new ToolbarAction('sulu_admin.save')])
            ->setParent(static::IMAGE_ADD_FORM_VIEW);
        $viewCollection->add($addDetailsFormView);

        // Configure news Edit View
        $editFormView = $this->viewBuilderFactory->createResourceTabViewBuilder(static::IMAGE_EDIT_FORM_VIEW, '/articles/:locale/:id')
            ->setResourceKey(Article::RESOURCE_KEY)
            ->setBackView(static::IMAGE_LIST_VIEW)
            ->setTitleProperty('title')
            ->addLocales($locales);
        $viewCollection->add($editFormView);

        $formToolbarActions = [
            new ToolbarAction('sulu_admin.save'),
            new ToolbarAction('sulu_admin.delete'),
            new TogglerToolbarAction(
                'app.enable_article',
                'enabled',
                'enable',
                'disable'
            ),
        ];
        $editDetailsFormView = $this->viewBuilderFactory->createFormViewBuilder(static::IMAGE_EDIT_FORM_VIEW . '.details', '/details')
            ->setResourceKey(Article::RESOURCE_KEY)
            ->setFormKey(self::IMAGE_FORM_KEY)
            ->setTabTitle('sulu_admin.details')
            ->addToolbarActions($formToolbarActions)
            ->setParent(static::IMAGE_EDIT_FORM_VIEW);
        $viewCollection->add($editDetailsFormView);
    }

    /**
     * {@inheritdoc}
     */
    public function getSecurityContexts()
    {
        return [
            'Sulu' => [
                'Article' => [
                    static::ARTICLE_SECURITY_CONTEXT => [
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
        return 'sulu_article';
    }
}
