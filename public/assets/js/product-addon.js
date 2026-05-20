if (!window.productAddonExternalControlsBound && !window.productAddonInlineControlsBound) {
window.productAddonExternalControlsBound = true;

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

    function nextGroupIndex() {
        let maxIndex = -1;

        $('#addon-wrapper .addon-block').each(function () {
            let input = $(this).find('.group-toggle').first();
            let match = input.attr('name') ? input.attr('name').match(/^addons\[(\d+)\]/) : null;

            if (match) {
                maxIndex = Math.max(maxIndex, parseInt(match[1], 10));
            }
        });

        return maxIndex + 1;
    }

    function nextOptionIndex(block) {
        let maxIndex = -1;

        block.find('.option-toggle').each(function () {
            let match = $(this).attr('name') ? $(this).attr('name').match(/\[options\]\[(\d+)\]/) : null;

            if (match) {
                maxIndex = Math.max(maxIndex, parseInt(match[1], 10));
            }
        });

        return maxIndex + 1;
    }

    function templateHtml(id, replacements) {
        let template = document.getElementById(id);

        if (!template) {
            return '';
        }

        let html = template.innerHTML;

        Object.keys(replacements).forEach(function (key) {
            html = html.replace(new RegExp(key, 'g'), replacements[key]);
        });

        return html;
    }

    function initBlock(block) {
        block.find('.group-toggle').prop('checked', true).prop('disabled', false);
        block.find('.option-toggle').prop('checked', true).prop('disabled', false);
        block.find('.option-input').prop('disabled', false);
        block.removeClass('addon-disabled');
        block.find('.addon-body').show();
        block.find('.addon-arrow').removeClass('la-angle-down').addClass('la-angle-up');
        updateSelectAllButton(block);
    }

    /*
    |--------------------------------------------------------------------------
    | ADD / REMOVE GROUPS AND OPTIONS
    |--------------------------------------------------------------------------
    */

    $(document).on('click', '#add-addon-group', function () {
        let groupIndex = nextGroupIndex();
        let html = templateHtml('addon-group-template', {
            __GROUP_INDEX__: groupIndex
        });

        if (!html) {
            return;
        }

        let block = $(html);
        $('#addon-wrapper').prepend(block);
        initBlock(block);
        if (block[0]) {
            block[0].scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }
        block.find('.group-name').first().trigger('focus');
    });

    $(document).on('click', '.add-option-btn', function () {
        let block = $(this).closest('.addon-block');
        let groupInput = block.find('.group-toggle').first().attr('name') || '';
        let groupMatch = groupInput.match(/^addons\[(\d+)\]/);
        let groupIndex = groupMatch ? groupMatch[1] : nextGroupIndex();
        let optionIndex = nextOptionIndex(block);
        let html = templateHtml('addon-option-template', {
            __GROUP_INDEX__: groupIndex,
            __OPTION_INDEX__: optionIndex
        });

        if (!html) {
            return;
        }

        let row = $(html);
        block.find('.addon-options').append(row);
        row.find('.option-toggle').prop('checked', true).prop('disabled', false);
        row.find('.option-input').prop('disabled', false);
        updateSelectAllButton(block);
    });

    $(document).on('click', '.remove-group', function () {
        let wrapper = $('#addon-wrapper');

        if (wrapper.find('.addon-block').length <= 1) {
            let block = $(this).closest('.addon-block');
            block.find('input[type="text"], input[type="number"], input[type="file"], input[type="hidden"]').val('');
            block.find('.group-toggle, .option-toggle').prop('checked', false).trigger('change');
            return;
        }

        $(this).closest('.addon-block').remove();
    });

    $(document).on('click', '.remove-option', function () {
        let block = $(this).closest('.addon-block');
        let rows = block.find('.addon-option-row');

        if (rows.length <= 1) {
            let row = $(this).closest('.addon-option-row');
            row.find('input[type="text"], input[type="number"], input[type="file"], input[type="hidden"]').val('');
            row.find('.option-toggle').prop('checked', false).trigger('change');
        } else {
            $(this).closest('.addon-option-row').remove();
        }

        updateSelectAllButton(block);
    });

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

        let groupToggle = block.find('.group-toggle').first();

        if (groupToggle.length && !groupToggle.is(':checked')) {
            groupToggle.prop('checked', true).trigger('change');
        }

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
}
/*
|--------------------------------------------------------------------------
| COLLAPSE HEADER CLICK
|--------------------------------------------------------------------------
*/

if (!window.productAddonSkipExternalCollapse) {
$(document).off('click.productAddonCollapse', '.addon-collapse-header').on('click.productAddonCollapse', '.addon-collapse-header', function (e) {

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
}
