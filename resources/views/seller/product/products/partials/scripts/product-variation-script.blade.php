
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var toggle = document.getElementById('attributes_enable_toggle');
        var container = document.getElementById('attributes-container');
        var choiceAttr = document.getElementById('choice_attributes');

        function updateAttributesState() {
            if (toggle) {
                toggle.checked = true;
            }

            if (container) {
                container.style.display = 'block';
            }

            if (choiceAttr) {
                choiceAttr.disabled = false;
            }

            if (window.jQuery && window.jQuery.fn && window.jQuery.fn.selectpicker) {
                $(choiceAttr).selectpicker('refresh');
            } else if (window.AIZ && AIZ.plugins && AIZ.plugins.bootstrapSelect) {
                AIZ.plugins.bootstrapSelect('refresh');
            }
            var skuCombination = document.getElementById('sku_combination');
            if (typeof update_sku === 'function' && (!skuCombination || !skuCombination.innerHTML.trim())) {
                update_sku();
            }
        }
        if (toggle) {
            toggle.addEventListener('change', updateAttributesState);
        }
        updateAttributesState();
    });
</script>
