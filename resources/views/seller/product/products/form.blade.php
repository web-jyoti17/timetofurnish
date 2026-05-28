<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap');

    #choice_form {
        --seller-primary: #c59259;
        --seller-primary-soft: rgba(197, 146, 89, 0.1);
        --seller-bg: #f5f6f7;
        --seller-surface: #ffffff;
        --seller-section: #fafafa;
        --seller-border: #d9dee3;
        --seller-border-strong: #bcc5cf;
        --seller-text: #202223;
        --seller-muted: #6d7175;
        width: 100%;
        max-width: 100%;
        margin: 0 auto;
        color: var(--seller-text);
        font-family: 'Outfit', sans-serif;
    }
  
    /* Conflicting flex style removed to allow proper CSS grid column alignment */


    #choice_form *,
    #choice_form *::before,
    #choice_form *::after {
        letter-spacing: 0;
    }

    #product-form-alert {
        margin-bottom: 14px;
        border-radius: 6px;
    }

    .product-form-field-error {
        display: block;
        margin-top: 6px;
        color: var(--seller-primary);
        font-size: 12px;
        line-height: 1.4;
    }

    .is-invalid-field,
    .is-invalid-field .dropdown-toggle {
        border-color: var(--seller-primary) !important;
    }

    #choice_form .seller-listing-topbar {
        margin-bottom: 14px;
    }

    #choice_form .seller-listing-title-card {
        margin: 0;
        border: 0 !important;
        background: transparent !important;
        box-shadow: none !important;
    }

    #choice_form .seller-listing-title-card .card-body {
        min-height: auto;
        padding: 0;
    }

    #choice_form .seller-listing-kicker,
    #choice_form .seller-category-gate-card {
        display: none !important;
    }

    #choice_form .seller-listing-title-card h3 {
        margin: 0;
        color: var(--seller-text);
        font-size: 24px;
        font-weight: 700;
        line-height: 1.2;
    }

    #choice_form .card,
    #choice_form .productvariation,
    #choice_form .seller-shipping-services-card,
    #choice_form .seller-addons-card-wrapper>.card,
    #choice_form .seller-action-card {
        margin: 0 0 14px;
        border: 1px solid var(--seller-border) !important;
        border-radius: 8px !important;
        background: var(--seller-surface) !important;
        box-shadow: 0 1px 0 rgba(32, 34, 35, 0.04) !important;
        overflow: hidden;
        animation: none !important;
        transform: none !important;
    }

    .note-editor.note-frame.card .note-toolbar.card-header {
        background: transparent !important;
        box-shadow: none !important;
        border: none !important;
    }

    #choice_form .card-header {
        min-height: 48px;
        padding: 13px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        background: #f0e6d9;
        border-bottom: 1px solid #eadfd2 !important;
        box-shadow: inset 3px 0 0 var(--seller-primary);
    }

    #choice_form .card-header h5,
    #choice_form .card-header .h6 {
        margin: 0;
        color: var(--seller-text) !important;
        font-size: 15px;
        font-weight: 700;
        line-height: 1.35;
    }

    #choice_form .card-body {
        padding: 16px;
    }

    #choice_form .seller-form-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 360px;
        gap: 16px;
        align-items: start;
        margin: 0;
    }

    #choice_form .seller-form-layout>[class*="col-"] {
        width: auto;
        max-width: none;
        padding: 0;
        flex: none;
    }

    #choice_form .seller-main-stack,
    #choice_form .seller-sidebar-stack {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    #choice_form .seller-sidebar-stack {
        position: sticky;
        top: 82px;
        max-height: none;
        overflow: visible;
        padding-bottom: 2px;
    }

    #choice_form .seller-main-stack>.card,
    #choice_form .seller-sidebar-stack>.card,
    #choice_form .productvariation,
    #choice_form .seller-addons-card-wrapper>.card,
    #choice_form .seller-shipping-services-card,
    #choice_form .seller-action-card {
        margin-bottom: 0;
    }

    #choice_form label,
    #choice_form .col-from-label,
    #choice_form .control-label,
    #choice_form .col-form-label {
        margin-bottom: 0;
        color: var(--seller-text);
        font-size: 13px;
        font-weight: 650;
        line-height: 1.35;
    }

    #choice_form .form-group {
        margin-bottom: 12px;
    }

    #choice_form .form-group:last-child {
        margin-bottom: 0;
    }

    #choice_form .form-group.row {
        display: grid;
        grid-template-columns: 180px minmax(0, 1fr);
        gap: 12px 20px;
        align-items: center;
        margin: 0;
        padding: 12px 0;
        border-bottom: 1px solid #edf0f2;
    }

    #choice_form .form-group.row:last-child {
        border-bottom: 0;
    }

    #choice_form .form-group.row>[class*="col-"] {
        width: 100%;
        max-width: none;
        padding: 0;
        flex: none;
    }

    #choice_form .form-group.row>label,
    #choice_form .form-group.row>.col-from-label,
    #choice_form .form-group.row>.control-label {
        grid-column: 1;
    }

    #choice_form .form-group.row>label+[class*="col-"],
    #choice_form .form-group.row>.col-from-label+[class*="col-"],
    #choice_form .form-group.row>.control-label+[class*="col-"] {
        grid-column: 2;
    }

    #choice_form .seller-product-details-card .form-group.row,
    #choice_form .seller-stock-visibility .form-group.row {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    #choice_form .seller-product-details-card .form-group.row {
        padding: 0;
        border-bottom: 0;
    }

    #choice_form .seller-stock-visibility .form-group.row>[class*="col-"] {
        width: auto;
        max-width: none;
        padding-left: 0;
        padding-right: 0;
        flex: 1 1 140px;
    }

    #choice_form .seller-stock-visibility .form-group.row>label {
        flex: 1 1 100%;
        padding: 0;
    }

    #choice_form .seller-price-stock-card .form-group.row {
        display: grid;
        grid-template-columns: 180px minmax(0, 1fr) minmax(0, 1fr);
        gap: 12px 18px;
    }

    #choice_form .seller-price-stock-card .form-group.row>[class*="col-"] {
        width: 100%;
        max-width: none;
        padding: 0;
        flex: none;
    }

    #choice_form .seller-price-stock-card .form-group.row>label {
        grid-column: 1;
    }

    #choice_form .seller-price-stock-card .form-group.row>label+[class*="col-"] {
        grid-column: 2;
    }

    #choice_form .seller-price-stock-card .form-group.row>label+[class*="col-"]+[class*="col-"] {
        grid-column: 3;
    }

    #choice_form .seller-price-stock-card .form-group.row .col-md-9,
    #choice_form .seller-price-stock-card .form-group.row .col-md-12,
    #choice_form .seller-price-stock-card #show-hide-div .form-group.row>div {
        grid-column: 2 / -1;
    }

    #choice_form .seller-price-stock-card .text-muted.discount {
        display: block;
        color: var(--seller-muted) !important;
        font-size: 12px;
        line-height: 1.45;
    }

    #choice_form .form-control,
    #choice_form .bootstrap-select .dropdown-toggle,
    #choice_form .input-group-text {
        min-height: 40px;
        border: 1px solid var(--seller-border-strong) !important;
        border-radius: 6px !important;
        background: #fff !important;
        color: var(--seller-text);
        box-shadow: none !important;
        font-size: 14px;
        border-radius: 50px !important;
    }

    #choice_form .bootstrap-select:not(.disabled) .dropdown-toggle,
    #choice_form .bootstrap-select:not(.disabled) .dropdown-toggle * {
        cursor: pointer;
    }

    #choice_form .bootstrap-select.disabled .dropdown-toggle {
        cursor: not-allowed;
        opacity: 0.72;
    }

    #choice_form .form-control:focus,
    #choice_form .bootstrap-select.show .dropdown-toggle {
        border-color: var(--seller-primary) !important;
        box-shadow: 0 0 0 2px var(--seller-primary-soft) !important;
    }

    #choice_form .form-control::placeholder {
        color: #8b949e;
    }

    #choice_form .input-group .form-control,
    #choice_form .input-group .input-group-text {
        border-radius: 0 !important;
    }

    #choice_form .input-group .input-group-prepend:first-child .input-group-text {
        border-top-left-radius: 6px !important;
        border-bottom-left-radius: 6px !important;
    }

    #choice_form .input-group .input-group-append:last-child .input-group-text,
    #choice_form .input-group .form-control:last-child {
        border-top-right-radius: 6px !important;
        border-bottom-right-radius: 6px !important;
    }

    #choice_form .btn {
        min-height: 38px;
        border-radius: 6px !important;
        box-shadow: none !important;
        font-weight: 700;
    }

    #choice_form .btn-primary {
        min-height: 42px;
        padding: 9px 20px;
        background: var(--seller-primary) !important;
        border-color: var(--seller-primary) !important;
        color: #fff !important;
    }

    #choice_form .btn-primary:hover,
    #choice_form .btn-primary:focus {
        transform: none !important;
    }

    #choice_form .btn-soft-primary,
    #choice_form .btn-soft-secondary,
    #choice_form .btn-soft-success,
    #choice_form .btn-soft-danger,
    #choice_form .btn-soft-info {
        background: var(--seller-primary-soft) !important;
        border-color: transparent !important;
        color: var(--seller-primary) !important;
    }

    #choice_form .aiz-switch input:checked+span {
        background-color: var(--seller-primary);
    }

    #choice_form .seller-shipping-services-card {
        margin-bottom: 16px;
    }

    #choice_form .seller-shipping-services-card .card-body {
        padding: 14px 16px 16px;
    }

    #choice_form .seller-service-panel,
    #choice_form .seller-variation-options,
    #choice_form .sku_combination,
    #choice_form #customer_choice_options>.form-group,
    #choice_form .addon-block,
    #choice_form .addon-group,
    #choice_form .addon-option-row {
        /* border: 1px solid var(--seller-border) !important; */
        border-radius: 6px !important;
        background: #fff !important;
        box-shadow: none !important;
    }

    #choice_form .seller-service-panel {
        height: 100%;
        overflow: hidden;
    }

    #choice_form .seller-service-panel-title,
    #choice_form .addon-block>.card-header,
    #choice_form .sku_combination thead td {
        padding: 11px 13px;
        background: #fff !important;
        border-color: var(--seller-border) !important;
        color: var(--seller-text) !important;
    }

    #choice_form .seller-service-panel-title h5 {
        font-size: 14px;
        font-weight: 700;
    }

    #choice_form .seller-service-panel .alert,
    #choice_form #shipping-charges-wrapper .alert,
    #choice_form #checkout-services-wrapper .alert {
        margin: 0;
        padding: 12px 13px;
        border: 0;
        border-top: 1px solid var(--seller-border);
        border-radius: 0;
        background: var(--seller-primary-soft);
        color: #6f563a;
        font-weight: 600;
    }

    #choice_form .seller-category-card .card-header h6 {
        color: var(--seller-muted);
        font-size: 12px;
        font-weight: 700;
    }

    #choice_form .main-category-info-icon .text-info {
        color: var(--seller-primary) !important;
    }

    #choice_form .seller-category-card .card-body>.mb-2 {
        display: grid !important;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-bottom: 10px !important;
    }

    #choice_form .seller-category-card .card-body>.mb-2 .btn {
        width: 100%;
        min-height: 36px;
        margin: 0;
        padding: 7px 8px;
    }

    #choice_form .category-tree-scroll {
        min-height: 0 !important;
        max-height: 250px !important;
        padding: 8px !important;
        border: 1px solid var(--seller-border) !important;
        border-radius: 6px !important;
        background: #fff !important;
    }

    #choice_form .hummingbird-treeview-converter {
        max-height: none;
        padding: 0;
    }

    #choice_form .hummingbird-treeview-converter ul {
        margin-left: 7px;
        padding-left: 11px;
        border-left: 1px solid #edf0f2;
    }

    #choice_form .hummingbird-treeview-converter li {
        margin: 1px 0;
        padding-left: 0;
    }

    #choice_form .hummingbird-treeview-converter label {
        min-height: 29px;
        padding: 5px 6px;
        display: flex;
        align-items: center;
        border-radius: 4px;
        color: var(--seller-text);
        font-size: 13px;
        font-weight: 650;
        cursor: pointer;
    }

    #choice_form .hummingbird-treeview-converter label:hover {
        background: var(--seller-primary-soft);
    }

    #choice_form .hummingbird-treeview-converter input[type="checkbox"] {
        appearance: none;
        width: 16px;
        height: 16px;
        min-width: 16px;
        margin-right: 8px;
        border: 1px solid var(--seller-border-strong);
        border-radius: 4px;
        background: #fff;
        position: relative;
        cursor: pointer;
    }

    #choice_form .hummingbird-treeview-converter input[type="checkbox"]:checked {
        background: var(--seller-primary);
        border-color: var(--seller-primary);
    }

    #choice_form .hummingbird-treeview-converter input[type="checkbox"]:checked::after {
        content: '\2713';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #fff;
        font-size: 11px;
        font-weight: 700;
    }

    #choice_form #treeview input[type="radio"],
    #choice_form .hummingbird-treeview-converter input[type="radio"] {
        display: none !important;
    }

    #choice_form .seller-sidebar-stack .card-body {
        padding: 14px;
    }

    #choice_form .seller-dimensions-card .card-header>div {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    #choice_form .seller-product-details-card .nav-tabs {
        border-bottom: 1px solid var(--seller-border);
        gap: 12px;
    }

    #choice_form .seller-product-details-card .note-editor .note-editing-area .note-editable,
    #choice_form .seller-product-details-card .note-editor.note-frame .note-editing-area .note-editable {
        min-height: 180px !important;
        max-height: 240px;
        overflow-y: auto;
    }

    #choice_form .seller-product-details-card .nav-tabs .nav-link {
        border: 0;
        border-bottom: 2px solid transparent;
        border-radius: 0;
        color: var(--seller-muted);
        font-weight: 650;
        padding: 9px 0;
    }

    #choice_form .seller-product-details-card .nav-tabs .nav-link.active {
        border-bottom-color: var(--seller-primary);
        color: var(--seller-text);
        background: transparent;
    }

    #choice_form .sku_combination {
        overflow-x: auto;
    }

    #choice_form .sku_combination:empty {
        display: none;
    }

    #choice_form .sku_combination table {
        margin-bottom: 0;
        min-width: 760px;
    }

    #choice_form .variant-option-badge,
    #choice_form .admin-catalog-chip {
        color: var(--seller-text) !important;
        background: #f6f6f7 !important;
        border: 1px solid var(--seller-border) !important;
        border-radius: 6px;
    }

    #choice_form .variant-option-cell {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    #choice_form .variant-option-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    #choice_form .variant-option-edit-btn {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
    }

    #choice_form .variant-option-badge.variant-option-editing {
        padding: 0 !important;
        border: 0 !important;
        background: transparent !important;
        box-shadow: none !important;
    }

    #choice_form .variant-option-inline-input {
        max-width: 180px;
        height: 34px;
        padding: 2px 14px;
        border: 1px solid var(--seller-border);
        border-radius: 999px;
        background: #f6f6f7;
        color: var(--seller-text);
        font-weight: 700;
        text-align: center;
        outline: none;
        box-shadow: none;
    }

    #choice_form .variant-option-inline-input:focus {
        border-color: var(--seller-border-strong);
        background: #fff;
    }

    #choice_form .seller-addons-body,
    #choice_form .productvariation .card-body {
        padding: 16px;
    }

    #choice_form .seller-addons-body {
        padding: 24px;
        background: #fcfbfa;
    }

    #choice_form #addon-wrapper {
        display: grid;
        gap: 20px;
    }

    #choice_form .addon-block {
        margin: 0 !important;
        padding: 0 !important;
        overflow: hidden;
        border-radius: 12px !important;
        border: 1px solid rgba(197, 146, 89, 0.12) !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02) !important;
        background: #fff;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    #choice_form .addon-block:hover {
        border-color: rgba(197, 146, 89, 0.25) !important;
        box-shadow: 0 10px 30px rgba(197, 146, 89, 0.06) !important;
    }

    #choice_form .addon-block>.card-header {
        padding: 14px 20px !important;
        background: linear-gradient(to right, rgba(197, 146, 89, 0.04) 0%, rgba(197, 146, 89, 0.01) 100%) !important;
        border-bottom: 1px solid rgba(197, 146, 89, 0.08) !important;
    }

    #choice_form .addon-block.addon-disabled {
        opacity: 0.85;
        border-color: #e2e8f0 !important;
        background: #fafafa;
    }

    #choice_form .addon-block.addon-disabled > .card-header {
        background: #f8fafc !important;
        border-bottom-color: #e2e8f0 !important;
    }

    #choice_form .addon-block .group-name {
        min-height: 38px;
        color: var(--seller-text);
        font-weight: 700;
        font-size: 15px;
        letter-spacing: -0.2px;
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        padding-left: 4px !important;
    }

    #choice_form .addon-block .group-name:focus {
        background: #fff !important;
        border: 1px solid rgba(197, 146, 89, 0.2) !important;
        border-radius: 8px !important;
        padding-left: 10px !important;
    }

    #choice_form .addon-block>.card-body {
        padding: 20px;
        background: #fff;
    }

    #choice_form .addon-table-container {
        overflow-x: auto;
        margin-bottom: 15px;
        -webkit-overflow-scrolling: touch;
    }

    #choice_form .seller-addon-option-head,
    #choice_form .addon-option-row {
        display: grid !important;
        grid-template-columns: 44px minmax(160px, 2fr) minmax(96px, 0.8fr) minmax(96px, 0.8fr) minmax(180px, 1.2fr) 40px !important;
        gap: 12px !important;
        align-items: center !important;
        min-width: 720px !important;
    }
    .addon-option-row, .seller-addon-option-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.addon-option-row .name {
    flex: 0 0 33%;
}

    #choice_form .seller-addon-option-head {
        margin-bottom: 12px;
        padding: 0 16px;
        color: #a27038;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    #choice_form .addon-option-row {
        margin-bottom: 12px;
        padding: 12px 16px;
        background: #fdfdfd !important;
        border: 1px solid #f3f3f3 !important;
        border-radius: 10px !important;
        transition: all 0.25s ease;
    }

    #choice_form .addon-option-row:hover {
        background: #fff !important;
        border-color: rgba(197, 146, 89, 0.18) !important;
        box-shadow: 0 4px 12px rgba(197, 146, 89, 0.04) !important;
    }

    #choice_form .addon-option-row .form-control {
        border-radius: 8px !important;
        border: 1px solid #e2dfd8 !important;
        padding: 0.55rem 0.85rem !important;
        font-size: 0.9rem !important;
        color: #4a463e !important;
        background-color: #fff !important;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    #choice_form .addon-option-row .form-control:focus {
        border-color: #c59259 !important;
        box-shadow: 0 0 0 3px rgba(197, 146, 89, 0.18) !important;
    }

    #choice_form .addon-option-toggle-cell {
        display: flex;
        justify-content: center;
    }

    #choice_form .addon-option-row .remove-option {
        width: 36px;
        height: 36px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50% !important;
        background: #fdf3f3 !important;
        color: #eb5757 !important;
        border: 1px solid #ffe3e3 !important;
        transition: all 0.25s ease;
        cursor: pointer;
    }

    #choice_form .addon-option-row .remove-option:hover {
        background: #eb5757 !important;
        color: #fff !important;
        border-color: #eb5757 !important;
        transform: scale(1.05);
        box-shadow: 0 3px 10px rgba(235, 87, 87, 0.25) !important;
    }

    #choice_form .option-disabled {
        background: #fafafa !important;
        border-color: #eee !important;
        opacity: 0.65;
    }

    /* Custom File Input Styling for Addons */
    #choice_form .addon-option-row input[type="file"] {
        padding: 0.5rem 0.85rem !important;
        font-size: 0.85rem !important;
        color: #7a756b !important;
        background: #fafbfc !important;
        cursor: pointer;
    }

    #choice_form .bootstrap-select .no-results,
    .bs-container .no-results {
        padding: 10px 12px;
        font-weight: 600;
        color: var(--seller-text);
        background: #f6f7f8;
        border-radius: 6px;
        white-space: normal;
    }

    .bootstrap-select .injected-add-custom-option-wrap,
    .bs-container .injected-add-custom-option-wrap {
        padding: 8px 0 0;
    }

    .bootstrap-select .injected-add-custom-option,
    .bs-container .injected-add-custom-option {
        width: 100%;
        min-height: 40px;
        margin-top: 10px;
        padding: 10px 12px;
        border: 1px solid rgba(197, 146, 89, 0.18);
        border-radius: 6px;
        background: rgba(197, 146, 89, 0.06);
        color: var(--seller-primary);
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 700;
        text-align: left;
        cursor: pointer;
        white-space: normal;
    }

    input[type="file"]::-webkit-file-upload-button,
    input[type="file"]::file-selector-button {
        display: none;
    }

    input[type="file"] {
        padding-left: 15px;
    }

    @media (max-width: 1199px) {
        #choice_form .seller-form-layout {
            grid-template-columns: minmax(0, 1fr) 340px;
        }
    }

    @media (max-width: 991px) {
        #choice_form {
            padding: 12px;
        }

        #choice_form .seller-listing-title-card .card-body {
            align-items: stretch !important;
            gap: 12px;
            flex-direction: column;
        }

        #choice_form .seller-listing-title-card .btn-primary {
            width: 100%;
        }

        #choice_form .seller-form-layout {
            grid-template-columns: 1fr;
        }

        #choice_form .seller-sidebar-stack {
            position: static;
            max-height: none;
            overflow: visible;
        }

        #choice_form .form-group.row,
        #choice_form .seller-product-details-card .form-group.row,
        #choice_form .seller-price-stock-card .form-group.row,
        #choice_form .seller-stock-visibility .form-group.row {
            display: grid;
            grid-template-columns: 1fr;
            gap: 8px;
        }

        #choice_form .form-group.row>label,
        #choice_form .form-group.row>.col-from-label,
        #choice_form .form-group.row>.control-label,
        #choice_form .form-group.row>label+[class*="col-"],
        #choice_form .form-group.row>.col-from-label+[class*="col-"],
        #choice_form .form-group.row>.control-label+[class*="col-"] {
            grid-column: 1;
        }

        #choice_form .seller-category-card .card-body>.mb-2 {
            grid-template-columns: 1fr;
        }

        #choice_form .category-tree-scroll {
            max-height: 280px !important;
        }

        /* Removed responsive vertical stack layout for addons to keep them horizontally aligned in single row */
    }
