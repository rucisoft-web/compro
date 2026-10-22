<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public const DEFAULTS = [
        'site_name' => 'Rucisoft Indonesia',
        'whatsapp_number' => '6281199892939',
        'whatsapp_display' => '0811-9989-2939',
        'whatsapp_default_message' => 'Halo Rucisoft, saya ingin konsultasi kebutuhan organisasi kami.',
        'email' => 'rucisoft@gmail.com',
        'address' => 'Indonesia',
        'hero_eyebrow' => 'FOKUS RUCISOFT 2026',
        'hero_title' => 'Strategi terlaksana. Kinerja terukur.',
        'hero_subtitle' => 'Solusi digital untuk strategi, kepatuhan, layanan korporat, dan pengetahuan perusahaan melalui buku, workshop, dan software terpadu.',
        'hero_badges' => 'Berbasis Odoo 18, Audit trail, Pendampingan end-to-end',
        'dashboard_alert' => '12 KPI memerlukan perhatian',
        'about_title' => 'Transformasi digital dengan tujuan yang jelas.',
        'about_body' => 'Kami memadukan pengalaman manajemen, proses bisnis, teknologi, dan pendampingan. Software harus menjadi alat pengendalian organisasi, bukan sekadar aplikasi.',
        'cta_title' => 'Diskusikan kebutuhan organisasi bersama Rucisoft.',
        'meta_description' => 'Rucisoft Indonesia: software, buku, dan workshop untuk strategi, kepatuhan, ESG, layanan korporat, dan AI perusahaan.',
    ];

    public static function get(string $key, ?string $default = null): ?string
    {
        $all = Cache::rememberForever('settings', fn () => static::query()->pluck('value', 'key')->all());

        return $all[$key] ?? $default ?? (self::DEFAULTS[$key] ?? null);
    }

    public static function whatsappUrl(?string $message = null): string
    {
        $number = preg_replace('/\D/', '', (string) static::get('whatsapp_number'));
        if (str_starts_with($number, '0')) {
            $number = '62'.substr($number, 1);
        }

        return 'https://wa.me/'.$number.'?text='.rawurlencode($message ?: (string) static::get('whatsapp_default_message'));
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('settings'));
        static::deleted(fn () => Cache::forget('settings'));
    }
}
