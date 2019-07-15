<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>WP Defence</title>

    <link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__);?>css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__);?>css/font_awesome.css">
    

    <script src="<?php echo plugin_dir_url(__FILE__);?>js/jQuery3.3.1.js"></script>
    <script src="<?php echo plugin_dir_url(__FILE__);?>js/bootstrap.js"></script>

    
<style>
body {
    background: url(<?php echo plugin_dir_url(__FILE__);?>img/mrv0npqp.gif);
}
h1, h2 {
    font-family: Roboto;
    letter-spacing: 2px;
    word-spacing: 2px;
}   
</style>

</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="p-3 text-center bg-white mt-5 border shadow p-3 mb-5 bg-white rounded">
            
<!--- CONTENT --->
    <h2 class="p-3">
        <span class="">
            <i class="fa fa-lock text-success" aria-hidden="true"></i> Access denied.
        </span>
    </h2>
     <div>
        <i class="fa fa-desktop" aria-hidden="true"></i> <?php echo $_SERVER['REMOTE_ADDR']; ?>
    </div>           

<!--- /CONTENT --->

            </div>
        </div>
    </div>
</div>



</body>
</html>