### Twig-Extensions

#### sulu_resolve_news

Returns news for given id.

 ```php
{% set news = sulu_resolve_news('1') %}
{{ news.title }}
 ```

Arguments:

    id: int - The id of requested news.

Returns:

    object - Object with all needed properties, like title
