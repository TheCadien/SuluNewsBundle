
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
    <a href="https://github.com/sulu/sulu/releases" target="_blank">
        <img src="https://img.shields.io/badge/sulu%20compatibility-%3E=2.0-52b6ca.svg" alt="Sulu compatibility">
    </a>    
</p>

##Requirements

* PHP 7.3
* Sulu 2.0.*
* Symfony 4.3

##Installation

### Install the bundle 

Execute the following [composer](https://getcomposer.org/) command to add the bundle to the dependencies of your 
project:

```bash

composer require thecadien/sulu-news-bundle

```

### Enable the bundle 
 
 Enable the bundle by adding it to the list of registered bundles in the `config/bundles.php` file of your project:
 
 ```php
 return [
     /* ... */
     TheCadien\Bundle\SuluNewsBundle\NewsBundle::class => ['all' => true],
 ];
 ```

###Update schema
```shell script
bin/console do:sch:up --force
```

## Bundle Config

Define key routes in `config/packages/sulu_admin.yml` 

```yaml
sulu_admin:
    #.....#
    resources:
        news:
            routes:
                list: app.get_news
                detail: app.get_news
```

Define Website Route in `routes_website.yaml`
```yaml
app.news:
   path: /news/{id}
   controller: TheCadien\Bundle\SuluNewsBundle\Controller\NewsWebsiteController::indexAction
```
    
Define the Admin Api Route in `routes_admin.yaml`
```yaml
app_news:
  type: rest
  resource: sulu_news.rest.controller
  prefix: /admin/api
  name_prefix: app.
```