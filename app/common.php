<?php

function head() {
    echo <<<'EOT'
<head>
        <meta charset="UTF-8">
        <meta name="author" content="Fitzwilliam College JCR">
        <meta name="title" content="Fitz JCR Housing Ballot System">
        <meta name="description" content="">
        <title>Fitz JCR Housing Ballot System</title>
        <!--<link href="include/img/icon.ico" rel="shortcut icon">-->
        <link href="include/css/bootstrap.min.css" rel="stylesheet">
        <link href="include/css/bootstrap-theme.min.css" rel="stylesheet">
        <link href="include/css/style.css" rel="stylesheet">
        <script src="include/js/jquery.min.js"></script>
        <script src="include/js/bootstrap.min.js"></script>
        <script src="include/js/markdown.js"></script>
        <script src="include/js/script.js.php"></script>
    </head>
EOT;
}

function bodyTop() {
    echo <<<'EOT'
<body>
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#nav-collapse">
                        <span class="sr-only">Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/"><img src="/img/logo.png"> Rooms</a>
                </div>
                <div id="nav-collapse" class="collapse navbar-collapse">
                    <ul id="pages" class="nav navbar-nav navbar-right" role="menu">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="news.php">News</a></li>
                        <li><a href="timetable.php">Ballot Timetable</a></li>
                        <li><a href="order.php">Ballot Order</a></li>
                        <li><a href="guide.php">Ballot Guide</a></li>
                        <li><a href="rooms.php">Rooms in the Ballot</a></li>
                        <li><a href="houses.php">College-owned Houses</a></li>
                        <li><a href="/login">Login with Raven</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div id="home" class="container-fluid page">
EOT;
}

function bodyBottom() {
    echo <<<'EOT'
        </div>
</body>
EOT;
}

?>