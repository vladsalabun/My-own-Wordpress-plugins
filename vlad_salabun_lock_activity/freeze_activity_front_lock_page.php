<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Freeze Lock</title>

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
            <i class="fa fa-lock text-success" aria-hidden="true"></i> Your activity is frozen
        </span>
    </h2>
     <div>
        <i class="fa fa-desktop" aria-hidden="true"></i> <?php echo $_SERVER['REMOTE_ADDR']; ?>
    </div>           
            
<div class="mt-3 bg-light pt-3 pb-4">

    <div class="row mt-5">
        <div class="col-sm-12 col-md-3 col-lg-3 col-xl-4">
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
        
    <form autocomplete="off" id="freeze-form">
        <input type="hidden" name="action" value="freeze_handler">
        <div class="form-group">
            <input type="password" class="form-control" id="frozen-password-input" name="password" placeholder="Password" maxlength="12" required>
        
            <small id="emailHelp" class="form-text text-muted">
    <?php 

        echo 'Attempts: <span id="attempt_count">'.$frozen_tries.'</span>';

    ?>
            </small>
        </div>
      <input type="submit" name="submit" class="btn btn-success" id="freeze-form-btn" value="Unlock?">
    </form>

        </div>
        <div class="col-sm-12 col-md-3 col-lg-3 col-xl-4">
        </div>
    </div>
 
</div>    
    
<div class="row mt-3">
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <small><b>Last activity:</b> <?php echo date('H:i:s d/m/Y', $last_activity_time); ?></small>
    </div>
</div>

<!--- /CONTENT --->

            </div>
        </div>
    </div>
</div>

<script>

    // Клік на об'єкт:
    $('body').on('click', '#freeze-form-btn', function() {
        
        var frozenPassword = $('#frozen-password-input').val();
        
            if(frozenPassword.length > 0) {
            
            // Відправляю дані:
            $.ajax({
                type: 'post',
                url: '<?php echo get_site_url() ; ?>/wp-admin/admin-ajax.php',
                data: $('#freeze-form').serialize(),
            })
            .done (function (data) {
                console.log(data);
                if( data == 'access granted') {
                    // reload
                    location.reload();
                } else if ( data == 'access denied') {
                    location.reload();
                }
                else {
                    $('#frozen-password-input').val('');
                    $('#attempt_count').text('');
                    $('#attempt_count').text(data);
                }
            })
            .fail (function () {
            });
            
        }
        
        return false;
    });

</script>

</body>
</html>