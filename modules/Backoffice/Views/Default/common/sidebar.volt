{% set controller_name = dispatcher.getControllerName() %}

<ul class="nav nav-sidebar">
	<li {% if controller_name == 'article' %} class="active" {% endif %}>
		<a href="{{ url('article/list') }}">Articles</a>
	</li>
	<li {% if controller_name == 'category' %} class="active" {% endif %}>
		<a href="{{ url('category/list') }}">Categories</a>
	</li>
	<li {% if controller_name == 'hashtag' %} class="active" {% endif %}>
		<a href="{{ url('hashtag/list') }}">Hashtags</a>
	</li>
	<li {% if controller_name == 'user' %} class="active" {% endif %}>
		<a href="{{ url('user/list') }}">Users</a>
	</li>
</ul>