<?php

namespace App\Service;

class Slugify
{
    // public function generate(string $input): string
    // {
    //     $replaced = [' '];
    //     $replacement = ['-'];
    //     return str_replace($replaced, $replacement, $input);
    // }

    public function generate(string $input, string $divider = '-'): string
    {
        // replace non letter or digits by divider
        $input = preg_replace('~[^\pL\d]+~u', $divider, $input);

        // transliterate
        $input = iconv('utf-8', 'us-ascii//TRANSLIT', $input);

        // remove unwanted characters
        $input = preg_replace('~[^-\w]+~', '', $input);

        // trim
        $input = trim($input, $divider);

        // remove duplicate divider
        $input = preg_replace('~-+~', $divider, $input);

        // lowercase
        $input = strtolower($input);

        if (empty($input)) {
            return 'n-a';
        }

        return $input;
    }
}
