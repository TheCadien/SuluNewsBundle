##Requirements

* PHP 7.3
* Sulu 2.0.*
* Symfony 4.3

##Installation

Clone the Bundle in your Project.
`
/src/Bundle/
`
 
 
###ini Bundle in the bundles.php
```php
    App\Bundle\ArticleBundle\ArticleBundle::class => ['all' => true],
```

###Init Servies
 copy content of `Resources/services.yml`  to `/config/services.yaml`

###Update schema
```shell script
bin/console do:sch:up --force
```


###Define Website Route in `routes_website.yaml`
```yaml
app.article:
   path: /article/{id}
   controller: App\Bundle\ArticleBundle\Controller\ArticleWebsiteController::indexAction
```
   

   
###Define the Admin Api Route in `routes_admin.yaml`
```yaml
app_articles:
  type: rest
  resource: App\Bundle\ArticleBundle\Controller\Admin\ArticleController
  prefix: /admin/api
  name_prefix: app.
  options:
    expose: true
```