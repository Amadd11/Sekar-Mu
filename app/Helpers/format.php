<?php

if (! function_exists('format_bytes')) {
    /**
     * Format a byte count into a human-readable string.
     *
     * @param  int  $bytes
     * @return string
     */
    function format_bytes(int $bytes): string
    {
        if ($bytes <= 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $pow = (int) floor(log($bytes, 1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 1) . ' ' . $units[$pow];
    }
}