</style>



@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div id="product-form-alert" class="alert d-none" role="alert"></div>
{{-- Data container for JS --}}
<div id="product-form-data" class="d-none" data-base-url="{{ asset('public') }}"
    data-checkout-services-route="{{ route('seller.products.checkout-services') }}"
    data-shipping-charges-route="{{ route('seller.products.shipping-charges') }}"
    data-get-attributes-route="{{ route('get-attributes-by-categories') }}"
    data-get-addons-route="{{ route('seller.products.get-addons-by-categories') }}"
    data-add-more-choice-route="{{ route('seller.products.add-more-choice-option') }}"
    data-store-attribute-route="{{ route('seller.products.attributes.store') }}"
    data-sku-combination-route="{{ isset($product) && $product->id ? route('seller.products.sku_combination_edit') : route('seller.products.sku_combination') }}"
    data-old-addons='@json(old(' addons', $addons ?? []))' data-existing-addons='@json($addons ?? [])'
    data-product-id="{{ $product->id ?? '' }}" data-choice-attributes-old='@json(old('choice_attributes', isset($product) && $product->attributes != null ? json_decode($product->attributes) : []))'>
</div>
{{-- {{ dd($addons) }} --}}

@php
    $selectedCategories = old('category_ids', isset($product) ? $product->categories->pluck('id')->toArray() : []);
