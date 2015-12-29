{% extends 'layout.volt' %}
{% block body %}
{{ content() }}
<h1>Add</h1>
<hr>
<div class="panel panel-default">
    <div class="panel-body">
        <form method="post" action="{{ url('hashtag/create') }}">
            <div class="form-group">
                <label for="hashtag_name">Name</label>
                {{ form.render('hashtag_name', {'class':'form-control'}) }}
            </div>
            {# {{ security.getToken() }}<br>{{ security.getSessionToken() }} #}
            {{ form.render('save', {'value':'Add'}) }}
            {{ form.render('csrf', {'value':security.getToken()}) }}
        </form>
    </div>
</div>
{% endblock %}
