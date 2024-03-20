<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\FleetManagement\tests\Models\Driver;

use Modules\FleetManagement\Models\Driver\NullDriver;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\Modules\FleetManagement\Models\Driver\NullDriver::class)]
final class NullDriverTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testNull() : void
    {
        self::assertInstanceOf('\Modules\FleetManagement\Models\Driver\Driver', new NullDriver());
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testId() : void
    {
        $null = new NullDriver(2);
        self::assertEquals(2, $null->id);
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testJsonSerialize() : void
    {
        $null = new NullDriver(2);
        self::assertEquals(['id' => 2], $null->jsonSerialize());
    }
}
