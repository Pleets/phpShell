<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />

	<title>#!Shell - Pleets Apps</title>

	<!--Import materialize.css-->
	<link type="text/css" rel="stylesheet" href="public/libs/materialize/css/materialize.min.css"  media="screen,projection"/>

	<!-- jQuery -->
	<script type="text/javascript" src="public/libs/jquery-2.1.1/jquery-2.1.1.min.js" /></script>

	<!--Import materialize.js-->
	<script type="text/javascript" src="public/libs/materialize/js/materialize.min.js"></script>

	<!-- App styles -->
	<link rel="stylesheet" media="all" href="public/css/style.css" />

	<!-- App Scripts -->
	<script type="text/javascript" src="public/js/script.js" /></script>
</head>
<body>
	<nav>
		<div class="nav-wrapper">
			<a href="#" class="brand-logo">#!Shell</a>
			<ul id="nav-mobile" class="right hide-on-med-and-down">
				<li><a href="https://github.com/PleetsApps/PHP-FileSystem-Environment">GitHub</a></li>
			</ul>
		</div>
	</nav>

	<div class="container">
		<div class="shell">
			<div class="header">Terminal</div>
			<div class="body"></div>
			<div class="footer">
				<div class="row">
					<form class="col s12" id="frm-shell">
						<div class="row">
							<div class="input-field col s12">
								<!-- <i class="material-icons prefix">account_circle</i> -->
								<input name="command" id="command" type="text" class="validate" autofocus>
								<label for="icon_prefix">$</label>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>
</html>