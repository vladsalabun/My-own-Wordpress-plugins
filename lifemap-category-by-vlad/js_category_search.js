jQuery(document).ready(function($){
    var $categoryDivs = $('.categorydiv');

    $categoryDivs.prepend('<input type="search" class="fc-search-field" placeholder="введіть початок назви..." style="width:100%" />');

    $categoryDivs.on('keyup search', '.fc-search-field', function (event) {

        var searchTerm = event.target.value,
            $listItems = $(this).parent().find('.categorychecklist li');

        if( $.trim(searchTerm) ){
            $listItems.hide().filter(function () {
                return $(this).text().toLowerCase().indexOf(searchTerm.toLowerCase()) !== -1;
            }).show();
        }
        else {
            $listItems.show();
        }
    });
});
