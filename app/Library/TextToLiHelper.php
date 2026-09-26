<?php

namespace App\Library;

class TextToLiHelper
{
    public static  function nl2li($text) {
        $lines = explode("\n", $text);
        $output = '<ul class="m-0 p-0 d-flex align-items-center justify-content-center" dir="ltr">';
        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line)) {
                $output .= '<li>' . $line . '</li>';
            }
        }
        $output .= '</ul>';
        return $output;
    }
}
