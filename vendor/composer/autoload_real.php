<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit121b4bcd60b4580f4402ce3ea6df8b85
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInit121b4bcd60b4580f4402ce3ea6df8b85', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit121b4bcd60b4580f4402ce3ea6df8b85', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit121b4bcd60b4580f4402ce3ea6df8b85::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
