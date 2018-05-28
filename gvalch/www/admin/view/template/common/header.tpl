<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>
    <?php if($description){ ?>
    <meta name="description" content="<?php echo $description; ?>">
    <?php } ?>
    <?php if($keywords){ ?>
    <meta name="keywords" content="<?php echo $keywords?>">
    <?php } ?>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php foreach($links as $link) { ?>
    <link rel="<?php echo $link['rel']; ?>" href="<?php echo $link['href']; ?>" />
    <?php } ?>
    <?php foreach($styles as $style) { ?>
    <link type="text/stylesheet" rel="<?php echo $style['rel']; ?>" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>"/>
    <?php } ?>
    <link type="text/stylesheet" rel="stylesheet" href="admin/view/css/main.css" media="all"/>

    <script src="/admin/view/js/jquery.js"></script>

    <!--<meta name="author" content="Ulvbern">
    <link rel="shortcut icon" href="img/favicon-icon.ico" type="image/x-icon">
    <link rel="icon" type="image/png" href="img/favicon.png">
    <link rel="apple-touch-icon" href="img/favicon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="img/favicon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="img/favicon-114x114.png">-->
</head>
<body>
    <div class="container">
