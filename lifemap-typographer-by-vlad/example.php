<?php

error_reporting(E_ALL); 
header('Content-Type: text/html; charset=utf-8');

require_once("EMT.php");


/*

// 1. Запуск типографа с настройками по умолчанию
$typograf = new EMTypograph();
$typograf->set_text("...Когда В. И. Пупкин увидел в газете ( это была &quot;Сермяжная правда&quot; № 45) рубрику Weather Forecast(r), он не поверил своим глазам - температуру обещали +-451F.");
$result = $typograf->apply();
echo "<i>Настройки по умолчанию</i>: " . $result . "\n";

*/


// 2. Ручная настройка правил
$typograf = new EMTypograph();
$typograf->set_text("...Когда В. И. Пупкин увидел в газете ( это была &quot;Сермяжная правда&quot; № 45) рубрику Weather Forecast(r), он не поверил своим глазам - температуру обещали +-451F. 123456789");
$typograf->setup(array(
		'Etc.split_number_to_triads' => 'off',
		'Number.minus_in_numbers_range' => 'off',
		'Number.auto_times_x' => 'off',
		'Number.simple_fraction' => 'off',
		'Number.math_chars' => 'off',		
		'Number.thinsp_between_number_triads' => 'off',
		'Number.thinsp_between_no_and_number' => 'off',
		'Number.thinsp_between_sect_and_number' => 'off',
));
$result = $typograf->apply();
echo "<i>Без параграфов, висячей пунктуации</i>: " . $result . "<br><br>\n";


/*

// 3. Быстрый запуск типографа с настройками по умолчанию
$result = EMTypograph::fast_apply("...Когда В. И. Пупкин увидел в газете ( это была &quot;Сермяжная правда&quot; № 45) рубрику Weather Forecast(r), он не поверил своим глазам - температуру обещали +-451F.");
echo "<i>Быстрый запуск</i>: " . $result . "<br>\n";



*/
/*
// 4. Быстрый запуск типографа с ручными настройками
$result = EMTypograph::fast_apply("...
Когда В. И. Пупкин увидел в газете ( это была &quot;Сермяжная правда&quot; № 45) рубрику Weather Forecast(r), он не поверил своим глазам - температуру обещали +-451F.",array(
	'Text.paragraphs' => 'off',
	'OptAlign.oa_oquote' => 'off',
	'OptAlign.oa_obracket_coma' => 'off',
    		'Dash.to_libo_nibud' => 'off',
		'Dash.iz_za_pod' => 'off',
		'Dash.ka_de_kas' => 'off',
		'Etc.unicode_convert' => 'off',
		'breakline' => 'off',
));
echo "<i>Быстрый запуск настройками</i>: " . $result . "<br>\n";
*/

?>