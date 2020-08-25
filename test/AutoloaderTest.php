<?php
declare(strict_types=1);
/**
 * Vonage Nexmo Bridge
 *
 * @copyright Copyright (c) 2020 Vonage, Inc. (http://vonage.com)
 * @license   https://github.com/nexmo/vonage-php-nexmo-bridge/blob/master/LICENSE MIT License
 */

namespace VonageTest\NexmoBridge;

use function get_class;

use function class_exists;
use Vonage\LegacyTypeHint;
use function interface_exists;
use PHPUnit\Framework\TestCase;

class AutoloaderTest extends TestCase
{
    /**
     * @return array[]
     */
    public function classProvider()
    {
        return [
            ['Nexmo\Client', 'Vonage\Client'],
            ['Nexmo\Account\Client', 'Vonage\Account\Client'],
            ['Nexmo\Application\Client', 'Vonage\Application\Client'],
            ['Nexmo\Call\Client', 'Vonage\Call\Client'],
            ['Nexmo\Insights\Client', 'Vonage\Insights\Client'],
            ['Nexmo\Message\Client', 'Vonage\Message\Client'],
            ['Nexmo\Numbers\Client', 'Vonage\Numbers\Client'],
            ['Nexmo\Redact\Client', 'Vonage\Redact\Client'],
            ['Nexmo\SMS\Client', 'Vonage\SMS\Client'],
            ['Nexmo\Verify\Client', 'Vonage\Verify\Client'],
            ['Nexmo\Voice\Client', 'Vonage\Voice\Client'],
        ];
    }

    /**
     * @dataProvider classProvider
     * @param string $legacy
     * @param string $actual
     * @param null|bool $isInterface
     */
    public function testLegacyClassIsAliasToVonage($legacy, $actual, $isInterface = false)
    {
        self::assertTrue($isInterface ? interface_exists($legacy) : class_exists($legacy));
        if (! $isInterface) {
            self::assertSame($actual, get_class(new $legacy()));
        }
    }

    public function testTypeHint()
    {
        self::assertTrue(class_exists('Vonage\LegacyTypeHint'));
        new LegacyTypeHint(new \Nexmo\Example());
    }

    /**
     * @return array[]
     */
    public function reverseClassProvider()
    {
        return [
            ['Vonage\Client', 'Nexmo\Client'],
            ['Vonage\Account\Client', 'Nexmo\Account\Client'],
            ['Vonage\Application\Client', 'Nexmo\Application\Client'],
            ['Vonage\Call\Client', 'Nexmo\Call\Client'],
            ['Vonage\Insights\Client', 'Nexmo\Insights\Client'],
            ['Vonage\Message\Client', 'Nexmo\Message\Client'],
            ['Vonage\Numbers\Client', 'Nexmo\Numbers\Client'],
            ['Vonage\Redact\Client', 'Nexmo\Redact\Client'],
            ['Vonage\SMS\Client', 'Nexmo\SMS\Client'],
            ['Vonage\Verify\Client', 'Nexmo\Verify\Client'],
            ['Vonage\Voice\Client', 'Nexmo\Voice\Client'],
        ];
    }

    /**
     * @dataProvider reverseClassProvider
     *
     * @param string $actual
     * @param string $legacy
     */
    public function testReverseAliasCreated($actual, $legacy)
    {
        self::assertTrue(class_exists($actual));
        self::assertTrue(class_exists($legacy));
    }
}
