
jQuery(document).ready(function($){
  synchronize_child_and_parent_category($);
});
 
function synchronize_child_and_parent_category($) {
  $('#categorychecklist').find('input').each(function(index, input) {
    $(input).bind('change', function() {
      var checkbox = $(this);
      var is_checked = $(checkbox).is(':checked');
      if(is_checked) {
        $(checkbox).parents('li').children('label').children('input').attr('checked', 'checked');
      } else {
        $(checkbox).parentsUntil('ul').find('input').removeAttr('checked');
      }
    });
  });
}

/*
// Приховування чекбоксі батьківських категорій:
jQuery(document).ready(function($){   
    // Беру всі категорії:
    var elements = $('[id^="in-category"]');
   
    // Масив категорій:
    for (i = 0; i < elements.length; i++) {
        
        // Беру елемент кожен елемент li:
        var currentLi = document.getElementById('category-' + elements[i].value);
        
        // Дізнаюсь чи в поточному елементі є дочірні елементи:
        var currentChild = currentLi.getElementsByClassName("children")[0];

        // Якщо дочірній елемент є:
        if (currentChild != null) {
            
            // Якщо чекбокс не відмічений:
            if(elements[i].checked != true) {
                // Приховую чекбоси:
                var id = "#in-category-" + elements[i].value;
                $(id).addClass("hide_checkbox");
                
                // Виділяю:
                var temp = currentLi.getElementsByClassName("selectit")[0];
                var temp2 = temp.innerHTML;
                $(temp).html('<span class="category_parent">' + temp2 + '</span>');
            }
        }
    }
});
*/