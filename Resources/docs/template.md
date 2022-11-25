### News Template

If the bundles default controller is used, a template must be created in `news/index.html.twig`.

#### Example Template

This is an example template, covering all currently available content block types in one news item.

 ```twig
{% block content %}
    <h2>{{ news.title }}</h2>

    {% set header = sulu_resolve_media(news.header.id, 'de') %}
    <img src="{{ header.thumbnails['sulu-260x'] }}" alt="{{ header.title }}" title="{{ header.title }}" />

    <p>{{ news.teaser }}</p>
    
    {% for contentItem in news.content  %}
        {% if contentItem.type == 'editor'  %}
            <p>{{ contentItem.text | raw }}</p>
        {% elseif contentItem.type == 'title'  %}
            <{{ contentItem.titleType }}>{{ contentItem.title }}</{{ contentItem.titleType }}>
        {% elseif contentItem.type == 'image'  %}
            {% set img = sulu_resolve_media(contentItem.image.id, 'de') %}
            <img src="{{ img.thumbnails['sulu-260x'] }}" alt="" />
        {% elseif contentItem.type == 'quote'  %}
            <figure>
                <blockquote><p>{{ contentItem.quote }}</p></blockquote>
                <figcaption>{{ contentItem.quoteReference }}</figcaption>
            </figure>
        {% endif %}
    {% endfor %}
{% endblock %}
 ```
