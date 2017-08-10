/*!
 * jQuery Fork stuff
 */

/**
 * HTML5 validation
 */
(function($)
{
    $.fn.html5validation = function(options)
    {
        // define defaults
        var $input = $(this),
            errorMessage = '',
            type = '',
            defaults = {
                required: jsFrontend.locale.err('FieldIsRequired'),
                email: jsFrontend.locale.err('EmailIsInvalid'),
                date: jsFrontend.locale.err('DateIsInvalid'),
                number: jsFrontend.locale.err('NumberIsInvalid'),
                value: jsFrontend.locale.err('InvalidValue')
            };

        options = $.extend(defaults, options);

        $input.on('invalid', function(e) {
            if ($input.context.validity.valueMissing) {
                errorMessage = options.required;
            } else if (!$input.context.validity.valid) {
                type = $input.context.type;
                errorMessage = options.value;

                if (options[type]) {
                    errorMessage = options[type];
                }
            }

            e.target.setCustomValidity(errorMessage);

            $input.on('input change', function(e) {
                e.target.setCustomValidity('');
            });
        });
    };
})(jQuery);
