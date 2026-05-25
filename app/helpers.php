<?php

if (!function_exists('setting')) {
    /**
     * Get a system setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting(string $key, $default = null)
    {
        return \App\Models\SystemSetting::get($key, $default);
    }
}
