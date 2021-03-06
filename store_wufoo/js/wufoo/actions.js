jQuery( document ).ready(function() {

    /**
    * Rating star system
    * updates rating input groups based on user clicks
    */
    jQuery('.wufoo-ratings :input[type="radio"]').on('click', function() {
        // grab clicked element
        var $clickedElm     = jQuery(this);
        // get clicked index
        var clickedElmIndex = $clickedElm.closest('label').index();
        // get ratings group
        var $groupedElm     = $clickedElm.closest('.wufoo-ratings > div').children();

        // reset classes
        $groupedElm.removeClass('active');

        // loop through each ratings elm and determine if if is active or not
        $groupedElm.each(function( index ) {
            jQuery(this).addClass('active');
            if ( clickedElmIndex === index ) return false;
        });
    });

    /**
    * Fixes safari issues with bootstrap 2.0 modals
    */
    //Allows scrolling on elements transform in Safari
    function fixSafariScrolling(event) {
        event.target.style.overflowY = 'hidden';
        setTimeout(function () { event.target.style.overflowY = 'auto'; });
    }

    // Add scrolling fix for every .modal element
    for (var i = 0 ; i < jQuery('.modal').length; i++) {
      jQuery('.modal')[i].addEventListener('webkitAnimationEnd', fixSafariScrolling);
    }

    var scrollPos = 0;
    jQuery('.modal')
        .on('show.bs.modal', function (){
            scrollPos = jQuery('body').scrollTop();
            jQuery('body').css({
                overflow: 'hidden',
                position: 'fixed',
                top : -scrollPos
            });
        })
        .on('hide.bs.modal', function (){
            jQuery('body').css({
                overflow: '',
                position: '',
                top: ''
            }).scrollTop(scrollPos);
        });
});
