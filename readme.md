
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
        <img src="https://img.shields.io/badge/sulu%20compatibility-%3E=2.3-52b6ca.svg" alt="Sulu compatibility">
    </a>    
</p>


[![ezgif-5-d1dd7235da05.gif](https://i.postimg.cc/fTt3nZkh/ezgif-5-d1dd7235da05.gif)](https://postimg.cc/tYbRWKhr)


## Requirements

* PHP 8.0
* Sulu >=2.3.*
* Symfony >=4.3

## Features
* List view of News
* Preview
* Content Blocks (Title,Editor,Image,Quote)
* Activity Log


## Installation

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

### Update schema
```shell script
bin/console do:sch:up --force
```

## Bundle Config
    
Define the Admin Api Route in `routes_admin.yaml`
```yaml
sulu_news.admin:
  type: rest
  resource: sulu_news.rest.controller
  prefix: /admin/api
  name_prefix: app.
```

Configure your own public website controller with the name `sulu_news.controller` or use the default bundle controller as follows
 ```yaml
    sulu_news.controller:
      class: 'TheCadien\Bundle\SuluNewsBundle\Controller\NewsWebsiteController'
      public: 'true'
      tags: ['controller.service_arguments', {name: 'sulu.context', context: 'website'}]
 ```

After that, only a template needs to be defined.
If the bundle standard controller is used. A template must be created in `news/index.html.twig`.