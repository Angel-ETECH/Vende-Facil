<?php

return [
    'site_name' => env('SEO_SITE_NAME', 'VendeFácil'),
    'title' => env('SEO_TITLE', 'VendeFácil | Marketplace de negocios locales en Perú'),
    'description' => env(
        'SEO_DESCRIPTION',
        'Descubre negocios locales, encuentra productos únicos y compra directamente por WhatsApp en VendeFácil.'
    ),
    'image' => env('SEO_IMAGE', 'images/seo/vendefacil-home.png'),
    'locale' => env('SEO_LOCALE', 'es_PE'),
    'country' => env('SEO_COUNTRY', 'PE'),
    'twitter_card' => env('SEO_TWITTER_CARD', 'summary_large_image'),
];
