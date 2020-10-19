<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit76b1170ac54738223143ba060565e8d8
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'TransactionPlugin\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'TransactionPlugin\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit76b1170ac54738223143ba060565e8d8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit76b1170ac54738223143ba060565e8d8::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
