<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitda25c43a46a766788e17f20a17323f49
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitda25c43a46a766788e17f20a17323f49::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitda25c43a46a766788e17f20a17323f49::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
