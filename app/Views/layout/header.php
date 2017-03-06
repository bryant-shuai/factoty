<html>
<head>
    <title>那记猪手-在线订货</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="/libs/bootstrap/css/bootstrap.min.css">

    <script src="/libs/jquery.js"></script>
    <script src="/libs/bootstrap/js/bootstrap.min.js"></script>
    <script src="/libs/md5.min.js"></script>
    <script src="/js/main.js"></script>
</head>
<body>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">在线订货</a>
            <span class="navbar-brand">
<?php
$client = $para["client"];
if (isset($client['deposit'])) {
    echo "当前店铺: " . $client['storename'];
} else {
    echo "未登录";
}
?>
            </span>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="/wechat/charge">充值</a></li>
                <li><a href="#">关于</a></li>
            </ul>
        </div>
    </div>
</nav>
