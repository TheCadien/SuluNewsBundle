
<p align="center">
    <a href="https://github.com/sulu/sulu/blob/master/LICENSE" target="_blank">
        <img src="https://img.shields.io/github/license/thecadien/sulunewsbundle?style=flat-square" alt="GitHub license">
    </a>
    <a href="https://github.com/sulu/sulu/releases" target="_blank">
        <img src="https://img.shields.io/github/v/tag/thecadien/sulunewsbundle?style=flat-square" alt="GitHub tag (latest SemVer)">
    </a>
    <a href="https://github.com/TheCadien/SuluNewsBundle/actions" target="_blank">
        <img src="https://img.shields.io/github/workflow/status/thecadien/sulunewsbundle/PHP?style=flat-square" alt="workflows">
    </a>    
</p>

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
app.news:
   path: /news/{id}
   controller: App\Bundle\NewsBundle\Controller\NewsWebsiteController::indexAction
```
   

   
###Define the Admin Api Route in `routes_admin.yaml`
```yaml
app_news:
  type: rest
  resource: App\Bundle\NewsBundle\Controller\Admin\NewsController
  prefix: /admin/api
  name_prefix: app.
  options:
    expose: true
```