<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{% block pageTitle %}Backoffice - Learning Phalcon{% endblock %}</title>
	
	{{ assets.outputCss('headerCss')}}
	{% block css %}{% endblock %}

</head>
<body>

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12 main">
				{% block body %}

				{% endblock %}
			</div>
		</div>
	</div>

	{{ assets.outputJs('footerJs')}}
	{% block javascripts %} {% endblock %}
	
</body>
</html>