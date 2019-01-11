<!doctype html>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Відеоблог  </title>
<link rel="stylesheet" href="http://diary.yy/wp-content/themes/lifemap/css/bootstrap4.2.1.css" />
<style>
    .my-video { max-width: 640px;}
</style>
</head>
<body>
<?php

    // Підключаю парсер:
    require "lib/simple_html_dom.php";
 
    $content = '
    <p>
        <iframe src="https://www.youtube.com/embed/IrbhajhcABI" width="640" height="480" frameborder="0" allowfullscreen="allowfullscreen"></iframe>
    </p>
    <p>
        <iframe src="https://www.youtube.com/embed/v16A-6Sy8ww" width="640" height="420" frameborder="0" allowfullscreen="allowfullscreen"></iframe>
    </p>
    
    <p>
    <iframe src="https://www.youtube.com/embed/tjscmX4NRIY" width="640" height="480" frameborder="0" allowfullscreen="allowfullscreen"><span data-mce-type="bookmark" style="display: inline-block; width: 0px; overflow: hidden; line-height: 0;" class="mce_SELRES_start"></span></iframe>
    </p>
    <p><iframe src="https://www.youtube.com/embed/CpCVqAVvNf0" width="640" height="360" frameborder="0" allowfullscreen="allowfullscreen"></iframe></p>
    ';
 
    // Паршу:
    $html = str_get_html($content);
    
    $ret = $html->find('iframe');
    
    
    
    foreach ($ret as $key => $value) {
        echo '
        
        <div class="embed-responsive embed-responsive-16by9 my-video">'.$value->outertext.'</div>
        
        ';
    }

