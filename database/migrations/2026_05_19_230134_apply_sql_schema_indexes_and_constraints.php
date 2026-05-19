<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        foreach (array (
  0 => 'ALTER TABLE `addons`
  ADD PRIMARY KEY (`id`);',
  1 => 'ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`);',
  2 => 'ALTER TABLE `app_translations`
  ADD PRIMARY KEY (`id`);',
  3 => 'ALTER TABLE `attributes`
  ADD PRIMARY KEY (`id`);',
  4 => 'ALTER TABLE `attribute_category`
  ADD PRIMARY KEY (`id`);',
  5 => 'ALTER TABLE `attribute_translations`
  ADD PRIMARY KEY (`id`);',
  6 => 'ALTER TABLE `attribute_values`
  ADD PRIMARY KEY (`id`);',
  7 => 'ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`);',
  8 => 'ALTER TABLE `blog_categories`
  ADD PRIMARY KEY (`id`);',
  9 => 'ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);',
  10 => 'ALTER TABLE `brand_translations`
  ADD PRIMARY KEY (`id`);',
  11 => 'ALTER TABLE `business_settings`
  ADD PRIMARY KEY (`id`);',
  12 => 'ALTER TABLE `carriers`
  ADD PRIMARY KEY (`id`);',
  13 => 'ALTER TABLE `carrier_ranges`
  ADD PRIMARY KEY (`id`);',
  14 => 'ALTER TABLE `carrier_range_prices`
  ADD PRIMARY KEY (`id`);',
  15 => 'ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`);',
  16 => 'ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `slug` (`slug`);',
  17 => 'ALTER TABLE `category_translations`
  ADD PRIMARY KEY (`id`);',
  18 => 'ALTER TABLE `checkout_services`
  ADD PRIMARY KEY (`id`);',
  19 => 'ALTER TABLE `checkout_service_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `checkout_service_categories_service_id_index` (`checkout_service_id`),
  ADD KEY `checkout_service_categories_category_id_index` (`category_id`);',
  20 => 'ALTER TABLE `checkout_service_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `checkout_service_id` (`checkout_service_id`);',
  21 => 'ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);',
  22 => 'ALTER TABLE `city_translations`
  ADD PRIMARY KEY (`id`);',
  23 => 'ALTER TABLE `colors`
  ADD PRIMARY KEY (`id`);',
  24 => 'ALTER TABLE `combined_orders`
  ADD PRIMARY KEY (`id`);',
  25 => 'ALTER TABLE `commission_histories`
  ADD PRIMARY KEY (`id`);',
  26 => 'ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`);',
  27 => 'ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);',
  28 => 'ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`);',
  29 => 'ALTER TABLE `coupon_usages`
  ADD PRIMARY KEY (`id`);',
  30 => 'ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);',
  31 => 'ALTER TABLE `customer_packages`
  ADD PRIMARY KEY (`id`);',
  32 => 'ALTER TABLE `customer_package_payments`
  ADD PRIMARY KEY (`id`);',
  33 => 'ALTER TABLE `customer_package_translations`
  ADD PRIMARY KEY (`id`);',
  34 => 'ALTER TABLE `customer_products`
  ADD PRIMARY KEY (`id`);',
  35 => 'ALTER TABLE `customer_product_translations`
  ADD PRIMARY KEY (`id`);',
  36 => 'ALTER TABLE `firebase_notifications`
  ADD PRIMARY KEY (`id`);',
  37 => 'ALTER TABLE `flash_deals`
  ADD PRIMARY KEY (`id`);',
  38 => 'ALTER TABLE `flash_deal_products`
  ADD PRIMARY KEY (`id`);',
  39 => 'ALTER TABLE `flash_deal_translations`
  ADD PRIMARY KEY (`id`);',
  40 => 'ALTER TABLE `home_categories`
  ADD PRIMARY KEY (`id`);',
  41 => 'ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);',
  42 => 'ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);',
  43 => 'ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);',
  44 => 'ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);',
  45 => 'ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);',
  46 => 'ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);',
  47 => 'ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`);',
  48 => 'ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);',
  49 => 'ALTER TABLE `page_translations`
  ADD PRIMARY KEY (`id`);',
  50 => 'ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);',
  51 => 'ALTER TABLE `payku_payments`
  ADD UNIQUE KEY `payku_payments_transaction_id_unique` (`transaction_id`);',
  52 => 'ALTER TABLE `payku_transactions`
  ADD UNIQUE KEY `payku_transactions_id_unique` (`id`),
  ADD UNIQUE KEY `payku_transactions_order_unique` (`order`);',
  53 => 'ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);',
  54 => 'ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);',
  55 => 'ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);',
  56 => 'ALTER TABLE `pickup_points`
  ADD PRIMARY KEY (`id`);',
  57 => 'ALTER TABLE `pickup_point_translations`
  ADD PRIMARY KEY (`id`);',
  58 => 'ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `tags` (`tags`(255)),
  ADD KEY `unit_price` (`unit_price`),
  ADD KEY `created_at` (`created_at`);',
  59 => 'ALTER TABLE `product_addons`
  ADD PRIMARY KEY (`id`);',
  60 => 'ALTER TABLE `product_addons_global`
  ADD PRIMARY KEY (`id`);',
  61 => 'ALTER TABLE `product_addon_options`
  ADD PRIMARY KEY (`id`);',
  62 => 'ALTER TABLE `product_addon_options_global`
  ADD PRIMARY KEY (`id`);',
  63 => 'ALTER TABLE `product_queries`
  ADD PRIMARY KEY (`id`);',
  64 => 'ALTER TABLE `product_stocks`
  ADD PRIMARY KEY (`id`);',
  65 => 'ALTER TABLE `product_taxes`
  ADD PRIMARY KEY (`id`);',
  66 => 'ALTER TABLE `product_translations`
  ADD PRIMARY KEY (`id`);',
  67 => 'ALTER TABLE `proxypay_payments`
  ADD PRIMARY KEY (`id`);',
  68 => 'ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);',
  69 => 'ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);',
  70 => 'ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);',
  71 => 'ALTER TABLE `role_translations`
  ADD PRIMARY KEY (`id`);',
  72 => 'ALTER TABLE `searches`
  ADD PRIMARY KEY (`id`);',
  73 => 'ALTER TABLE `sellers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);',
  74 => 'ALTER TABLE `seller_withdraw_requests`
  ADD PRIMARY KEY (`id`);',
  75 => 'ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);',
  76 => 'ALTER TABLE `shipping_costs`
  ADD PRIMARY KEY (`id`);',
  77 => 'ALTER TABLE `shipping_rates`
  ADD PRIMARY KEY (`id`);',
  78 => 'ALTER TABLE `shops`
  ADD PRIMARY KEY (`id`);',
  79 => 'ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`);',
  80 => 'ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);',
  81 => 'ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);',
  82 => 'ALTER TABLE `taxes`
  ADD PRIMARY KEY (`id`);',
  83 => 'ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);',
  84 => 'ALTER TABLE `ticket_replies`
  ADD PRIMARY KEY (`id`);',
  85 => 'ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);',
  86 => 'ALTER TABLE `translations`
  ADD PRIMARY KEY (`id`);',
  87 => 'ALTER TABLE `uploads`
  ADD PRIMARY KEY (`id`);',
  88 => 'ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);',
  89 => 'ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`);',
  90 => 'ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`);',
  91 => 'ALTER TABLE `zones`
  ADD PRIMARY KEY (`id`);',
  92 => 'ALTER TABLE `addons`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;',
  93 => 'ALTER TABLE `addresses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;',
  94 => 'ALTER TABLE `app_translations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;',
  95 => 'ALTER TABLE `attributes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;',
  96 => 'ALTER TABLE `attribute_category`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;',
  97 => 'ALTER TABLE `attribute_translations`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;',
  98 => 'ALTER TABLE `attribute_values`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;',
  99 => 'ALTER TABLE `blogs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;',
  100 => 'ALTER TABLE `blog_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;',
  101 => 'ALTER TABLE `brands`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;',
  102 => 'ALTER TABLE `brand_translations`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;',
  103 => 'ALTER TABLE `business_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=178;',
  104 => 'ALTER TABLE `carriers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;',
  105 => 'ALTER TABLE `carrier_ranges`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;',
  106 => 'ALTER TABLE `carrier_range_prices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;',
  107 => 'ALTER TABLE `carts`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;',
  108 => 'ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=247;',
  109 => 'ALTER TABLE `category_translations`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=247;',
  110 => 'ALTER TABLE `checkout_services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;',
  111 => 'ALTER TABLE `checkout_service_categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;',
  112 => 'ALTER TABLE `checkout_service_product`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;',
  113 => 'ALTER TABLE `cities`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48357;',
  114 => 'ALTER TABLE `city_translations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;',
  115 => 'ALTER TABLE `colors`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;',
  116 => 'ALTER TABLE `combined_orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=570;',
  117 => 'ALTER TABLE `commission_histories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=172;',
  118 => 'ALTER TABLE `conversations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;',
  119 => 'ALTER TABLE `countries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=297;',
  120 => 'ALTER TABLE `coupons`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;',
  121 => 'ALTER TABLE `coupon_usages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;',
  122 => 'ALTER TABLE `currencies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;',
  123 => 'ALTER TABLE `customer_packages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;',
  124 => 'ALTER TABLE `customer_package_payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;',
  125 => 'ALTER TABLE `customer_package_translations`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;',
  126 => 'ALTER TABLE `customer_products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;',
  127 => 'ALTER TABLE `customer_product_translations`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;',
  128 => 'ALTER TABLE `firebase_notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;',
  129 => 'ALTER TABLE `flash_deals`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;',
  130 => 'ALTER TABLE `flash_deal_products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;',
  131 => 'ALTER TABLE `flash_deal_translations`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;',
  132 => 'ALTER TABLE `home_categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;',
  133 => 'ALTER TABLE `languages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;',
  134 => 'ALTER TABLE `messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;',
  135 => 'ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=619;',
  136 => 'ALTER TABLE `order_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=650;',
  137 => 'ALTER TABLE `pages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;',
  138 => 'ALTER TABLE `page_translations`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;',
  139 => 'ALTER TABLE `payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;',
  140 => 'ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=216;',
  141 => 'ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;',
  142 => 'ALTER TABLE `pickup_points`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;',
  143 => 'ALTER TABLE `pickup_point_translations`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;',
  144 => 'ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1172;',
  145 => 'ALTER TABLE `product_addons`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;',
  146 => 'ALTER TABLE `product_addons_global`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;',
  147 => 'ALTER TABLE `product_addon_options`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1049;',
  148 => 'ALTER TABLE `product_addon_options_global`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;',
  149 => 'ALTER TABLE `product_queries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;',
  150 => 'ALTER TABLE `product_stocks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5453;',
  151 => 'ALTER TABLE `product_taxes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3895;',
  152 => 'ALTER TABLE `product_translations`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=905;',
  153 => 'ALTER TABLE `proxypay_payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;',
  154 => 'ALTER TABLE `reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;',
  155 => 'ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;',
  156 => 'ALTER TABLE `role_translations`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;',
  157 => 'ALTER TABLE `searches`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=204;',
  158 => 'ALTER TABLE `sellers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;',
  159 => 'ALTER TABLE `seller_withdraw_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;',
  160 => 'ALTER TABLE `shipping_costs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;',
  161 => 'ALTER TABLE `shipping_rates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;',
  162 => 'ALTER TABLE `shops`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=279;',
  163 => 'ALTER TABLE `staff`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;',
  164 => 'ALTER TABLE `states`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4122;',
  165 => 'ALTER TABLE `subscribers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=397;',
  166 => 'ALTER TABLE `taxes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;',
  167 => 'ALTER TABLE `tickets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;',
  168 => 'ALTER TABLE `ticket_replies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;',
  169 => 'ALTER TABLE `transactions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;',
  170 => 'ALTER TABLE `translations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27998;',
  171 => 'ALTER TABLE `uploads`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11560;',
  172 => 'ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=605;',
  173 => 'ALTER TABLE `wallets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;',
  174 => 'ALTER TABLE `wishlists`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;',
  175 => 'ALTER TABLE `zones`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;',
  176 => 'ALTER TABLE `checkout_service_categories`
  ADD CONSTRAINT `checkout_service_categories_category_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `checkout_service_categories_service_foreign` FOREIGN KEY (`checkout_service_id`) REFERENCES `checkout_services` (`id`) ON DELETE CASCADE;',
  177 => 'ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;',
  178 => 'ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;',
  179 => 'ALTER TABLE `payku_payments`
  ADD CONSTRAINT `payku_payments_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `payku_transactions` (`id`);',
  180 => 'ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;',
) as $statement) {
            try {
                DB::statement($statement);
            } catch (QueryException $exception) {
                // The SQL dump may overlap with existing project migrations.
                // Keep this schema migration idempotent by skipping duplicate keys/columns.
            }
        }
    }

    public function down(): void
    {
        // Index and constraint rollback is handled by table drops in each table migration.
    }
};
