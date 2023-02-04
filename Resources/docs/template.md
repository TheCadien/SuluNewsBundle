### News Template

#### Template.xml 

To link the news in the frontend there are two ways of integration, Smart Content and the News Selection.

##### Smart Content
The smart content type is used to display a list of news items it loads the latest published news items by default.

The smart content can be configured in every `template.xml` file.

 ```xml
        <property name="news" type="smart_content">
            <meta>
                <title lang="en">Latest News</title>
            </meta>

            <params>
                <param name="provider" value="news"/>
                <param name="max_per_page" value="5"/>
                <param name="page_parameter" value="p"/>
            </params>
        </property>

```

Follow the Official [Smart Content Documentation](https://docs.sulu.io/en/latest/cookbook/smart-content.html) to learn more about the smart content.

##### News Selection
The news selection is used to display a specific list of news items.

The smart content can be configured in every `template.xml` file.

 ```xml
        <property name="news" type="news_selection">
            <meta>
                <title lang="en">News</title>
            </meta>
        </property>
```

#### Twig Template

If the bundles default controller is used, a template must be created in `news/index.html.twig`.

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
    <aside>
        <small>Published at {{ news.publishedAt | date() }} by {{ sulu_resolve_user(news.creator.id).contact.fullName }}</small>
    </aside>
{% endblock %}
 ```
