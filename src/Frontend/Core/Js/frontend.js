/**
 * Frontend related objects
 */
var jsFrontend =
{
    debug: false,
    current: {},

    // init, something like a constructor
    init: function()
    {
        jsFrontend.current.language = jsFrontend.data.get('LANGUAGE');

        // init stuff
        jsFrontend.initAjax();

        // init controls
        jsFrontend.controls.init();

        // init form
        jsFrontend.forms.init();
    },

    // init
    initAjax: function()
    {
        // set defaults for AJAX
        $.ajaxSetup(
        {
            url: '/frontend/ajax',
            cache: false,
            type: 'POST',
            dataType: 'json',
            timeout: 10000,
            data: { fork: { module: null, action: null, language: jsFrontend.current.language } }
        });
    }
};

/**
 * Controls related javascript
 */
jsFrontend.controls =
{
    // init, something like a constructor
    init: function()
    {
        jsFrontend.controls.bindTargetBlank();
    },

    // bind target blank
    bindTargetBlank: function()
    {
        $('a.targetBlank').attr('target', '_blank');
    }
};

/**
 * Data related methods
 */
jsFrontend.data =
{
    initialized: false,
    data: {},

    init: function()
    {
        // check if var is available
        if(typeof jsData == 'undefined') throw 'jsData is not available';

        // populate
        jsFrontend.data.data = jsData;
        jsFrontend.data.initialized = true;
    },

    exists: function(key)
    {
        return (typeof eval('jsFrontend.data.data.' + key) != 'undefined');
    },

    get: function(key)
    {
        // init if needed
        if(!jsFrontend.data.initialized) jsFrontend.data.init();

        // return
        return eval('jsFrontend.data.data.' + key);
    }
};

/**
 * Form related javascript
 */