@endphp

<form action="{{ $action }}" method="POST" enctype="multipart/form-data" id="choice_form"
    data-ajax-submit="true"
    class="{{ empty($selectedCategories) ? 'seller-category-pending' : 'seller-category-ready' }}">
    @csrf
    @if (isset($product) && $product->id)
        <input type="hidden" name="id" value="{{ $product->id }}">
    @endif

    @if (isset($method) && $method == 'POST')
        @method('POST')
    @endif
    <div class="seller-listing-topbar">
        <div class="card seller-listing-title-card">
            <div class="card-body d-flex justify-content-between align-items-center w-100 p-2">
                <div>
                    <span class="seller-listing-kicker">{{ translate('Seller listing') }}</span>
                    <h3 class="mb-0">
                        {{ isset($product) && $product->id ? translate('Edit Your Product') : translate('Add Your Product') }}
                    </h3>
                </div>
                <button type="submit" name="button" value="publish"
                    class="btn btn-primary">{{ translate('Upload Product') }}</button>
            </div>
        </div>
    </div>

    <div class="seller-category-gate-card">
        <div class="seller-category-gate-icon">
            <i class="las la-sitemap"></i>
        </div>
        <div>
            <h4>{{ translate('Choose a product category first') }}</h4>
            <p>{{ translate('You can continue filling the listing, but choose a category so attributes, addons, shipping and services match this product.') }}
            </p>
        </div>
    </div>

    <div class="card seller-shipping-services-card">
        <div class="card-header bg-light border-bottom-0 pb-2">
            <h5 class="mb-0 h6 text-black">
                {{ translate('Shipping and services') }}
            </h5>
        </div>

        <div class="card-body">
            <div class="row gutters-2">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="seller-service-panel h-100">
                        <div class="seller-service-panel-title">
                            <h5 class="mb-0 h6 text-black">
                                {{ translate('Matched Shipping Charges') }}
                            </h5>
                        </div>
                        <div id="shipping-charges-wrapper">
                            @include('seller.product.products.partials.shipping-charges', [
                                'shippingCharges' =>
                                    isset($product) && $product->id
                                        ? getProductShippingCharges($product)
                                        : collect(),
                            ])
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="seller-service-panel h-100">
                        <div class="seller-service-panel-title">
                            <h5 class="mb-0 h6 text-black">
                                {{ translate('Delivery & Assembly Services') }}
                            </h5>
                        </div>
                        <div id="checkout-services-wrapper">
                            @include('seller.product.products.partials.checkout-services', [
                                'services' => $services ?? collect(),
                            ])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row gutters-5 seller-form-layout">
        <div class="col-lg-8 seller-main-stack">
            <input type="hidden" name="added_by" value="seller">
            <input type="hidden" name="old_values" value="{{ json_encode(old()) }}">

            @include('seller.product.products.partials.product-information-sec')

            <div class="card seller-price-stock-card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ 'Product Price and Stock' }}</h5>
                </div>
                <div class="card-body">
                    @php
                        $unit_price_enabled = (bool) old(
                            'unit_price_enabled',
                            trim((string) old('unit_price', $product->unit_price ?? '')) !== '',
                        );
                    @endphp
                    <div class="form-group row align-items-center">
                        <label class="col-md-3 col-from-label">
                            {{ 'Unit Price (GBP)' }} <span>£</span>
                        </label>
                        <div class="col-md-4 d-flex align-items-center">
                            <label class="mb-0 aiz-switch aiz-switch-success">
                                <input type="checkbox" id="toggleUnitPrice" name="unit_price_enabled" value="1"
                                    {{ $unit_price_enabled ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                            <span class="ml-2" id="toggleUnitPriceLabel">{{ translate('Show') }}</span>
                        </div>
                        <div class="col-md-4" id="unitPriceInputWrapper"
                            style="{{ $unit_price_enabled ? '' : 'display:none;' }}">
                            <input type="text" name="unit_price" inputmode="decimal"
                                placeholder="{{ translate('Unit price') }}"
                                value="{{ old('unit_price', $product->unit_price ?? '') }}" class="form-control"
                                oninput="
                            // allow only numbers and dot
                            this.value = this.value.replace(/[^0-9.]/g, '').slice(0, 5);
                            // allow only one dot
                            this.value = this.value.replace(/(\..*)\./g, '$1');
                            // block leading zero like 01, 00 (except 0.)
                            if (this.value.length > 1 && this.value.startsWith('0') && !this.value.startsWith('0.')) {
                                this.value = this.value.replace(/^0+/, '');
                            }
                        "
                                onblur="if(this.value > 99999) alert('Unit price cannot exceed 99999')"
                                onchange="update_sku()" id="unit_price_input">
                        </div>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            var toggle = document.getElementById('toggleUnitPrice');
                            var unitPriceInputWrapper = document.getElementById('unitPriceInputWrapper');
                            var label = document.getElementById('toggleUnitPriceLabel');
                            var unitPriceInput = document.getElementById('unit_price_input');

                            function updateUnitPriceVisibility() {
                                if (toggle.checked) {
                                    unitPriceInputWrapper.style.display = '';
                                    label.innerText = "{{ translate('Show') }}";
                                    // Enable the input (not required)
                                    if (unitPriceInput) {
                                        unitPriceInput.removeAttribute('disabled');
                                        unitPriceInput.tabIndex = 0;
                                    }
                                } else {
                                    unitPriceInputWrapper.style.display = 'none';
                                    label.innerText = "{{ translate('Hide') }}";
                                    // Keep the empty value submitted so update can save null.
                                    if (unitPriceInput) {
                                        unitPriceInput.value = '';
                                        unitPriceInput.removeAttribute('disabled');
                                        unitPriceInput.tabIndex = -1;
                                    }
                                }
                            }
                            toggle.addEventListener('change', updateUnitPriceVisibility);
                            updateUnitPriceVisibility();
                        });
                    </script>
                    <div class="form-group row">
                        <label class="col-md-3 control-label" for="start_date">{{ translate('Discount Date Range') }}
                        </label>
                        <div class="col-md-9">
                            @php
                                $discount_enabled = (bool) old(
                                    'discount_enabled',
                                    (float) old('discount', $product->discount ?? 0) > 0 ||
                                        !empty(old('date_range', $product->date_range ?? '')),
                                );
                            @endphp
                            <input type="text" class="form-control aiz-date-range" name="date_range"
                                id="date_range" value="{{ old('date_range', $product->date_range ?? '') }}"
                                placeholder="{{ translate('Select Date') }}" data-time-picker="true"
                                data-format="DD-MM-Y HH:mm" data-separator=" to " autocomplete="off"
                                {{ $discount_enabled ? '' : 'disabled' }}>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">
                            {{ translate('Discount') }}
                        </label>
                        <!-- Enable / Disable Button -->
                        <div class="mb-2 col-md-2 mb-md-0">
                            <label class="mb-0 aiz-switch aiz-switch-success">
                                <input id="discountToggleBtn" name="discount_enabled" onchange="toggleDiscount()"
                                    value="1" type="checkbox" {{ $discount_enabled ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <!-- Discount Input -->
                        <div class="col-md-4 discount-box" style="{{ $discount_enabled ? '' : 'display:none;' }}">
                            <input type="number" lang="en" min="0" step="0.01"
                                placeholder="{{ translate('Discount') }}" name="discount" id="discountInput"
                                value="{{ old('discount', $product->discount ?? '') }}" class="form-control"
                                oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,4)">
                        </div>
                        <!-- Discount Type -->
                        <div class="col-md-6 discount-box" style="{{ $discount_enabled ? '' : 'display:none;' }}">
                            <select class="form-control aiz-selectpicker" name="discount_type">
                                <option value="amount">{{ translate('Flat') }}</option>
                                <option value="percent">{{ translate('Percent') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <small class="text-muted discount">
                                <span>(</span>{{ translate('If you do want to sell the items in discounted price or
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                clearance') }}<span>)</span>
                            </small>
                        </div>
                    </div>
                    <div id="show-hide-div">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Quantity') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <input type="number" lang="en" min="1" step="1"
                                    placeholder="{{ translate('Quantity') }}" name="current_stock"
                                    value="{{ old('current_stock', $product->current_stock ?? 1) }}"
                                    class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">
                                {{ translate('SKU') }} <span class="text-danger"></span>
                            </label>
                            <div class="col-md-6">
                                <input type="text" placeholder="{{ translate('SKU') }}" name="sku"
                                    value="{{ old('sku', $product->sku ?? '') }}" class="form-control">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card" style="display:none;">
                <div class="card-header bg-light border-bottom-0 pb-2">
                    <h5 class="mb-0 h6 text-black">{{ translate('SEO Meta Tags') }}</h5>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">{{ translate('Meta Title') }}</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="meta_title"
                                placeholder="{{ translate('Meta Title') }}"
                                value="{{ old('meta_title', $product->meta_title ?? '') }}">
                        </div>
                    </div>
                    <div class="form-group row ">
                        <label class="col-md-3 col-from-label">{{ translate('Description') }}</label>
                        <div class="col-md-8">
                            <textarea name="meta_description" rows="8" class="form-control">{{ old('meta_description', $product->meta_description ?? '') }}</textarea>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <div class="col-lg-4 seller-sidebar-stack">
            <div class="card seller-category-card">
                <div class="card-header bg-light border-bottom-0 pb-2">
                    <h5 class="mb-0 h6 text-black">{{ translate('Product Category') }}</h5>
                    <h6 class="float-right mb-0 fs-13">
                        {{ translate('Select Main') }}
                        <span class="position-relative main-category-info-icon">
                            <i class="las la-question-circle fs-18 text-info"></i>
                            <span
                                class="p-2 border main-category-info bg-soft-info position-absolute d-none">{{ translate('This will be used for commission based calculations and homepage category
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                wise product Show.') }}</span>
                        </span>
                    </h6>
                </div>
                <input type="hidden" name="old_categories_string" value="{{ implode(',', $selectedCategories) }}">

                <input type="hidden" name="category_id" id="main_category_id"
                    value="{{ old('category_id', $product->category_id ?? '') }}">

                <div class="card-body">

                    <div class="mb-2 d-flex justify-content-between">

                        <button type="button" class="btn btn-sm btn-soft-primary px-2 py-1"
                            onclick="$('#treeview').hummingbird('expandAll')">

                            {{ translate('Expand All') }}

                        </button>

                        <button type="button" class="btn btn-sm btn-soft-secondary px-2 py-1"
                            onclick="$('#treeview').hummingbird('collapseAll')">

                            {{ translate('Collapse All') }}

                        </button>

                    </div>

                    <div class="overflow-auto c-scrollbar-light category-tree-scroll" style="max-height: 450px;">

                        <ul id="treeview" class="hummingbird-treeview-converter list-unstyled"
                            data-checkbox-name="category_ids[]">

                            @foreach ($categories as $category)
                                <li id="{{ $category->id }}">
                                    {{ $category->getTranslation('name') }}
                                </li>

                                @foreach ($category->childrenCategories as $childCategory)
                                    @include('backend.product.products.child_category', [
                                        'child_category' => $childCategory,
                                    ])
                                @endforeach
                            @endforeach

                        </ul>

                    </div>

                </div>
            </div>

            <div class="card seller-low-stock-card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Low Stock Quantity Warning') }}</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3 form-group">
                        <label for="name">
                            {{ translate('Quantity') }}
                        </label>
                        <input type="number" name="low_stock_quantity"
                            value="{{ old('low_stock_quantity', $product->low_stock_quantity ?? 1) }}" min="1"
                            step="1" class="form-control"
                            oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,3)">
                    </div>
                </div>
            </div>
            <div class="card seller-stock-visibility">
                <div class="card-header">
                    <h5 class="mb-0 h6">
                        {{ translate('Stock Visibility State') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-md-6 col-from-label">{{ translate('Show Stock Quantity') }}</label>
                        <div class="col-md-6">
                            <label class="mb-0 aiz-switch aiz-switch-success">
                                <input type="radio" name="stock_visibility_state" value="quantity" checked
                                    {{ old('stock_visibility_state', $product->stock_visibility_state ?? '') == 'quantity' ? 'checked' : '' }}>
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6 col-from-label">{{ translate('Show Stock With Text Only') }}</label>
                        <div class="col-md-6">
                            <label class="mb-0 aiz-switch aiz-switch-success">
                                <input type="radio" name="stock_visibility_state" value="text"
                                    {{ old('stock_visibility_state', $product->stock_visibility_state ?? '') == 'text' ? 'checked' : '' }}>
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6 col-from-label">{{ translate('Hide Stock') }}</label>
                        <div class="col-md-6">
                            <label class="mb-0 aiz-switch aiz-switch-success">
                                <input type="radio" name="stock_visibility_state" value="hide"
                                    {{ old('stock_visibility_state', $product->stock_visibility_state ?? '') == 'hide' ? 'checked' : '' }}>
                                <span></span>
                            </label>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card" style="display:none;">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('VAT & Tax') }}</h5>
                </div>
                <div class="card-body">
                    @foreach (\App\Models\Tax::where('tax_status', 1)->get() as $tax)
                        <label for="name">
                            {{ $tax->name }}
                            <input type="hidden" value="{{ $tax->id }}" name="tax_id[]">
                        </label>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <input type="number" lang="en" min="0"
                                    value="{{ is_array(old('tax')) ? old('tax')[$loop->index] ?? ($product->tax ?? 0) : old('tax', $product->tax ?? 0) }}"
                                    step="0.01" placeholder="{{ translate('Tax') }}" name="tax[]"
                                    class="form-control" required>

                            </div>
                            <div class="form-group col-md-6">
                                <select class="form-control aiz-selectpicker" name="tax_type[]">
                                    <option value="amount">{{ translate('Flat') }}</option>
                                    <option value="percent">{{ translate('Percent') }}</option>
                                </select>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card seller-dimensions-card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 h6 mb-0" id="dimension-title">Dimensions (<span id="unit-label">Inches</span>)
                    </h5>
                    <div>
                        @php
                            $dimensions_enabled =
                                (int) old('dimensions_enabled', $product->dimensions_enabled ?? 0) === 1;
                        @endphp
                        <!-- Toggle to Show/Hide and Save/Not Save Dimensions -->
                        <label class="mb-0 aiz-switch aiz-switch-success mr-2" title="Enable Dimensions">
                            <input type="checkbox" id="toggle-dimensions" name="dimensions_enabled" value="1"
                                @checked($dimensions_enabled && $dimensions_enabled == 1)>

                            <span></span>
                        </label>
                        <!-- Toggle to switch between Inches/Centimeters -->
                        <label class="mb-0 aiz-switch aiz-switch-success" title="Switch to Centimeters">
                            <input type="hidden" name="dimensions_unit" value="in">

                            <input type="checkbox" id="toggle-units" name="dimensions_unit" value="cm"
                                @checked(old('dimensions_unit', $product->dimensions_unit ?? 'in') == 'cm')>
                            <span>

                            </span>
                        </label>
                        <span style="font-size: 12px;color:var(--secondary);" id="unit-label-toggle">
                            <span id="show-inch"
                                {{ old('dimensions_unit', $product->dimensions_unit ?? 'in') == 'cm' ? 'style=display:none;' : '' }}>IN</span>
                            <span id="show-cm"
                                {{ old('dimensions_unit', $product->dimensions_unit ?? 'in') == 'cm' ? '' : 'style=display:none;' }}>CM</span>
                        </span>
                    </div>
                </div>
                <div class="card-body" id="dimensions-section"
                    style="{{ $dimensions_enabled ? '' : 'display:none;' }}">
                    <input type="hidden" name="dimensions_unit" id="dimensions_unit_input"
                        value="{{ old('dimensions_unit', $product->dimensions_unit ?? 'in') }}">
                    <div class="mb-3 form-group">
                        <label for="product_length">
                            Length <span class="text-danger"></span>
                        </label>
                        <div class="input-group">
                            <input type="text" name="product_length"
                                value="{{ old('product_length', $product->product_length ?? '') }}"
                                class="form-control" id="product_length" placeholder="Length"
                                {{ old('dimensions_enabled', !empty($product->product_length) ? 'required' : '') }}>
                            <div class="input-group-append">
                                <span class="input-group-text" id="length-unit-addon">
                                    {{ old('dimensions_unit', $product->dimensions_unit ?? 'in') == 'cm' ? 'cm' : 'in' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 form-group">
                        <label for="product_breadth">
                            Width <span class="text-danger"></span>
                        </label>
                        <div class="input-group">
                            <input type="text" name="product_breadth"
                                value="{{ old('product_breadth', $product->product_breadth ?? '') }}"
                                class="form-control" id="product_breadth" placeholder="Width"
                                {{ old('dimensions_enabled', !empty($product->product_breadth) ? 'required' : '') }}>
                            <div class="input-group-append">
                                <span class="input-group-text" id="breadth-unit-addon">
                                    {{ old('dimensions_unit', $product->dimensions_unit ?? 'in') == 'cm' ? 'cm' : 'in' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 form-group">
                        <label for="product_height">
                            Height <span class="text-danger"></span>
                        </label>
                        <div class="input-group">
                            <input type="text" name="product_height"
                                value="{{ old('product_height', $product->product_height ?? '') }}"
                                class="form-control" id="product_height" placeholder="Height"
                                {{ old('dimensions_enabled', !empty($product->product_height) ? 'required' : '') }}>
                            <div class="input-group-append">
                                <span class="input-group-text" id="height-unit-addon">
                                    {{ old('dimensions_unit', $product->dimensions_unit ?? 'in') == 'cm' ? 'cm' : 'in' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 form-group">
                        <label for="weight">
                            Weight <small id="weight-unit-label">IN Kg</small><span class="text-danger"></span>
                        </label>
                        <input type="text" class="form-control" name="weight"
                            value="{{ old('weight', $product->weight ?? '0.00') }}" id="weight"
                            placeholder="0.00"
                            {{ old('dimensions_enabled', !empty($product->weight) ? 'required' : '') }}>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var dimensionsToggle = document.getElementById('toggle-dimensions');
                    var dimensionsSection = document.getElementById('dimensions-section');
                    var requiredFields = dimensionsSection.querySelectorAll(
                        'input[name="product_length"],input[name="product_breadth"],input[name="product_height"],input[name="weight"]'
                    );
                    var unitToggle = document.getElementById('toggle-units');
                    var unitLabel = document.getElementById('unit-label');
                    var unitLabelToggle = document.getElementById('unit-label-toggle');
                    var showInch = document.getElementById('show-inch');
                    var showCm = document.getElementById('show-cm');
                    var unitInput = document.getElementById('dimensions_unit_input');
                    var weightUnitLabel = document.getElementById('weight-unit-label');
                    var lengthUnitAddon = document.getElementById('length-unit-addon');
                    var breadthUnitAddon = document.getElementById('breadth-unit-addon');
                    var heightUnitAddon = document.getElementById('height-unit-addon');

                    function updateUnitsUI() {
                        if (unitToggle.checked) {
                            unitLabel.textContent = "Centimeters";
                            unitInput.value = 'cm';
                            showInch.style.display = 'none';
                            showCm.style.display = '';
                            lengthUnitAddon.textContent = 'cm';
                            breadthUnitAddon.textContent = 'cm';
                            heightUnitAddon.textContent = 'cm';
                        } else {
                            unitLabel.textContent = "Inches";
                            unitInput.value = 'in';
                            showInch.style.display = '';
                            showCm.style.display = 'none';
                            lengthUnitAddon.textContent = 'in';
                            breadthUnitAddon.textContent = 'in';
                            heightUnitAddon.textContent = 'in';
                        }
                        // Weight unit is always Kg in this UI
                        weightUnitLabel.textContent = 'IN Kg';
                    }

                    function updateDisplayByToggle() {
                        if (dimensionsToggle.checked) {
                            dimensionsSection.style.display = '';
                            requiredFields.forEach(function(input) {
                                input.required = true;
                            });
                        } else {
                            dimensionsSection.style.display = 'none';
                            requiredFields.forEach(function(input) {
                                input.required = false;
                            });
                        }
                    }
                    // Initialize on page load
                    updateDisplayByToggle();
                    updateUnitsUI();
                    dimensionsToggle.addEventListener('change', updateDisplayByToggle);
                    unitToggle.addEventListener('change', updateUnitsUI);
                });
            </script>


        </div>

    </div> {{-- ✅ END row gutters-5 --}}

    @include('seller.product.products.partials.seller-product-variation')
    @include('seller.product.products.partials.scripts.product-variation-script')

    <div class="seller-addons-card-wrapper">
        <div class="card">
            @include('seller.product.products.partials.addons', [
                'addons' => old('addons', $addons ?? []),
                'oldAddonsJson' => old('addons', $addons ?? []),
            ])
        </div>
    </div>

    <div class="flex flex-row card align-items-center seller-action-card" style="justify-content: end !important;">
        <div class="card-body d-flex justify-content-between align-items-center w-100"
            style="justify-content: end !important;">
            <button type="submit" name="button" value="publish"
                class="btn btn-primary">{{ translate('Upload Product') }}</button>
        </div>
    </div>
</form>


@section('script')
    <!-- Fallback-friendly script loading with sequential guarantee -->
    <script>
        function loadScriptSequentially(urls, checkSuccess, callback, index = 0) {
            if (index >= urls.length) {
                console.error("Failed to load script from any of the sources:", urls);
                if (callback) callback(false);
                return;
            }

            if (checkSuccess && checkSuccess()) {
                if (callback) callback(true);
                return;
            }

            let src = urls[index];
            let script = document.createElement('script');
            script.src = src;
            script.async = false;
            script.onload = function() {
                if (!checkSuccess || checkSuccess()) {
                    console.log("Successfully loaded script from: " + src);
                    if (callback) callback(true);
                } else {
                    console.warn("Script loaded from " + src + " but validation check failed. Trying next...");
                    loadScriptSequentially(urls, checkSuccess, callback, index + 1);
                }
            };
            script.onerror = function() {
                console.warn("Failed to load script from: " + src + ". Trying next...");
                loadScriptSequentially(urls, checkSuccess, callback, index + 1);
            };
            document.head.appendChild(script);
        }

        // Initialize script loading once jQuery is ready
        if (typeof jQuery !== 'undefined') {
            initProductFormScripts();
        } else {
            document.addEventListener('DOMContentLoaded', function() {
                initProductFormScripts();
            });
        }

        function initProductFormScripts() {
            if (typeof jQuery === 'undefined') {
                setTimeout(initProductFormScripts, 50);
                return;
            }

            @php
                $sellerProductFormJsVersion = file_exists(public_path('assets/js/seller-product-form.js')) ? filemtime(public_path('assets/js/seller-product-form.js')) : time();
            @endphp
            const hummingbirdUrls = [
                "{{ static_asset('assets/js/hummingbird-treeview.js') }}",
                "{{ asset('assets/js/hummingbird-treeview.js') }}",
                "/assets/js/hummingbird-treeview.js",
                "{{ static_asset('js/hummingbird-treeview.js') }}",
                "{{ asset('js/hummingbird-treeview.js') }}",
                "/js/hummingbird-treeview.js"
            ];

            const addonUrls = [
                "{{ static_asset('assets/js/product-addon.js') }}",
                "{{ asset('assets/js/product-addon.js') }}",
                "/assets/js/product-addon.js",
                "{{ static_asset('js/product-addon.js') }}",
                "{{ asset('js/product-addon.js') }}",
                "/js/product-addon.js"
            ];

            const formUrls = [
                "{{ static_asset('assets/js/seller-product-form.js') }}?v={{ $sellerProductFormJsVersion }}",
                "{{ asset('assets/js/seller-product-form.js') }}?v={{ $sellerProductFormJsVersion }}",
                "/assets/js/seller-product-form.js?v={{ $sellerProductFormJsVersion }}",
                "{{ static_asset('js/seller-product-form.js') }}?v={{ $sellerProductFormJsVersion }}",
                "{{ asset('js/seller-product-form.js') }}?v={{ $sellerProductFormJsVersion }}",
                "/js/seller-product-form.js?v={{ $sellerProductFormJsVersion }}"
            ];

            loadScriptSequentially(hummingbirdUrls, function() {
                return typeof jQuery !== 'undefined' && jQuery.fn && jQuery.fn.hummingbird;
            }, function(success) {
                loadScriptSequentially(addonUrls, null, function(success) {
                    loadScriptSequentially(formUrls, null, function(success) {
                        console.log("All custom product form JS files loaded successfully!");
                        if (typeof jQuery !== 'undefined') {
                            jQuery(document).trigger('seller-scripts-loaded');
                        }
                    });
                });
            });
        }
    </script>
@endsection
