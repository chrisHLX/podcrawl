<?php

// app/Helpers/TextHelper.php
namespace App\Helpers;

class TextHelper
{
    public static function highlightMatch(?string $text, string $query): string
    {
        if (!$text) return '';
        
        return preg_replace_callback('/(' . preg_quote($query, '/') . ')/i', function ($match) {
            return '<mark>' . $match[1] . '</mark>';
        }, $text);
    }
}
