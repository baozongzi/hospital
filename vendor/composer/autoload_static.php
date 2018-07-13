<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit6dc4599ee749e0b2dcae73bb375cd0ff
{
    public static $prefixLengthsPsr4 = array (
        'J' => 
        array (
            'JokeChat\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'JokeChat\\' => 
        array (
            0 => __DIR__ . '/..' . '/jokechat/src/JokeChat',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit6dc4599ee749e0b2dcae73bb375cd0ff::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit6dc4599ee749e0b2dcae73bb375cd0ff::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
