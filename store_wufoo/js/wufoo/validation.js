jQuery( document ).ready(function() {

    /**
    * Wufoo Form Validation Rules
    */
    Validation.addAllThese([

        // Phone Validations
        ['validate-phone', 'Please enter a valid phone number. For example (123) 456-7890 or 123-456-7890.', function (v, elm) {
            if ( !Validation.get('IsEmpty').test(v) == false ) {
                if ( jQuery(elm).hasClass('wufoo-required') ) {
                    return !Validation.get('IsEmpty').test(v);
                } else {
                    return Validation.get('IsEmpty').test(v);
                };
            } else {
                return Validation.get('IsEmpty').test(v) || /^(\()?\d{3}(\))?(-|\s)?\d{3}(-|\s)\d{4}$/.test(v);
            }
        }],

        // Number Validations
        ['validate-number', 'Please enter a number', function (v, elm) {
            if ( !Validation.get('IsEmpty').test(v) == false ) {
                if ( jQuery(elm).hasClass('wufoo-required') ) {
                    return !Validation.get('IsEmpty').test(v);
                } else {
                    return Validation.get('IsEmpty').test(v);
                };
            } else {
                if (Validation.get('IsEmpty').test(v)) {
                    return true;
                }
                v = parseNumber(v);
                return !isNaN(v);
            }
        }],

        // Money Validations
        ['validate-money', 'Please enter a valid $ amount. For example 100.00.', function (v, elm) {
            if ( !Validation.get('IsEmpty').test(v) == false ) {
                if ( jQuery(elm).hasClass('wufoo-required') ) {
                    return !Validation.get('IsEmpty').test(v);
                } else {
                    return Validation.get('IsEmpty').test(v) ||  /\-?([1-9]{1}[0-9]{0,2}(\,[0-9]{3})*(\.[0-9]{0,2})?|[1-9]{1}\d*(\.[0-9]{0,2})?|0(\.[0-9]{0,2})?|(\.[0-9]{1,2})?)$/.test(v)
                };
            } else {
                return Validation.get('IsEmpty').test(v) ||  /\-?([1-9]{1}[0-9]{0,2}(\,[0-9]{3})*(\.[0-9]{0,2})?|[1-9]{1}\d*(\.[0-9]{0,2})?|0(\.[0-9]{0,2})?|(\.[0-9]{1,2})?)$/.test(v)
            }
        }],

        // Date Validations
        ['validate-date', 'Please enter a valid date. MM/DD/YYYY', function (v, elm) {
            var test = new Date(v);
            if ( !Validation.get('IsEmpty').test(v) == false ) {
                if ( jQuery(elm).hasClass('wufoo-required') ) {
                    return !Validation.get('IsEmpty').test(v);
                } else {
                    return Validation.get('IsEmpty').test(v) || !isNaN(test);
                };
            } else {
                return Validation.get('IsEmpty').test(v) || !isNaN(test);
            }
        }],

        // URL Validations
        ['validate-url', 'Please enter a valid URL. Protocol is required (http://, https:// or ftp://)', function (v, elm) {
            if ( !Validation.get('IsEmpty').test(v) == false ) {
                if ( jQuery(elm).hasClass('wufoo-required') ) {
                    return !Validation.get('IsEmpty').test(v);
                } else {
                    return Validation.get('IsEmpty').test(v);
                };
            } else {
                v = (v || '').replace(/^\s+/, '').replace(/\s+$/, '');
                return Validation.get('IsEmpty').test(v) || /^(http|https|ftp):\/\/(([A-Z0-9]([A-Z0-9_-]*[A-Z0-9]|))(\.[A-Z0-9]([A-Z0-9_-]*[A-Z0-9]|))*)(:(\d+))?(\/[A-Z0-9~](([A-Z0-9_~-]|\.)*[A-Z0-9~]|))*\/?(.*)?$/i.test(v)
            }
        }],

        // Email Validations
        ['validate-email', 'Please enter a valid email address. For example johndoe@domain.com.', function (v, elm) {
            if ( !Validation.get('IsEmpty').test(v) == false ) {
                if ( jQuery(elm).hasClass('wufoo-required') ) {
                    return !Validation.get('IsEmpty').test(v);
                } else {
                    return Validation.get('IsEmpty').test(v);
                };
            } else {
                return Validation.get('IsEmpty').test(v) || /^([a-z0-9,!\#\$%&'\*\+\/=\?\^_`\{\|\}~-]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z0-9,!\#\$%&'\*\+\/=\?\^_`\{\|\}~-]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*@([a-z0-9-]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z0-9-]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*\.(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]){2,})$/i.test(v)
            }
        }],

        // Radio/Checkbox Validations
        ['validate-checked', 'Please select one of the options.', function (v, elm) {
            return jQuery(elm).siblings('div:not(.validation-advice)').find(':input').is(':checked');
        }],

        // Likert Validations
        ['validate-likert', 'Please select one of the options.', function (v, elm) {
            var wrapper = jQuery(elm).closest('tr');
            return jQuery(wrapper).find(':input').is(':checked');
        }],

    ]);


});
