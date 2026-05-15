$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | INIT BUTTON TEXT
    |--------------------------------------------------------------------------
    */

    function updateSelectAllButton(block) {

        let options = block.find('.option-toggle');

        let checkedOptions = options.filter(':checked');

        let btn = block.find('.select-all-options');

        if (options.length > 0 && options.length === checkedOptions.length) {

            btn.text('Deselect All');

        } else {

            btn.text('Select All');

        }
    }

    /*
    |--------------------------------------------------------------------------
    | GROUP TOGGLE
    |--------------------------------------------------------------------------
    */

    $(document).on('change', '.group-toggle', function () {

        let block = $(this).closest('.addon-block');

        let checked = $(this).is(':checked');

        if (checked) {

            block.removeClass('addon-disabled');

            block.find('.option-toggle').prop('disabled', false);

            block.find('.option-input').prop('disabled', false);

        } else {

            block.addClass('addon-disabled');

            block.find('.option-toggle').prop('checked', false);

            block.find('.option-toggle').prop('disabled', true);

            block.find('.option-input').prop('disabled', true);

        }

        updateSelectAllButton(block);

    });

    /*
    |--------------------------------------------------------------------------
    | OPTION TOGGLE
    |--------------------------------------------------------------------------
    */

    $(document).on('change', '.option-toggle', function () {

        let row = $(this).closest('.addon-option-row');

        let checked = $(this).is(':checked');

        if (checked) {

            row.removeClass('option-disabled');

            row.find('.option-input').prop('disabled', false);

        } else {

            row.addClass('option-disabled');

            row.find('.option-input').prop('disabled', true);

        }

        let block = $(this).closest('.addon-block');

        updateSelectAllButton(block);

    });

    /*
    |--------------------------------------------------------------------------
    | SELECT / DESELECT ALL
    |--------------------------------------------------------------------------
    */

    $(document).on('click', '.select-all-options', function () {

        let block = $(this).closest('.addon-block');

        let options = block.find('.option-toggle:not(:disabled)');

        let allChecked = options.length === options.filter(':checked').length;

        if (allChecked) {

            options.prop('checked', false).trigger('change');

        } else {

            options.prop('checked', true).trigger('change');

        }

        updateSelectAllButton(block);

    });

    /*
    |--------------------------------------------------------------------------
    | INITIAL LOAD
    |--------------------------------------------------------------------------
    */

    $('.addon-block').each(function () {

        let block = $(this);

        updateSelectAllButton(block);

        /*
        | GROUP INIT
        */

        let groupChecked = block.find('.group-toggle').is(':checked');

        if (!groupChecked) {

            block.addClass('addon-disabled');

            block.find('.option-toggle').prop('disabled', true);

            block.find('.option-input').prop('disabled', true);

        }

        /*
        | OPTION INIT
        */

        block.find('.option-toggle').each(function () {

            let row = $(this).closest('.addon-option-row');

            let checked = $(this).is(':checked');

            if (!checked) {

                row.addClass('option-disabled');

                row.find('.option-input').prop('disabled', true);

            }

        });

    });

});
/*
|--------------------------------------------------------------------------
| COLLAPSE HEADER CLICK
|--------------------------------------------------------------------------
*/

$(document).on('click', '.addon-collapse-header', function (e) {

    /*
    |--------------------------------------------------------------------------
    | PREVENT COLLAPSE ON INPUTS/BUTTONS/SWITCHES
    |--------------------------------------------------------------------------
    */

    if (
        $(e.target).closest('input').length ||
        $(e.target).closest('button').length ||
        $(e.target).closest('label').length
    ) {
        return;
    }

    let block = $(this).closest('.addon-block');

    let body = block.find('.addon-body');

    let arrow = block.find('.addon-arrow');

    body.slideToggle(200);

    arrow.toggleClass('la-angle-down la-angle-up');

});
