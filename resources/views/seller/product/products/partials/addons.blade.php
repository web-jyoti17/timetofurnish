<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Product Add-ons</h5>

        <button type="button"
                class="btn btn-soft-primary btn-sm"
                id="add-addon-group">
            <i class="las la-plus"></i>
            Add New Addon
        </button>
    </div>

    <div class="card-body">

        <div id="addon-wrapper">

            @php
                $addons = !empty($addons) ? $addons : ($oldAddonsJson ?? []);
            @endphp

            @forelse($addons as $index => $addon)

                @include('seller.product.products.partials.addon-group', [
                    'addon' => $addon,
                    'index' => $index
                ])

            @empty

                @include('seller.product.products.partials.addon-group', [
                    'addon' => null,
                    'index' => 0
                ])

            @endforelse

        </div>

    </div>

</div>

<template id="addon-group-template">
    @include('seller.product.products.partials.addon-group', [
        'addon' => null,
        'index' => '__GROUP_INDEX__'
    ])
</template>

<template id="addon-option-template">
    @include('seller.product.products.partials.addon-option', [
        'option' => [],
        'groupIndex' => '__GROUP_INDEX__',
        'optIndex' => '__OPTION_INDEX__',
    ])
</template>

<script>
    (function () {
        if (window.productAddonInlineControlsBound) {
            return;
        }

        window.productAddonInlineControlsBound = true;
        window.productAddonControlsBound = true;
        window.productAddonSkipExternalCollapse = true;

        function closest(element, selector) {
            return element && element.closest ? element.closest(selector) : null;
        }

        function addonWrapper() {
            return document.getElementById('addon-wrapper');
        }

        function optionToggles(block) {
            return Array.prototype.slice.call(block.querySelectorAll('.option-toggle'));
        }

        function updateSelectAllButton(block) {
            var button = block.querySelector('.select-all-options');
            var toggles = optionToggles(block).filter(function (toggle) {
                return !toggle.disabled;
            });
            var checkedCount = toggles.filter(function (toggle) {
                return toggle.checked;
            }).length;

            if (button) {
                button.textContent = toggles.length > 0 && toggles.length === checkedCount
                    ? 'Deselect All'
                    : 'Select All';
            }
        }

        function setOptionState(row, checked) {
            row.classList.toggle('option-disabled', !checked);
            row.querySelectorAll('.option-input').forEach(function (input) {
                input.disabled = !checked;
            });
        }

        function setGroupState(block, checked) {
            block.classList.toggle('addon-disabled', !checked);

            block.querySelectorAll('.option-toggle').forEach(function (toggle) {
                toggle.disabled = !checked;
                if (!checked) {
                    toggle.checked = false;
                }

                var row = closest(toggle, '.addon-option-row');
                if (row) {
                    setOptionState(row, checked && toggle.checked);
                }
            });

            updateSelectAllButton(block);
        }

        function nextGroupIndex() {
            var maxIndex = -1;

            document.querySelectorAll('#addon-wrapper .group-toggle').forEach(function (input) {
                var match = input.name.match(/^addons\[(\d+)\]/);
                if (match) {
                    maxIndex = Math.max(maxIndex, parseInt(match[1], 10));
                }
            });

            return maxIndex + 1;
        }

        function nextOptionIndex(block) {
            var maxIndex = -1;

            block.querySelectorAll('.option-toggle').forEach(function (input) {
                var match = input.name.match(/\[options\]\[(\d+)\]/);
                if (match) {
                    maxIndex = Math.max(maxIndex, parseInt(match[1], 10));
                }
            });

            return maxIndex + 1;
        }

        function templateHtml(id, replacements) {
            var template = document.getElementById(id);
            var html = template ? template.innerHTML : '';

            Object.keys(replacements).forEach(function (key) {
                html = html.replace(new RegExp(key, 'g'), replacements[key]);
            });

            return html;
        }

        function appendHtml(parent, html, prepend) {
            var holder = document.createElement('div');
            holder.innerHTML = html.trim();
            var node = holder.firstElementChild;

            if (node) {
                if (prepend && parent.firstElementChild) {
                    parent.insertBefore(node, parent.firstElementChild);
                } else {
                    parent.appendChild(node);
                }
            }

            return node;
        }

        function openBlock(block) {
            var body = block.querySelector('.addon-body');
            var arrow = block.querySelector('.addon-arrow');

            if (body) {
                body.style.display = 'block';
            }

            if (arrow) {
                arrow.classList.remove('la-angle-down');
                arrow.classList.add('la-angle-up');
            }
        }

        function initializeBlock(block, forceEnabled) {
            var groupToggle = block.querySelector('.group-toggle');
            var checked = forceEnabled ? true : !!(groupToggle && groupToggle.checked);

            if (groupToggle) {
                groupToggle.checked = checked;
                groupToggle.disabled = false;
            }

            block.querySelectorAll('.option-toggle').forEach(function (toggle) {
                if (forceEnabled) {
                    toggle.checked = true;
                }

                var row = closest(toggle, '.addon-option-row');
                toggle.disabled = !checked;
                if (row) {
                    setOptionState(row, checked && toggle.checked);
                }
            });

            setGroupState(block, checked);

            if (forceEnabled) {
                openBlock(block);
            }
        }

        document.addEventListener('click', function (event) {
            var target = event.target;
            var wrapper = addonWrapper();

            if (!wrapper) {
                return;
            }

            if (closest(target, '#add-addon-group')) {
                event.preventDefault();
                event.stopImmediatePropagation();

                var groupIndex = nextGroupIndex();
                var block = appendHtml(wrapper, templateHtml('addon-group-template', {
                    __GROUP_INDEX__: groupIndex
                }), true);

                if (block) {
                    initializeBlock(block, true);
                    block.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });

                    var nameInput = block.querySelector('.group-name');
                    if (nameInput) {
                        setTimeout(function () {
                            nameInput.focus();
                        }, 250);
                    }
                }

                return;
            }

            var block = closest(target, '.addon-block');

            if (!block) {
                return;
            }

            if (closest(target, '.add-option-btn')) {
                event.preventDefault();
                event.stopImmediatePropagation();

                var groupInput = block.querySelector('.group-toggle');
                var groupMatch = groupInput && groupInput.name ? groupInput.name.match(/^addons\[(\d+)\]/) : null;
                var groupIndexForOption = groupMatch ? groupMatch[1] : nextGroupIndex();
                var optionIndex = nextOptionIndex(block);
                var options = block.querySelector('.addon-options');
                var row = options ? appendHtml(options, templateHtml('addon-option-template', {
                    __GROUP_INDEX__: groupIndexForOption,
                    __OPTION_INDEX__: optionIndex
                })) : null;

                if (groupInput) {
                    groupInput.checked = true;
                }

                if (row) {
                    var toggle = row.querySelector('.option-toggle');
                    if (toggle) {
                        toggle.checked = true;
                        toggle.disabled = false;
                    }
                    setOptionState(row, true);
                    openBlock(block);
                    setGroupState(block, true);
                }

                return;
            }

            if (closest(target, '.select-all-options')) {
                event.preventDefault();
                event.stopImmediatePropagation();

                var groupToggleForSelect = block.querySelector('.group-toggle');

                if (groupToggleForSelect && !groupToggleForSelect.checked) {
                    groupToggleForSelect.checked = true;
                    setGroupState(block, true);
                }

                var toggles = optionToggles(block).filter(function (toggle) {
                    return !toggle.disabled;
                });
                var allChecked = toggles.length > 0 && toggles.every(function (toggle) {
                    return toggle.checked;
                });

                toggles.forEach(function (toggle) {
                    toggle.checked = !allChecked;
                    var row = closest(toggle, '.addon-option-row');
                    if (row) {
                        setOptionState(row, toggle.checked);
                    }
                });

                updateSelectAllButton(block);
                return;
            }

            if (closest(target, '.remove-option')) {
                event.preventDefault();
                event.stopImmediatePropagation();

                var optionRows = Array.prototype.slice.call(block.querySelectorAll('.addon-option-row'));
                var optionRow = closest(target, '.addon-option-row');

                if (optionRow && optionRows.length > 1) {
                    optionRow.remove();
                } else if (optionRow) {
                    optionRow.querySelectorAll('input').forEach(function (input) {
                        if (input.type === 'checkbox') {
                            input.checked = false;
                        } else if (input.type !== 'file') {
                            input.value = '';
                        }
                    });
                    setOptionState(optionRow, false);
                }

                updateSelectAllButton(block);
                return;
            }

            if (closest(target, '.remove-group')) {
                event.preventDefault();
                event.stopImmediatePropagation();

                var blocks = wrapper.querySelectorAll('.addon-block');

                if (blocks.length > 1) {
                    block.remove();
                } else {
                    block.querySelectorAll('input').forEach(function (input) {
                        if (input.type === 'checkbox') {
                            input.checked = false;
                        } else if (input.type !== 'file') {
                            input.value = '';
                        }
                    });
                    setGroupState(block, false);
                }

                return;
            }

            if (closest(target, '.addon-collapse-header') && !closest(target, 'input') && !closest(target, 'button') && !closest(target, 'label')) {
                event.preventDefault();
                event.stopImmediatePropagation();

                var body = block.querySelector('.addon-body');
                var arrow = block.querySelector('.addon-arrow');
                var isOpen = body && body.style.display !== 'none';

                if (body) {
                    body.style.display = isOpen ? 'none' : 'block';
                }

                if (arrow) {
                    arrow.classList.toggle('la-angle-down', isOpen);
                    arrow.classList.toggle('la-angle-up', !isOpen);
                }
            }
        }, true);

        document.addEventListener('change', function (event) {
            var target = event.target;
            var block = closest(target, '.addon-block');

            if (!block) {
                return;
            }

            if (target.classList.contains('group-toggle')) {
                event.stopImmediatePropagation();
                setGroupState(block, target.checked);
            }

            if (target.classList.contains('option-toggle')) {
                event.stopImmediatePropagation();
                var row = closest(target, '.addon-option-row');

                if (row) {
                    setOptionState(row, target.checked);
                }

                updateSelectAllButton(block);
            }
        }, true);

        document.querySelectorAll('#addon-wrapper .addon-block').forEach(function (block) {
            initializeBlock(block, false);
        });
    })();
</script>
