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
    App\Bundle\NewsBundle\NewsBundle::class => ['all' => true],
```

###Update schema
```shell script
bin/console do:sch:up --force
```


###Define Website Route in `routes_website.yaml`
```yaml
app.article:
   path: /news/{id}
   controller: App\Bundle\NewsBundle\Controller\NewsWebsiteController::indexAction
```
   

   
###Define the Admin Api Route in `routes_admin.yaml`
```yaml
app_articles:
  type: rest
  resource: App\Bundle\NewsBundle\Controller\Admin\NewsController
  prefix: /admin/api
  name_prefix: app.
  options:
    expose: true
```