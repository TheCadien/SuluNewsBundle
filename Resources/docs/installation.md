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

Configure your own public website controller with the name `sulu_news.controller` or use the default bundle controller as follows in your `service.yml`.
 ```yaml
    sulu_news.controller:
      class: 'TheCadien\Bundle\SuluNewsBundle\Controller\NewsWebsiteController'
      public: true
      tags: ['controller.service_arguments', {name: 'sulu.context', context: 'website'}]
 ```

After that, only a template needs to be defined.
If the bundle standard controller is used. A template must be created in `news/index.html.twig`.