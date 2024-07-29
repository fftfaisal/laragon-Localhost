<?php
$DomainPostFix = 'test';

if (!empty($_GET['q'])) {

    switch ($_GET['q']) {
        case 'info':
            phpinfo();
            exit;
            break;
    }

}

function stringToColor($string)
{
    // random color
    $rgb = substr(dechex(crc32($string)), 0, 6);
    // make it darker
    $darker = 1;
    list($R16, $G16, $B16) = str_split($rgb, 2);
    $R = sprintf('%02X', floor(hexdec($R16) / $darker));
    $G = sprintf('%02X', floor(hexdec($G16) / $darker));
    $B = sprintf('%02X', floor(hexdec($B16) / $darker));
    return '#' . $R . $G . $B;
}

function mysqlVersion()
{
    $mysql = new mysqli('localhost', 'root', '');

    return $mysql->server_info;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Laragon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Inter&display=swap" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        html, body {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            display: table;
            font-weight: normal;
            font-family: Inter, serif;
            background-color: #ebebeb;
        }

        .content {
            margin-top: 100px;
            margin-bottom: 100px;
        }
        .title {
            font-size: 40px;
            font-weight: 500;
            display: inline-block;
        }

        a:hover {
            color: red;
        }

        .Directories a {
            text-decoration: none;
            color: #000;
            padding: 15px 20px;
            display: inline-block;
            background-color: #f0f0f0;
            border-radius: 12px;
            width: 100%;
            margin: 5px 0;
            text-align: left;
        }

        .Directories a:focus, .Directories a:hover {
            box-shadow: 0 0 10px 0 #ddd;
            background-color: #f4f4f4;
        }

        .Directories a span {
            display: inline-block;
            background-color: #444;
            color: #fff;
            font-weight: bold;
            border-radius: 50px;
            width: 30px;
            height: 30px;
            margin-right: 3px;
            margin-bottom: -4px;
            font-size: 21px;
            text-align: center;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg fixed-top bg-body-tertiary">
    <div class="container">
        <a class="navbar-brand" href="#">
            <strong>Laragon</strong>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="http://localhost">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                       aria-expanded="false">
                        Tools
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="http://localhost/phpmyadmin/">PhpMyAdmin</a></li>
                        <li><a class="dropdown-item" href="http://localhost/?q=info">PHP Info</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="http://localhost/redis/?overview">Redis WA</a></li>
                        <li><a class="dropdown-item" href="http://localhost/memcached/">Memecached WA</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" aria-disabled="true">PHP: <?php print phpversion(); ?></a>
                </li>

            </ul>
            <form class="d-flex" role="search">
                <input class="form-control me-2 search" tabindex="1" type="text" id="search"
                       placeholder="Type To search..." aria-label="Search">
            </form>
        </div>
    </div>
</nav>
<div class="container">
    <div class="content">
        <div class="card mb-5">
            <div class="card-body text-center">
                <?php print($_SERVER['SERVER_SOFTWARE']); ?><br/>
                PHP version: <?php print phpversion(); ?> <span><a title="phpinfo()" href="/?q=info">info</a></span><br/>
                Mysql version: <?php print mysqlVersion(); ?><br/>
                Document Root: <?php print($_SERVER['DOCUMENT_ROOT']); ?><br/>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="Directories row">
                    <?php
                    $Directories = glob(dirname(__FILE__) . '/*', GLOB_ONLYDIR);
                    $temp        = [];

                    foreach ($Directories as $key => $value) {
                        $temp[] = basename($value);
                    }

                    natcasesort($temp);

                    foreach ($temp as $Dir) {

                        if (basename($Dir) == 'bootstrap-5.3.1') {
                            continue;
                        }

                        $FirstChar = strtoupper(substr($Dir, 0, 1));
                        echo '<div class="col-md-3"><a href="https://' . basename($Dir) . '.' . $DomainPostFix . '"><span style="background-color:' . stringToColor(strtolower($FirstChar)) . '">' . $FirstChar . '</span>' . basename($Dir) . '</a></div>';
                    }

                    ?>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="footer mt-5 bg-white p-3 fixed-bottom">
    <div class="container">
        <div class="text-center">
            <p class="text-muted mb-0">&copy; <?php echo date('Y');?> Faisal Ahmed. All rights reserved</p>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("#search").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $(".Directories a").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
</body>
</html>