jsFrontend.forms =
{
    // init, something like a constructor
    init: function()
    {
        jsFrontend.forms.placeholders();
        jsFrontend.forms.datefields();
        jsFrontend.forms.validation();
        jsFrontend.forms.filled();
        jsFrontend.forms.datePicker();
    },

    // once text has been filled add another class to it (so it's possible to style it differently)
    filled: function()
    {
        $(document).on('blur', 'form input, form textarea, form select', function()
        {
            if($(this).val() === '') $(this).removeClass('filled');
            else $(this).addClass('filled');
        });
    },

    // initialize the date fields
    datefields: function()
    {
        // jQuery datapicker fallback for browsers that don't support the HTML5 date type
        var $inputDateType = $('input.inputDatefield');
        if ($inputDateType.length)
        {
            // the browser does not support the HTML5 data type
            if ('date' !== $inputDateType.get(0).type) {
                $inputDateType.addClass('inputDatefieldNormal');
            }
        }

        var $inputDatefields = $('.inputDatefieldNormal, .inputDatefieldFrom, .inputDatefieldTill, .inputDatefieldRange');
        var $inputDatefieldNormal = $('.inputDatefieldNormal');
        var $inputDatefieldFrom = $('.inputDatefieldFrom');
        var $inputDatefieldTill = $('.inputDatefieldTill');
        var $inputDatefieldRange = $('.inputDatefieldRange');

        if($inputDatefields.length > 0)
        {
            var dayNames = [jsFrontend.locale.loc('DayLongSun'), jsFrontend.locale.loc('DayLongMon'), jsFrontend.locale.loc('DayLongTue'), jsFrontend.locale.loc('DayLongWed'), jsFrontend.locale.loc('DayLongThu'), jsFrontend.locale.loc('DayLongFri'), jsFrontend.locale.loc('DayLongSat')];
            var dayNamesMin = [jsFrontend.locale.loc('DayShortSun'), jsFrontend.locale.loc('DayShortMon'), jsFrontend.locale.loc('DayShortTue'), jsFrontend.locale.loc('DayShortWed'), jsFrontend.locale.loc('DayShortThu'), jsFrontend.locale.loc('DayShortFri'), jsFrontend.locale.loc('DayShortSat')];
            var dayNamesShort = [jsFrontend.locale.loc('DayShortSun'), jsFrontend.locale.loc('DayShortMon'), jsFrontend.locale.loc('DayShortTue'), jsFrontend.locale.loc('DayShortWed'), jsFrontend.locale.loc('DayShortThu'), jsFrontend.locale.loc('DayShortFri'), jsFrontend.locale.loc('DayShortSat')];
            var monthNames = [jsFrontend.locale.loc('MonthLong1'), jsFrontend.locale.loc('MonthLong2'), jsFrontend.locale.loc('MonthLong3'), jsFrontend.locale.loc('MonthLong4'), jsFrontend.locale.loc('MonthLong5'), jsFrontend.locale.loc('MonthLong6'), jsFrontend.locale.loc('MonthLong7'), jsFrontend.locale.loc('MonthLong8'), jsFrontend.locale.loc('MonthLong9'), jsFrontend.locale.loc('MonthLong10'), jsFrontend.locale.loc('MonthLong11'), jsFrontend.locale.loc('MonthLong12')];
            var monthNamesShort = [jsFrontend.locale.loc('MonthShort1'), jsFrontend.locale.loc('MonthShort2'), jsFrontend.locale.loc('MonthShort3'), jsFrontend.locale.loc('MonthShort4'), jsFrontend.locale.loc('MonthShort5'), jsFrontend.locale.loc('MonthShort6'), jsFrontend.locale.loc('MonthShort7'), jsFrontend.locale.loc('MonthShort8'), jsFrontend.locale.loc('MonthShort9'), jsFrontend.locale.loc('MonthShort10'), jsFrontend.locale.loc('MonthShort11'), jsFrontend.locale.loc('MonthShort12')];

            if ($.isFunction($.fn.datepicker)) {
                $inputDatefieldNormal.each(function()
                {
                    // Create a hidden clone (before datepicker init!), which will contain the actual value
                    var clone = $(this).clone();
                    clone.insertAfter(this);
                    clone.hide();

                    // Rename the original field, used to contain the display value
                    $(this).attr('id', $(this).attr('id') + '-display');
                    $(this).attr('name', $(this).attr('name') + '-display');
                });

                $inputDatefields.datepicker({
                    dayNames: dayNames,
                    dayNamesMin: dayNamesMin,
                    dayNamesShort: dayNamesShort,
                    hideIfNoPrevNext: true,
                    monthNames: monthNames,
                    monthNamesShort: monthNamesShort,
                    nextText: jsFrontend.locale.lbl('Next'),
                    prevText: jsFrontend.locale.lbl('Previous'),
                    showAnim: 'slideDown'
                });

                // the default, nothing special
                $inputDatefieldNormal.each(function()
                {
                    // get data
                    var data = $(this).data();
                    var phpDate = new Date(data.year, data.month, data.day, 0, 0, 0); // Get date from php in YYYY-MM-DD format
                    var value = ($(this).val() !== '') ? $.datepicker.formatDate(data.mask, phpDate) : ''; // Convert the value to the data-mask to display it

                    // Create the datepicker with the desired display format and alt field
                    $(this).datepicker('option', {
                        dateFormat: data.mask,
                        firstDay: data.firstday,
                        altField: "#" + $(this).attr('id').replace('-display', ''),
                        altFormat: "yy-mm-dd"
                    }).datepicker('setDate', value);
                });

                // date fields that have a certain start date
                $inputDatefieldFrom.each(function()
                {
                    // get data
                    var data = $(this).data();
                    var value = $(this).val();

                    // set options
                    $(this).datepicker('option', {
                        dateFormat: data.mask, firstDay: data.firstday,
                        minDate: new Date(parseInt(data.startdate.split('-')[0], 10), parseInt(data.startdate.split('-')[1], 10) - 1, parseInt(data.startdate.split('-')[2], 10))
                    }).datepicker('setDate', value);
                });

                // date fields that have a certain enddate
                $inputDatefieldTill.each(function()
                {
                    // get data
                    var data = $(this).data();
                    var value = $(this).val();

                    // set options
                    $(this).datepicker('option',
                    {
                        dateFormat: data.mask,
                        firstDay: data.firstday,
                        maxDate: new Date(parseInt(data.enddate.split('-')[0], 10), parseInt(data.enddate.split('-')[1], 10) -1, parseInt(data.enddate.split('-')[2], 10))
                    }).datepicker('setDate', value);
                });

                // date fields that have a certain range
                $inputDatefieldRange.each(function()
                {
                    // get data
                    var data = $(this).data();
                    var value = $(this).val();

                    // set options
                    $(this).datepicker('option',
                    {
                        dateFormat: data.mask,
                        firstDay: data.firstday,
                        minDate: new Date(parseInt(data.startdate.split('-')[0], 10), parseInt(data.startdate.split('-')[1], 10) - 1, parseInt(data.startdate.split('-')[2], 10), 0, 0, 0, 0),
                        maxDate: new Date(parseInt(data.enddate.split('-')[0], 10), parseInt(data.enddate.split('-')[1], 10) - 1, parseInt(data.enddate.split('-')[2], 10), 23, 59, 59)
                    }).datepicker('setDate', value);
                });
            }
        }
    },

    validation: function()
    {
        $('input, textarea, select').each(function() {
            var $input = $(this),
                options = {};

            // Check for custom error messages
            $.each($input.data(), function(key, value) {
                if (key.indexOf('error') < 0) return;
                key = key.replace('error', '').toLowerCase();
                options[key] = value;
            });

            $input.html5validation(options);
        });
    },

    // placeholder fallback for browsers that don't support placeholder
    placeholders: function()
    {
        // detect if placeholder-attribute is supported
        jQuery.support.placeholder = ('placeholder' in document.createElement('input'));

        if(!jQuery.support.placeholder)
        {
            // bind focus
            $('input[placeholder], textarea[placeholder]').on('focus', function()
            {
                // grab element
                var input = $(this);

                // only do something when the current value and the placeholder are the same
                if(input.val() == input.attr('placeholder'))
                {
                    // clear
                    input.val('');

                    // remove class
                    input.removeClass('placeholder');
                }
            });

            $('input[placeholder], textarea[placeholder]').on('blur', function()
            {
                // grab element
                var input = $(this);

                // only do something when the input is empty or the value is the same as the placeholder
                if(input.val() === '' || input.val() === input.attr('placeholder'))
                {
                    // set placeholder
                    input.val(input.attr('placeholder'));

                    // add class
                    input.addClass('placeholder');
                }
            });

            // call blur to initialize
            $('input[placeholder], textarea[placeholder]').blur();

            // hijack the form so placeholders aren't submitted as values
            $('input[placeholder], textarea[placeholder]').parents('form').submit(function()
            {
                // find elements with placeholders
                $(this).find('input[placeholder]').each(function()
                {
                    // grab element
                    var input = $(this);

                    // if the value and the placeholder are the same reset the value
                    if(input.val() == input.attr('placeholder')) input.val('');
                });
            });
        }
    },

    // Add date pickers to the appropriate input elements
    datePicker: function ()
    {
        $('input[data-role="fork-datepicker"]').each(
            function (index, datePickerElement) {
                $(datePickerElement).datepicker();
            }
        );
    }
};

