<?php

require_once "lib/Michelf/MarkdownInterface.php";
require_once "lib/Michelf/Markdown.php";
require_once "Database.php";

class Layout {
	static function HTMLheader($pageTitle) {
?><!DOCTYPE html>
<html lang="en-GB">
	<head>
		<meta charset="UTF-8">
		<meta name="author" content="Fitzwilliam College JCR">
		<meta name="title" content="<?php echo $pageTitle; ?>">
		<meta name="description" content="">
		<title><?php echo $pageTitle; ?></title>
		<!--<link href="include/img/icon.ico" rel="shortcut icon">-->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	</head>

	<body>
<?php
	}

	static function HTMLnavbar() {
?>
		<!-- Fixed navbar -->
		<nav class="navbar navbar-default">
			<div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Fitz JCR Housing Ballot System</a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			  	<ul class="nav navbar-nav navbar-right">
					<li><a href="#news">Latest News</a></li>
					<li><a href="#timetable">Ballot Timetable</a></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Ballot Order <span class="caret"></span></a>
					  	<ul class="dropdown-menu">
							<li><a href="#order">Order of the Ballot</a></li>
							<li><a href="#shaddow">Shaddow Ballot</a></li>
					  	</ul>
					</li>
					<li><a href="#guide">Ballot Guide</a></li>
					<li><a href="#rooms">Rooms in the Ballot</a></li>
					<li><a href="#houses">Houses in the Ballot</a></li>
				</ul>
			</div><!-- /.navbar-collapse -->
		  </div><!-- /.container-fluid -->
		</nav>
<?php
	}

	static function HTMLcontent($heading, $text) {
?>

		<div class="container">
			<div class="page-header">
				<h1><?php echo $heading; ?></h1>
			</div>
			<?php echo Markdown::defaultTransform($text); ?>
		</div>
<?php
	}

	static function HTMLfooter() {
?>

		<footer class="footer">
			<div class="container">
				<p class="text-muted">DEVELOPMENT VERSION.</p>
			</div>
		</footer>
	</body>
</html>
<?php
	}
}
?>
