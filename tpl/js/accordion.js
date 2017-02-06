$(document).ready(function() {
    function close_accordion_section() {
        $('.search-item-title').removeClass('active');
        $('.search-item-content').slideUp(300).removeClass('open');
    }

    $(document).on('click', '.search-item a', function(e) {
        // Grab current anchor value
        var currentAttrValue = $(this).attr('href');

        if($(e.target).is('.active')) {
            close_accordion_section();
        }else {
            close_accordion_section();

            // Add active class to section title
            $(this).addClass('active');
            // Open up the hidden content panel
            $(currentAttrValue).slideDown(300).addClass('open');
        }

        e.preventDefault();
    });
});
