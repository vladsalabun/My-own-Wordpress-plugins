<?php

add_action( 'admin_menu', 'freeze_activity_page' );

// 
function freeze_activity_page(){
	add_menu_page( 
        'Лог активності', 
        'Лог активності', 
        'edit_others_posts', 
        'freeze_activity', 
        'freeze_activity_function', 
        '',
        100 
    ); 
}

function freeze_activity_function() {
   
    global $wpdb;
    global $freeze_activity_config_table;
    global $freeze_activity_log_table;

    $freeze_activity_config_table = $wpdb->get_blog_prefix().'freeze_activity_config';
    $freeze_activity_log_table = $wpdb->get_blog_prefix().'freeze_activity_log';
    
    $cookie = $_COOKIE['frozen_cookie'];

    // Видаляю логи поведінки і логи спроб введення пароля:
    $reuslt = $wpdb->get_results("SELECT DISTINCT frozen_cookie, ip, user_id FROM $freeze_activity_log_table");
   
    echo '<h2>'. get_admin_page_title() .'</h2>';
   
    if(count($reuslt) > 0) { 
   
        echo '<table cellspacing="0" cellpadding="5" width="700">
        <th>#</th>
        <th>cookie</th>
        <th>ip</th>
        <th>user_id</th>
        <th>last_activity</th>
        <th>status</th>
        ';
   
        $max_time_without_activity = get_freeze_activity_config('max_time_without_activity');
        $current_time = current_time('timestamp'); 
   
        foreach ($reuslt as $key => $value) {
            
            $myrows = $wpdb->get_results("SELECT * FROM $freeze_activity_log_table 
            WHERE frozen_cookie = '".$value->frozen_cookie."' AND user_id = '".$value->user_id."' AND password = 'log' ORDER BY id DESC LIMIT 1");
            
            //  Дізнаюсь час останнього логу:
            if(count($myrows) > 0) {
                $last_activity = date('H:i:s d/m/Y', $myrows[0]->time);
            } else {
                $last_activity = '';
            }
            
            // Визначаю поточний девайс:
            if( $cookie == $value->frozen_cookie) {
                $tr_style = 'background-color: #D8EFC1;';
            } else {
                $tr_style = '';
            }
            
            // Дізнаюсь які девайси вже відключені:
            if($current_time > $myrows[0]->time + $max_time_without_activity) {
                $status = '<font color="red">Не активний</font>';
            } else {
                $status = '<font color="green">Активний</font>';
            }
?> 
    <tr style="<?php echo $tr_style; ?>">
        <td width="10px"><?php echo ($key + 1); ?></td>
        <td width="60px"><?php echo $value->frozen_cookie; ?></td>
        <td width="60px"><?php echo $value->ip; ?></td>
        <td width="60px"><?php echo $value->user_id; ?></td>
        <td width="60px"><?php echo $last_activity; ?></td>
        <td width="60px"><?php echo $status; ?></td>
    </tr>
    
<?php       
       
        }
   
        echo '</table>';
   
    }
    
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
   
}





// Добавим подменю в меню админ-панели "Инструменты" (tools):
add_action('admin_menu', 'freeze_activity_config_page');

function freeze_activity_config_page() {
	add_submenu_page( 
        'freeze_activity', 
        'Налаштування логу активності', 
        'Налаштування', 
        'manage_options', 
        'freeze_activity_config', 
        'freeze_activity_config_function'
    ); 
}

function freeze_activity_config_function() {
	// контент страницы
	echo '<h2>'. get_admin_page_title() .'</h2>';
?>
    <form autocomplete="off" id="freeze-form" action="admin-post.php" method="POST">
        <input type="hidden" name="action" value="update_freeze_options">
        
<hr>  
<table>
    <tr>
        <td width="230px">Максимальний час неактивності: </td>
        <td width="20px"><input type="number" name="max_time_without_activity" min="1" value="<?php echo get_freeze_activity_config('max_time_without_activity'); ?>"></td>
        <td width="30px">секунд</td>
    </tr>
    <tr>
        <td width="230px">Максимальна кількість спроб: </td>
        <td width="20px"><input type="number" name="max_unlock_password_try" min="1" value="<?php echo get_freeze_activity_config('max_unlock_password_try'); ?>"></td>
        <td width="30px">шт</td>
    </tr>
    <tr>
        <td width="230px">Скільки логів зберігати: </td>
        <td width="20px"><input type="number" name="max_log_lines_count" min="1" value="<?php echo get_freeze_activity_config('max_log_lines_count'); ?>"></td>
        <td width="30px">шт</td>
    </tr>
    <tr>
        <td width="230px">Пароль доступу:</td>
        <td width="20px"><input type="password" name="password" placeholder="Current password" maxlength="12" required></td>
        <td width="30px"></td>
    </tr>
</table>

<hr>
<table>
    <tr>
        <td width="230px">Новий пароль:</td>
        <td width="20px"><input type="password" name="password1" placeholder="New password" maxlength="12"></td>
        <td width="30px"></td>
    </tr>
    <tr>
        <td width="230px">Підтвердження паролю:</td>
        <td width="20px"><input type="password" name="password2" placeholder="Password again" maxlength="12"></td>
        <td width="30px"></td>
    </tr>
</table>
        
<hr>  
<table>
    <tr>
        <td width="230px"></td>
        <td width="20px"></td>
        <td width="30px">
            <input type="submit" name="submit" class="btn btn-success" id="freeze-form-btn" value="Зберегти">
        </td>
    </tr>    
</table>
        
      
    </form> 
<?php
}