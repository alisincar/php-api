<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit8bc2476a82a68d274d369c0ea8c03e5d
{
    public static $prefixLengthsPsr4 = array (
        'R' => 
        array (
            'Rakit\\Validation\\' => 17,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Rakit\\Validation\\' => 
        array (
            0 => __DIR__ . '/..' . '/rakit/validation/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit8bc2476a82a68d274d369c0ea8c03e5d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit8bc2476a82a68d274d369c0ea8c03e5d::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
