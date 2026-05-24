<div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Product Add-ons</h5>

    <button type="button"
            class="btn btn-soft-primary btn-sm"
            id="add-addon-group">
        <i class="las la-plus"></i>
        Add New Addon
    </button>
</div>

<div class="card-body seller-addons-body">

        <div id="addon-wrapper">

            @php
                $addons = !empty($addons) ? $addons : ($oldAddonsJson ?? []);
            @endphp

            @forelse($addons as $index => $addon)
                @php
                    $isGlobal = false;
                    if (empty($addon['id']) && isset($addon['name'])) {
                        $isGlobal = \App\Models\ProductAddonGlobal::where('name', $addon['name'])->exists();
                    }
                @endphp
                @include('seller.product.products.partials.addon-group', [
                    'addon' => $addon,
                    'index' => $index,
                    'isGlobal' => $isGlobal
                ])

            @empty

                @include('seller.product.products.partials.addon-group', [
                    'addon' => null,
                    'index' => 0
                ])

            @endforelse

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

        function normalizeAddonName(name) {
            return String(name || '').trim().toLowerCase();
        }

        function existingAddonNames(wrapper) {
            var names = [];

            wrapper.querySelectorAll('.addon-block').forEach(function (block) {
                var input = block.querySelector('.group-name');
                var name = normalizeAddonName(input ? input.value : '');

                if (name) {
                    names.push(name);
                }
            });

            return names;
        }

        function setOptionState(row, checked) {
            row.classList.toggle('option-disabled', !checked);
        }

        function setGroupState(block, checked) {
            block.classList.toggle('addon-disabled', !checked);

            block.querySelectorAll('.option-toggle').forEach(function (toggle) {
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

        function markGroupSelected(block) {
            var groupToggle = block ? block.querySelector('.group-toggle') : null;

            if (groupToggle && !groupToggle.checked) {
                groupToggle.checked = true;
                setGroupState(block, true);
            }
        }

        function markOptionSelected(row) {
            var block = closest(row, '.addon-block');

            markGroupSelected(block);

            var optionToggle = row ? row.querySelector('.option-toggle') : null;
            if (optionToggle && !optionToggle.checked) {
                optionToggle.checked = true;
                setOptionState(row, true);
                updateSelectAllButton(block);
            }
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
                    markGroupSelected(block);
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

                if (target.checked) {
                    markGroupSelected(block);
                }

                if (row) {
                    setOptionState(row, target.checked);
                }

                updateSelectAllButton(block);
            }
        }, true);

        document.addEventListener('input', function (event) {
            var target = event.target;
            var block = closest(target, '.addon-block');

            if (!block) {
                return;
            }

            if (target.classList.contains('group-name') && target.value.trim() !== '') {
                markGroupSelected(block);
                return;
            }

            if (target.classList.contains('option-input') && target.type !== 'file') {
                var row = closest(target, '.addon-option-row');
                if (row && target.value.trim() !== '') {
                    markOptionSelected(row);
                }
            }
        }, true);

        document.addEventListener('change', function (event) {
            var target = event.target;

            if (target.classList && target.classList.contains('option-input') && target.type === 'file' && target.files && target.files.length) {
                var row = closest(target, '.addon-option-row');
                if (row) {
                    markOptionSelected(row);
                }
            }
        }, true);

        function addAddon(addon, forceEnabled, isGlobal) {
            var wrapper = document.getElementById('addon-wrapper');
            if (!wrapper) return;

            var groupIndex = nextGroupIndex();
            var blockHtml = templateHtml('addon-group-template', {
                '__GROUP_INDEX__': groupIndex
            });

            var holder = document.createElement('div');
            holder.innerHTML = blockHtml.trim();
            var block = holder.firstElementChild;
            if (!block) return;

            if (isGlobal) {
                block.classList.add('is-global-addon');
            }

            var nameInput = block.querySelector('.group-name');
            if (nameInput) {
                nameInput.value = addon.name;
            }

            var groupToggle = block.querySelector('.group-toggle');
            if (groupToggle) {
                groupToggle.checked = forceEnabled;
                if (addon.id) {
                    groupToggle.value = addon.id;
                }
            }

            var optionsContainer = block.querySelector('.addon-options');
            if (optionsContainer) {
                optionsContainer.innerHTML = '';
            }

            if (addon.options && addon.options.length > 0) {
                addon.options.forEach(function (opt, optIndex) {
                    var optHtml = templateHtml('addon-option-template', {
                        '__GROUP_INDEX__': groupIndex,
                        '__OPTION_INDEX__': optIndex
                    });

                    var optHolder = document.createElement('div');
                    optHolder.innerHTML = optHtml.trim();
                    var optRow = optHolder.firstElementChild;
                    if (optRow) {
                        var optName = optRow.querySelector('input[name*="[name]"]');
                        if (optName) optName.value = opt.name;

                        var optPrice = optRow.querySelector('input[name*="[price]"]');
                        if (optPrice) optPrice.value = opt.price;

                        var optQty = optRow.querySelector('input[name*="[quantity]"]');
                        if (optQty) optQty.value = opt.quantity;

                        var existingImg = optRow.querySelector('input[name*="[existing_img]"]');
                        if (existingImg) existingImg.value = opt.img;

                        var optToggle = optRow.querySelector('.option-toggle');
                        if (optToggle) {
                            optToggle.checked = forceEnabled;
                            if (opt.id) {
                                optToggle.value = opt.id;
                            }
                        }

                        if (opt.img) {
                            var imgCol = optRow.querySelector('.col-md-3');
                            if (imgCol) {
                                var preview = document.createElement('div');
                                preview.className = 'mt-1';
                                preview.innerHTML = '<img src="' + $('#product-form-data').data('base-url') + '/' + opt.img + '" class="img-thumbnail" style="width:50px;height:50px;object-fit:cover;border-radius:6px;">';
                                imgCol.appendChild(preview);
                            }
                        }

                        optionsContainer.appendChild(optRow);
                    }
                });
            }

            wrapper.appendChild(block);
            initializeBlock(block, forceEnabled);
        }

        window.addProductAddonFromData = addAddon;

        function loadCategoryAddons() {
            var categoryIds = [];
            document.querySelectorAll('#treeview input[type="checkbox"]:checked').forEach(function (input) {
                categoryIds.push(input.value);
            });

            loadAddonsForCategories(categoryIds);
        }

        function loadAddonsForCategories(categoryIds) {
            var getAddonsRoute = $('#product-form-data').data('get-addons-route');
            if (!getAddonsRoute) return;

            $.ajax({
                type: 'POST',
                url: getAddonsRoute,
                data: {
                    category_ids: categoryIds,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (addons) {
                    var wrapper = document.getElementById('addon-wrapper');
                    if (!wrapper) return;

                    // Remove all existing global addon blocks
                    wrapper.querySelectorAll('.addon-block.is-global-addon').forEach(function (block) {
                        block.remove();
                    });

                    var existingNames = existingAddonNames(wrapper);

                    // Add the new global addons
                    addons.forEach(function (addon) {
                        var addonName = normalizeAddonName(addon.name);

                        if (addonName && existingNames.indexOf(addonName) !== -1) {
                            return;
                        }

                        addAddon(addon, false, true);
                        if (addonName) {
                            existingNames.push(addonName);
                        }
                    });
                }
            });
        }

        window.loadProductAddonsByCategories = loadAddonsForCategories;

        document.querySelectorAll('#addon-wrapper .addon-block').forEach(function (block) {
            initializeBlock(block, false);
        });
    })();
</script>
