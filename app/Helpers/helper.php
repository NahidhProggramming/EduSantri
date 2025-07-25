<?php

use Illuminate\Support\Str;

if (!function_exists('generate_safe_filename')) {
    function generate_safe_filename($name, $extension) {
        $slug = Str::slug($name, '_');
        return "surat_{$slug}.{$extension}";
    }
}
