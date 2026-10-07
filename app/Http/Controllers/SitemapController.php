<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Response;
use Throwable;

class SitemapController extends Controller
{
    public function sitemap(): Response
    {
        $urls = collect([
            [
                'loc' => route('home'),
                'lastmod' => now(),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ],
        ]);

        try {
            Store::query()
                ->where('active', true)
                ->latest('updated_at')
                ->get(['slug', 'updated_at'])
                ->each(function (Store $store) use ($urls): void {
                    $urls->push([
                        'loc' => route('stores.show', ['store' => $store->slug]),
                        'lastmod' => $store->updated_at,
                        'changefreq' => 'weekly',
                        'priority' => '0.8',
                    ]);
                });
        } catch (Throwable) {
            //
        }

        return response()
            ->view('seo.sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }

    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Disallow: /dashboard',
            'Disallow: /profile',
            'Disallow: /productos',
            'Disallow: /mi-tienda',
            'Disallow: /login',
            'Disallow: /register',
            'Disallow: /forgot-password',
            'Disallow: /reset-password',
            'Disallow: /verify-email',
            'Disallow: /confirm-password',
            'Disallow: /email/verification-notification',
            '',
            'Sitemap: '.url('/sitemap.xml'),
            '',
        ];

        return response(implode("\n", $lines), 200)
            ->header('Content-Type', 'text/plain');
    }
}
