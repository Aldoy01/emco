<?php

return [
    'usd_to_idr_rate' => (float) env('USD_TO_IDR_RATE', 16000),
    'sales_email' => env('SALES_EMAIL', 'aldiansyah@tramatek.id'),
    'sales_whatsapp' => env('SALES_WHATSAPP', '6285188337500'),
    'hide_commercial_values' => filter_var(env('EMKO_HIDE_COMMERCIAL_VALUES', true), FILTER_VALIDATE_BOOLEAN),
    'product_upload_path' => env('PRODUCT_UPLOAD_PATH', public_path('uploads/products')),
    'product_upload_url' => trim(env('PRODUCT_UPLOAD_URL', 'uploads/products'), '/'),
    'invoice_tax_percent' => (float) env('EMKO_INVOICE_TAX_PERCENT', 11),
];
