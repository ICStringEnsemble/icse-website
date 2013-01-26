<?php

namespace Common;

class Tools
{
    public static function randString($length) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";	
        $size = strlen( $chars );
      $str = "";
        for( $i = 0; $i < $length; $i++ ) {
            $str .= $chars[mt_rand(0, $size-1)];
        }
        return $str;
    }  


    public static function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
     
        // trim
        $text = trim($text, '-');
     
        // transliterate
        if (function_exists('iconv'))
        {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }
     
        // lowercase
        $text = strtolower($text);
     
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
     
        if (empty($text))
        {
            return 'n-a';
        }
     
        return $text; 
    }
} 
