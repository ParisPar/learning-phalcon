{% extends 'layout.volt' %}
{% block body %}
	{% for article in articles %}
		<li>
			<a href="{{ url('article/' ~ article.getArticleSlug()) }}">{{ article.getArticleShortTitle() }}</a>
		</li>
	{% endfor %}
{% endblock %}