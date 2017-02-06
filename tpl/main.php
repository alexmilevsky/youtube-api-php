<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>YouTube Search</title>
    <link rel="stylesheet" href="tpl/css/reset.css">
    <link rel="stylesheet" href="tpl/css/style.css">
    <link rel="stylesheet" href="tpl/css/media.css">
</head>
<body>
<section id="search">
    <div class="container">
        <div id="buttons">
            <form action="search.php" method="GET">
                <div>
                    <input type="search" id="query" name="q" placeholder="введите название">
                </div>
                <input type="submit" id="search-button" value="Искать">
            </form>
        </div>
        <h1 id="search-header"><?=$search_text?></h1>
        <div id="search-container">
            <ul id="search-cointainer-list"><?=$videos?></ul>
        </div>
    </div>
</section>
<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="tpl/js/accordion.js"></script>
</body>
</html>
