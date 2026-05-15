
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var toggle = document.getElementById('attributes_enable_toggle');
        var container = document.getElementById('attributes-container');
        var choiceAttr = document.getElementById('choice_attributes');

        function updateAttributesState() {
            if (toggle.checked) {
                container.style.display = 'block';
                choiceAttr.disabled = false;
            } else {
                container.style.display = 'none';
                choiceAttr.disabled = true;
            }
            if (window.jQuery && window.jQuery.fn && window.jQuery.fn.selectpicker) {
                $(choiceAttr).selectpicker('refresh');
            } else if (window.AIZ && AIZ.plugins && AIZ.plugins.bootstrapSelect) {
                AIZ.plugins.bootstrapSelect('refresh');
            }
            if (typeof update_sku === 'function') {
                update_sku();
            }
        }
        toggle.addEventListener('change', updateAttributesState);
        updateAttributesState();
    });
</script>