/**
 * Locale
 */
jsFrontend.locale =
{
    initialized: false,
    data: {},

    // init, something like a constructor
    init: function()
    {
        $.ajax({
            url: '/src/Frontend/Cache/Locale/' + jsFrontend.current.language + '.json',
            type: 'GET',
            dataType: 'json',
            async: false,
            success: function(data)
            {
                jsFrontend.locale.data = data;
                jsFrontend.locale.initialized = true;
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
                throw 'Regenerate your locale-files.';
            }
        });
    },

    // get an item from the locale
    get: function(type, key)
    {
        // initialize if needed
        if(!jsFrontend.locale.initialized) jsFrontend.locale.init();

        // validate
        if(typeof jsFrontend.locale.data[type][key] == 'undefined') return '{$' + type + key + '}';

        return jsFrontend.locale.data[type][key];
    },

    // get an action
    act: function(key)
    {
        return jsFrontend.locale.get('act', key);
    },

    // get an error
    err: function(key)
    {
        return jsFrontend.locale.get('err', key);
    },

    // get a label
    lbl: function(key)
    {
        return jsFrontend.locale.get('lbl', key);
    },

    // get localization
    loc: function(key)
    {
        return jsFrontend.locale.get('loc', key);
    },

    // get a message
    msg: function(key)
    {
        return jsFrontend.locale.get('msg', key);
    }
};

$(jsFrontend.init);
