<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Certificate Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for certificate generation
    | and management.
    |
    */

    // Organization settings
    'organization_name' => env('CERTIFICATE_ORG_NAME', config('app.name')),
    'organization_logo' => env('CERTIFICATE_ORG_LOGO', 'certificates/logo.png'),
    'organization_tagline' => env('CERTIFICATE_ORG_TAGLINE', 'Excellence in Education'),

    // Signature settings
    'signature_name' => env('CERTIFICATE_SIGNATURE_NAME', 'Authorized Signature'),
    'signature_title' => env('CERTIFICATE_SIGNATURE_TITLE', 'Course Director'),
    'signature_image' => env('CERTIFICATE_SIGNATURE_IMAGE', 'certificates/signature.png'),

    // Certificate numbering
    'number_prefix' => env('CERTIFICATE_NUMBER_PREFIX', 'CERT'),
    'number_format' => env('CERTIFICATE_NUMBER_FORMAT', '{prefix}-{year}-{course_id}-{enrollment_id}'),

    // Default template
    'default_template' => env('CERTIFICATE_DEFAULT_TEMPLATE', 'classic'),

    // Security features
    'enable_qr_code' => env('CERTIFICATE_ENABLE_QR', true),
    'enable_barcode' => env('CERTIFICATE_ENABLE_BARCODE', true),
    'enable_watermark' => env('CERTIFICATE_ENABLE_WATERMARK', true),

    // Verification settings
    'verification_url' => env('CERTIFICATE_VERIFICATION_URL', null),

    // Storage settings
    'storage_disk' => env('CERTIFICATE_STORAGE_DISK', 'public'),
    'qr_codes_path' => env('CERTIFICATE_QR_PATH', 'certificates/qr_codes'),
    'barcodes_path' => env('CERTIFICATE_BARCODE_PATH', 'certificates/barcodes'),
];
