<?php
namespace App;

class Tools
{
    public static function Crypt(string $password): string
    {
        $options = [
            'cost' => 12,
        ];
        return password_hash($password, PASSWORD_BCRYPT, $options);
    }

    public static function FlashMessage(string $message, string $type='primary')
    {
        if(isset($_SESSION['flash_message']))
            unset($_SESSION['flash_message']);
        $_SESSION['flash_message'] = ['message' => $message,'type' => $type];
    }

    public static function slugify($text, string $divider = '-'): string
    {
        $text = preg_replace('~[^\pL\d]+~u',$divider, $text);

        $text = iconv('utf-8','us-ascii//TRANSLIT', $text);

        $text = preg_replace('~[^-\w]+~','', $text);

        $text = trim($text,$divider);

        $text = preg_replace('~-+~',$divider,$text);

        $text = strtolower($text);

        if(empty($text)){
            return 'N/A';
        }

        return $text;
    }
}