{% extends 'layout.volt' %}
{% block body %}
{{ content() ~ flashSession.output() }}
<div class="pull-left">
    <h1>Categories</h1>
</div>
<div class="pull-right">
    <a class="btn btn-success" href="{{ url('category/add') }}" aria-label="Left Align">
        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> New
    </a>
</div>
<div class="clearfix"></div>
<hr>
<div class="panel panel-default">
    <div class="panel-body">
        <table class="table table-striped">
            <thead>
            <tr>
              <th>#</th>
              <th>Category</th>
              <th>Slug</th>
              <th>Created at</th>
              <th>Options</th>
            </tr>
            </thead>
            <tbody>
            {% for category in categories['items'] %}
            <tr>
              <th scope="row">{{ category['id'] }}</th>
              <td>{{ category['category_translations'][0]['category_translation_name'] }}</td>
              <td>{{ category['category_translations'][0]['category_translation_slug'] }}</td>
              <td>{{ category['category_created_at'] }}</td>
              <td>
                <a class="btn btn-default btn-xs" href="{{ url('category/edit/' ~ category['id']) }}" aria-label="Left Align">
                    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                </a>
                <a class="btn btn-danger btn-xs" href="{{ url('category/delete/' ~ category['id']) }}" aria-label="Left Align">
                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                </a>
              </td>
            </tr>
            {% else %}
            <tr>
                <td colspan="4">There are no categories in your database</td>
            </tr>
            {% endfor %}
            </tbody>
        </table>
    </div>
</div>
{% if (categories['total_pages'] > 1) %}
{% include 'common/paginator' with {'page_url' : url('category/list'), 'stack' : categories} %}
{% endif %}
{% endblock %}
