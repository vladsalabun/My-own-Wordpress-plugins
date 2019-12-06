<?php
/*
    Plugin Name: LM Activity
    Description: Графічне відображення кількості публікацій у блозі по дням за рік (додав кеш)
    Version: 1.4
    Author: Vlad Salabun
    Author URI: http://salabun.com/
*/

    /*
        Щоб відобразити активність блога, створи нову публікацію з шорткодом: [blogActivity year=2018]
        Якщо не вказати рік, то буде взятий поточний.
        
        TODO:
        1) В які години я зазвичай публікую пости?
    
    */
    
    


    // Підключення хоткеїв для фронт-енду:
    add_action( 'wp_enqueue_scripts', 'lm_activity_script_and_styles' );
    
    // Підключення скриптів у шапку сайта:
    function lm_activity_script_and_styles() {
        wp_enqueue_script( 'js_popper', plugin_dir_url( __FILE__ ) . 'js_popper.js');
        wp_enqueue_script( 'js_activity', plugin_dir_url( __FILE__ ) . 'js_activity.js');
        wp_enqueue_style( 'css_activity', plugin_dir_url( __FILE__ ) . 'css_activity.css' );
    }

    // Створюю шорткод:
    add_shortcode('blogActivity','newActivityShortcode');
    
    function newActivityShortcode($atts) {
        echo show_my_activity($atts['year']);
    }
    
    
   
    function show_my_activity($startYear = null) {

        // 6 грудня 2019 р.
        // пробуем получить кэш и вернем его если он есть
        $cache_key = 'showMyActivity_'.$startYear.'_Cache';
        if( $cachedString = getVladCache($cache_key) ) {
            return $cachedString;
        }
    
    
        if($startYear) {
            $currentYear = $startYear;
        } else {
            $currentYear = date('Y');
        }
        
        // Запит постів по даті:
        $params = array(
            'date_query' => array(
                array(
                    // починаючи з цієї дати:
                    'after'     => array(
                        'year'  => $currentYear,
                        'month' => 01,
                        'day'   => 1,
                    ),
                    // закінчуючи цією датою:
                    'before'    => array(
                        'year'  => ($currentYear + 1),
                        'month' => 01,
                        'day'   => 1,
                    ),
                    'inclusive'=> true, // чи включати крайні періоди
                    //'fields' => 'ID,post_date,post_title'
                )
            ),
            'posts_per_page' => -1,
        );
        
        $dateq = new WP_Query( $params );
        
        $yearDayNumber = array();
        
        $allPostsArray = $dateq->posts;
        
        // Підрахунок кількості постів:
        foreach ($allPostsArray as $id => $value) {

            $time = strtotime($value->post_date);
            $newformat = date('Y-m-d',$time);
            
            // Якщо є пости за сьогоднішнє число минулих років:
            if($newformat === date($currentYear.'-m-d')) {
                // то беру їх:
                $lastYearPosts[] = '<a href="'.get_permalink($value->ID).'">'.$value->post_title.'</a> / '.$value->post_date;
            }
            
            $dayNumber = date('z',mktime(0,0,0,date('m',$time),date('d',$time),date('Y',$time)));
            
            if(isset($yearDayNumber[$dayNumber])) {
                $yearDayNumber[$dayNumber] += 1;
            } else {
                $yearDayNumber[$dayNumber] = 1; // кількість починається з 1
            } 
            
        }
        
        // Кількість днів у році:
        $daysCount = daysInYear($currentYear);
        
        // Кількість тижнів у році:
        $weeks = ceil($daysCount / 7);
        
        // Перший день:
        $realDay = 0;
        
        $string = '
            <div id="activityBlog">  
            <b>'.$currentYear.'</b> - <b>'.($currentYear + 1).'</b><br>
        ';
        
        // Виведення статистики:
        for ($i = 1; $i <= $weeks; $i++) {
            $string .= '<div class="weekCol">';
            for ($wd = 1; $wd <= 7; $wd++) {
                
                $dateString = date('d-m-Y', strtotime("+".$realDay." day", strtotime("01-01-".$currentYear)));
                $monthString = date('m', strtotime("+".$realDay." day", strtotime("01-01-".$currentYear)));
                $dayString = date('d', strtotime("+".$realDay." day", strtotime("01-01-".$currentYear)));
                
                if($daysCount > $realDay) {
                    
                    if(isset($yearDayNumber[$realDay])){
                        $c = $yearDayNumber[$realDay];
                        
                        if($c > 40) {
                            $countLevel = 'countLevelMax';
                        } else {
                            $countLevel = 'countLevel'.$c;
                        }
                        $string .= '<a href="http://diary.yy/archives/date/'.$currentYear.'/'.$monthString.'/'.$dayString.'">
                            <div class="weekCell '.$countLevel.'" title="Дописів: '.$c.' / '.$dateString.'" data-toggle="tooltip" data-placement="top"></div>
                        </a>';
                    } else {
                        $string .= '<a href="http://diary.yy/archives/date/'.$currentYear.'/'.$monthString.'/'.$dayString.'">
                            <div class="weekCell countLevel" title="" data-toggle="tooltip" data-placement="top"></div>
                        </a>';
                    }

                }
                $realDay++;
            }
            $string .= '</div>';
        }
        
        $string .= '</div>';
        
        // Якщо є публікації в цей день за минулі роки:
        if(isset($lastYearPosts)) {
            if(count($lastYearPosts) > 0) {
                
                $string .= '<h2>Публікації цього дня за '.$currentYear.' рік:</h2><ol>';
                
                foreach ($lastYearPosts as $key => $value) {
                    $string .= '<li>'.$value.'</li>';
                }
                
                $string .= '</ol>';
            }
        }
        
        // добавим данные в кэш
        addVladCache($cache_key, $string);
        
        return $string;
        
    }
    
    // Скільки днів у році:
    function daysInYear($year) {
        if(date('L',mktime(0, 0, 0, 12, 31, $year))) {
            return 366;
        } else {
            return 365;
        }
    }