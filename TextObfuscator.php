<?php

class TextObfuscator
{

    public static function obfuscateStrings(string $input, array $strings, string $naming = ''): string
    {
        foreach ($strings as $id => $string) {
            $hash = uniqid();
            $obfuscated = str_ireplace(['{id}', '{hash}'], [$id, $hash], $naming);
            $input = str_ireplace($string, $obfuscated, $input);
        }
        return $input;
    }

    public static function obfuscateRegex(string $input, string $regex, string $naming = ''): string
    {
        return preg_replace($regex, $pattern, $input);
    }

    public static function getTerms(string $input): array
    {
        $term_list = array_unique(
                explode("\n", str_replace("\r", "", $input)));
        return $term_list;
    }

    public static function getWords(string $input, string $exceptions = '')
    {
        $word_list = array_unique(
                explode(' ',
                        str_replace(["\r", "\n"], ['', ' '], strtolower($input))
                )
        );
        $word_except_clean = array_filter(explode(' ', trim(strtolower($exceptions))));
        return array_diff($word_list, $word_except_clean);
    }

}
