<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{% block pageTitle %}Learning Phalcon{% endblock %}</title>

	{{ stylesheetLink('assets/default/bower_components/bootstrap/dist/css/bootstrap.min.css') }}
	{{ stylesheetLink('assets/css/lp.css') }}

</head>
<body>

	<div class="container">

	{% block body %}
	<h1>Main Layout</h1>
	{% endblock %}

	</div>

	{{ javascriptInclude('assets/default/bower_components/jquery/dist/jquery.min.js')}}	
	{{ javascriptInclude('assets/js/lp.js') }}
	{{ javascriptInclude('assets/default/bower_components/jquery/dist/jquery.min.js')}}
	{% block javascripts %} {% endblock %}
	
</body>
</html>