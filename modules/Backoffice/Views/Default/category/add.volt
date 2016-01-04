{% extends 'layout.volt' %}
{% block body %}
{{ content() }}
<h1>Add</h1>
<hr>
<div class="panel panel-default">
    <div class="panel-body">
        <form method="post" action="{{ url('category/create') }}">
            {% for locale, language in locales %}
            <h4>Category ({{ language }})</h4>
            <div class="form-group">
                <label for="category_name">Name</label>
                {{ form.render('translations['~locale~'][category_translation_name]', {'class':'form-control'}) }}
            </div>
            <div class="form-group">
                <label for="category_slug">Slug</label>
                {{ form.render('translations['~locale~'][category_translation_slug]', {'class':'form-control'}) }}
            </div>
            {{ form.render('translations['~locale~'][category_translation_lang]') }}
            {% endfor %}
            {{ form.render('save', {'value':'Save'}) }}
            {{ form.render('csrf', {'value':security.getToken()}) }}
        </form>
    </div>
</div>
{% endblock %}
