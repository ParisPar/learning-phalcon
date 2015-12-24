<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{% block pageTitle %}Backoffice - Learning Phalcon{% endblock %}</title>
	
	{{ stylesheetLink('../assets/default/bower_components/bootstrap/dist/css/bootstrap.min.css') }}
	
	{{ assets.outputCss('headerCss')}}
	{% block css %}{% endblock %}

</head>
<body>

	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Learning Phalcon</a>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
				<ul class="nav navbar-nav navbar-right">
					<li><a href="#">Sign out</a></li>
				</ul>
			</div>
		</div>
	</nav>

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-3 col-md-2 sidebar">
				<ul class="nav nav-sidebar">
					<li class="active"><a href="{{ url('article/list') }}">Articles <span class="sr-only">(current)</span></a></li>
					<li><a href="#">Other menu item</a></li>
				</ul>
			</div>
			<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
				{% block body %}
				<h1 class="page-header">Dashboard</h1>
				<h2 class="sub-header">Section title</h2>
				<div class="table-responsive">

				</div>
				{% endblock %}
			</div>
		</div>
	</div>

	{{ assets.outputJs('footerJs')}}
	{% block javascripts %} {% endblock %}
	
</body>
</html>