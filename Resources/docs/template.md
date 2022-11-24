### Twig-Extensions

If the bundle default controller is used. A template must be created in `news/index.html.twig`.

#### Example template
 ```php
{% block content %}
<h2>{{ news.title }}</h2>

    {% set header = sulu_resolve_media(news.header.id, 'de') %}
    <img src="{{ header.thumbnails['sulu-260x'] }}" alt="{{ header.title }}" title="{{ header.title }}" />

    <p>{{ news.teaser }}</p>
    
    {% for contentItem in news.content  %}
        {% if contentItem.type == 'editor'  %}
            <p>{{ contentItem.text | raw }}</p>
        {% endif %}
    {% endfor %}
{% endblock %}
 ```
