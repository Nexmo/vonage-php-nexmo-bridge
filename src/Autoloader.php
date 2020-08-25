<?php
declare(strict_types=1);
/**
 * Vonage Nexmo Bridge
 *
 * @copyright Copyright (c) 2020 Vonage, Inc. (http://vonage.com)
 * @license   https://github.com/nexmo/vonage-php-nexmo-bridge/blob/master/LICENSE MIT License
 */

namespace Vonage\NexmoBridge;

use \ArrayObject;
use Closure;
use Composer\Autoload\ClassLoader;
use \RuntimeException;

/**
 * Alias legacy Nexmo classes/interfaces/traits to Vonage equivalents.
 */
class Autoloader
{
    /**
     * Attach autoloaders for managing legacy Nexmo artifacts.
     *
     * We attach two autoloaders:
     *
     * - The first is _prepended_ to handle new classes and add aliases for
     *   legacy classes. PHP expects any interfaces implemented, classes
     *   extended, or traits used when declaring class_alias() to exist and/or
     *   be autoloadable already at the time of declaration. If not, it will
     *   raise a fatal error. This autoloader helps mitigate errors in such
     *   situations.
     *
     * - The second is _appended_ in order to create aliases for legacy
     *   classes.
     */

     /**
      * @var array<string, string>
      */
    protected static $namespaces = ['Nexmo\\' => 'Vonage\\'];

    /**
     * @var array<string, string>
     */
    protected static $namespacesReversed = ['Vonage\\' => 'Nexmo\\'];

    public static function load() : void
    {
        $loaded = new ArrayObject([]);

        spl_autoload_register(self::createPrependAutoloader(
            static::$namespacesReversed,
            self::getClassLoader(),
            $loaded
        ), true, true);

        spl_autoload_register(self::createAppendAutoloader(
            static::$namespaces,
            $loaded
        ));
    }

    /**
     * @throws RuntimeException
     */
    private static function getClassLoader() : ClassLoader
    {
        if (file_exists(getenv('COMPOSER_VENDOR_DIR') . '/autoload.php')) {
            return include getenv('COMPOSER_VENDOR_DIR') . '/autoload.php';
        }

        if (file_exists(__DIR__ . '/../../../autoload.php')) {
            return include __DIR__ . '/../../../autoload.php';
        }

        if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
            return include __DIR__ . '/../vendor/autoload.php';
        }

        throw new RuntimeException('Cannot detect composer autoload. Please run composer install');
    }

    /**
     * @param array<string, string> $namespaces Namespaces to flip between
     * @return Closure(string): void
     */
    private static function createPrependAutoloader(
        array $namespaces,
        ClassLoader $classLoader,
        ArrayObject $loaded
    ) : Closure {
        /**
         * @param  string $class Class name to autoload
         * @return void
         */
        return static function (string $class) use ($namespaces, $classLoader, $loaded) : void {
            if (isset($loaded[$class])) {
                return;
            }

            $segments = explode('\\', $class);

            $i = 0;
            $check = '';

            while (isset($segments[$i + 1], $namespaces[$check . $segments[$i] . '\\'])) {
                $check .= $segments[$i] . '\\';
                ++$i;
            }

            if ($check === '') {
                return;
            }

            if ($classLoader->loadClass($class)) {
                $legacy = $namespaces[$check]
                    . strtr(substr($class, strlen($check)), [
                        'Vonage' => 'Nexmo',
                    ]);
                class_alias($class, $legacy);
            }
        };
    }

    /**
     * @param array<string, string> $namespaces Namespaces to flip between
     * @return Closure(string): void
     */
    private static function createAppendAutoloader(array $namespaces, ArrayObject $loaded) : Closure
    {
        /**
         * @param  string $class Class name to autoload
         * @return void
         */
        return static function (string $class) use ($namespaces, $loaded) : void {
            $segments = explode('\\', $class);

            $i = 0;
            $check = '';

            // We are checking segments of the namespace to match quicker
            while (isset($segments[$i + 1], $namespaces[$check . $segments[$i] . '\\'])) {
                $check .= $segments[$i] . '\\';
                ++$i;
            }

            if ($check === '') {
                return;
            }

            $alias = $namespaces[$check]
                . strtr(substr($class, strlen($check)), [
                    'Nexmo' => 'Vonage',
                ]);

            $loaded[$alias] = true;
            if (class_exists($alias) || interface_exists($alias) || trait_exists($alias)) {
                class_alias($alias, $class);
            }
        };
    }
}
