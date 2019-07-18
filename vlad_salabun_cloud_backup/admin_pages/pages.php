<?php

add_action( 'admin_menu', 'cloud_backup_page' );

// 
function cloud_backup_page(){
	add_menu_page( 
        'Автобекапи', 
        'Автобекапи', 
        'edit_others_posts', 
        'cloud_backup', 
        'cloud_backup_function', 
        '',
        100 
    );  // cloud_backup
}

function cloud_backup_function() {
   
    global $wpdb;
    global $cloud_backup_tables;
    
    $config_table = $wpdb->get_blog_prefix().$cloud_backup_tables['config'];
    $backups_table = $wpdb->get_blog_prefix().$cloud_backup_tables['backups'];

    $myrows = $wpdb->get_results("SELECT * FROM $backups_table 
    WHERE deleted_time is NULL ORDER BY id DESC");
    
    echo '<h2>'.get_admin_page_title().'</h2>';
    
    echo '
<style>
table {
  border-collapse: collapse;
}

table, th, td {
  border: 1px solid #ccc;
}
</style>
';
    
        echo '<table cellspacing="0" cellpadding="5" style="width: 90%;">
        <th>#</th>
        <th>cloud_name</th>
        <th>files_size</th>
        <th>files_time</th>
        <th>db_size</th>
        <th>db_time</th>
        <th>download_db</th>
        <th>download_files</th>
        <th>backup_date</th>
        ';
    
    foreach ($myrows as $key => $value) {
?>
    <tr>
        <td><?php echo $value->id; ?></td>
        <td><?php echo $value->cloud_name; ?></td>
        <td><?php if($value->files_backup_size > 0) {echo round($value->files_backup_size / 1024 / 1024, 2).' МБ'; } ?></td>
        <td><?php echo $value->files_backup_time; ?> сек.</td>
        <td><?php echo round($value->db_backup_size / 1024 / 1024, 2); ?> МБ</td>
        <td><?php echo $value->db_backup_time; ?> сек.</td>
        <td><?php if($value->download_db_link != null) {?>
            <a href="<?php echo $value->download_db_link; ?>" target="_blank">db_download</a>
            <?php }?>
        </td>
        <td><?php if($value->download_files_link != null) {?>
            <a href="<?php echo $value->download_files_link; ?>" target="_blank">files_download</a>
            <?php }?>
        </td>
        <td><?php echo date('Y-m-d H:i', $value->backup_date); ?></td>
    </tr>
<?php
    }
    
     echo '</table>';
     
     echo '<p>Пагінація?</p>';
     
     
} // - кінець сторінки



// Добавим подменю в меню админ-панели "Инструменты" (tools):
add_action('admin_menu', 'cloud_backup_config_page');

function cloud_backup_config_page() {
	add_submenu_page( 
        'cloud_backup', 
        'Налаштування хмар', 
        'Налаштування хмар', 
        'manage_options', 
        'cloud_backup_config', // url 
        'cloud_backup_config_page_function'
    ); 
}

function cloud_backup_config_page_function() {
	
    global $wpdb;
    global $cloud_backup_tables;
    
    echo '<h2>'.get_admin_page_title().'</h2>';
    
    echo '
<style>

th, td {
    padding: 7px;
}
tr {

}
table {
  border-collapse: collapse;
  background: #f5f5e9;
  border: 1px solid #F9F8E9;
  padding: 5px;
}
</style>
';
    
    echo '<div style="width: 100%; overflow: hidden;">';
    
    $config_table = $wpdb->get_blog_prefix().$cloud_backup_tables['config'];
    $backups_table = $wpdb->get_blog_prefix().$cloud_backup_tables['backups'];
    $clouds_table = $wpdb->get_blog_prefix().$cloud_backup_tables['clouds'];

    // Беру всі хмари:
    $myrows = $wpdb->get_results("SELECT * FROM $clouds_table");
    
    // ПРОГРАМА БЕКАПУВАННЯ:
    foreach ($myrows as $key => $cloud) {
        
         echo '
         <form autocomplete="off" id="freeze-form" action="admin-post.php" method="POST">
         <input type="hidden" name="action" value="update_cloud_backup_options">
         <input type="hidden" name="cloud_name" value="'.$cloud->cloud_name.'">
         <table  style="float: left; margin: 5px;">
        <th>'.$cloud->cloud_name.'</th><th>Налаштування:</th><th></th>';
        $cloud->backup_frequency
?>
    <tr>
        <td>Частота бекапа:</td>
        <td><input type="number" name="backup_frequency" value="<?php echo $cloud->backup_frequency; ?>"></td>
        <td>днів</td>
    </tr>
    <tr>
        <td>Кількість бекапів:</td>
        <td><input type="number" name="simultaneously_stored_quantity" value="<?php echo $cloud->simultaneously_stored_quantity; ?>"></td>
        <td>шт</td>
    </tr>
    <tr>
        <td>Бекапи бази:</td>
        <td><input type="number" name="db_switch" value="<?php echo $cloud->db_switch; ?>"></td>
        <td><a href="">Do!</a></td>
    </tr>
    <tr>
        <td>Бекапи файлів:</td>
        <td><input type="number" name="files_switch" value="<?php echo $cloud->files_switch; ?>"></td>
        <td><a href="">Do!</a></td>
    </tr>
    <tr>
        <td>Токен:</td>
        <td><input type="text" name="token" value="<?php echo $cloud->token; ?>"></td>
        <td></td>
    </tr>
    <tr>
        <td>Логін:</td>
        <td><input type="text" name="login" value="<?php echo $cloud->login; ?>"></td>
        <td></td>
    </tr>
    <tr>
        <td>Пароль:</td>
        <td><input type="password" name="password" value="<?php echo $cloud->password; ?>"></td>
        <td></td>
    </tr>
    <tr>
        <td><a href="">Перевірка</a></td>
        <td><input type="submit" name="submit" value="Зберегти"></td>
        <td></td>
    </tr>
<?php
        
        echo '</table></form>';
        
    }

echo '</div>
<div>
<hr>
<h2>Зміна пароля архівів:</h2>
     <form autocomplete="off" id="freeze-form" action="admin-post.php" method="POST">
     <input type="hidden" name="action" value="update_cloud_backup_password">
<table cellspacing="0" cellpadding="5" style="float: left; margin: 5px;">
    <tr>
        <td>Пароль:</td>
        <td><input type="password" name="password" value="'.get_cloud_backup_config('download_password').'"></td>
        <td><input type="submit" name="submit" value="Зберегти"></td>
    </tr>
 </table>
     </form>
         
         ';

echo '</div>';

    // TODO: загрузка в Яндекс и Гугл - це на потім!


}











// Добавим подменю в меню админ-панели "Инструменты" (tools):
add_action('admin_menu', 'cloud_backup_testing_page');

function cloud_backup_testing_page() {
	add_submenu_page( 
        'cloud_backup', 
        'Тестування хмари', 
        'Тестування хмари', 
        'manage_options', 
        'cloud_backup_testing', // url 
        'cloud_backup_test_function'
    ); 
}

function cloud_backup_test_function() {
	
    global $wpdb;
    global $cloud_backup_tables;
    
     $config_table = $wpdb->get_blog_prefix().$cloud_backup_tables['config'];
    
    echo '<h2>Testing:</h2>';



    // TODO: загрузка в Яндекс и Гугл - це на потім!


}
