

# NewsBundle 2.0.0

### routes_admin.yaml

Change the following routing Config in your `routes_admin.yaml`
```diff
sulu_news.admin:
- resource: sulu_news.rest.controller
+ resource: "@NewsBundle/Resources/config/routes_admin.yaml"
  prefix: /admin/api
```
