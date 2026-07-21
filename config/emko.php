<?php

return [
    'usd_to_idr_rate' => (float) env('USD_TO_IDR_RATE', 16000),
    'sales_email' => env('SALES_EMAIL', 'aldiansyah@tramatek.id'),
    'sales_whatsapp' => env('SALES_WHATSAPP', '6285188337500'),
    'office_address' => env('EMKO_OFFICE_ADDRESS', 'Indonesia'),
    'office_email' => env('EMKO_OFFICE_EMAIL', env('SALES_EMAIL', 'aldiansyah@tramatek.id')),
    'office_phone' => env('EMKO_OFFICE_PHONE', env('SALES_WHATSAPP', '6285188337500')),
    'work_email' => env('EMKO_WORK_EMAIL', env('SALES_EMAIL', 'aldiansyah@tramatek.id')),
    'work_phone' => env('EMKO_WORK_PHONE', env('SALES_WHATSAPP', '6285188337500')),
    'office_hours' => env('EMKO_OFFICE_HOURS', 'Senin - Jumat, 09.00 - 17.00'),
    'map_url' => env('EMKO_MAP_URL', 'https://www.google.com/maps?q=GKM%20Green%20Tower%20Jakarta&output=embed'),
    'map_link' => env('EMKO_MAP_LINK', 'https://www.google.com/maps/search/?api=1&query=GKM%20Green%20Tower%20Jakarta'),
    'hide_commercial_values' => filter_var(env('EMKO_HIDE_COMMERCIAL_VALUES', true), FILTER_VALIDATE_BOOLEAN),
    'product_upload_path' => env('PRODUCT_UPLOAD_PATH', public_path('uploads/products')),
    'product_upload_url' => trim(env('PRODUCT_UPLOAD_URL', 'uploads/products'), '/'),
    'invoice_tax_percent' => (float) env('EMKO_INVOICE_TAX_PERCENT', 11),
];
