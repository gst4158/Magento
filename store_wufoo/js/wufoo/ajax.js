jQuery( document ).ready(function() {
    // submit form actions
    jQuery(".wufoo-ajax-form .btn-primary").off().on("click", function(e){

        //format date inputs
        wufooFormatDate(":input.input-date");

        //format phone inputs
        wufooFormatPhone(":input.input-phone");

        // setup vars
        var formElm   = jQuery(this).closest('form'),
            formID    = jQuery(formElm).attr('id');
            formData  = encodeURIComponent(jQuery(':input', formElm).not('[data-serialize=false], [name=_antispam], [name=wufooformID]').serialize().replace(/[^&]+=&/g, '').replace(/&[^&]+=$/g, ''));

        // wufoo vars
        var wufooHash = jQuery('input[name=wufooformID]', formElm).val(),
            postUrl   = wufoo_ajax_url + '?wufooformID='+wufooHash+'&data='+formData;

        // create validation check
        var wufooForm = new VarienForm(formID);
        wufooForm.validator.validate();

        // form submit handling
        if ( wufooForm.validator.validate() == true ) {
            // submit form
            jQuery(this).prop("disabled",true);
            wufooAjax(postUrl, formElm);
        } else {
            // display error message
            jQuery(this).prop("disabled",false);
            jQuery(formElm).find('.wufoo-error').fadeIn();
        };

        // prevent default click events
        return false;

    }); // END click event

}); // END document ready

// ajax function
function wufooAjax(ajaxURL, form) {
    var result = "";
    new Ajax.Updater({ success:'formSuccess' }, ajaxURL, {
        method:'post',
        asynchronous:true,
        evalScripts:false,
        onCreate: function(transport) {
            // disable submit button
            jQuery(form).find('.wufoo-fail, .wufoo-error, .wufoo-success').fadeOut();
            jQuery('.wufoo-ajax-form .btn-primary', form).prop("disabled",true);
        },
        onComplete:function(transport) {
            // get form response
            result = transport.responseText;
            result = jQuery.parseJSON(result);

            // form success
            if ( result['http_code'] == 201 ) {
                //show confirm message
                var successElm = jQuery('.wufoo-success', form).clone();
                jQuery(form).closest('.modal-body').append(successElm);
                successElm.fadeIn();

                // hide form
                jQuery(form).fadeOut(800);
                setTimeout(function(){
                    jQuery(form).remove();
                }, 900);
            }
            // form failed
            else {
                // display error message
                jQuery(form).find('.wufoo-fail').fadeIn();
                // re-enable submit button
                jQuery('.wufoo-ajax-form .btn-primary', form).prop("disabled", false);
            }
        },
        onLoading:function(transport) {

        }
    });
}

// format date inputs
function wufooFormatDate(cssSelector) {
    jQuery( cssSelector ).each(function( index ) {
        if ( jQuery(this).val().length ) {
            var date     = new Date(jQuery(this).val());
            var year     = datehelper(date.getFullYear());
            var month    = datehelper(date.getMonth() + 1);
            var day      = datehelper(date.getDate());
            var yyyymmdd = year + month + day;
            jQuery(this).siblings(':input').val(yyyymmdd);
        };
    });
};

// Used by wufooFormatDate to help check date data
function datehelper(numb) {
    return (numb < 10 ? '0' : '') + numb;
}

// format phone inputs
function wufooFormatPhone(cssSelector) {
    jQuery( ":input.input-phone" ).each(function( index ) {
        if ( jQuery(this).val().length ) {
            var value = jQuery(this).val();
            var stripped = value.replace(/\D/g, '').replace(/[_\s]/g, '-');
            jQuery(this).siblings(':input').val(stripped);
        };
    });
};
