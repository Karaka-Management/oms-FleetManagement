<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\FleetManagement\tests\Models;

use Modules\FleetManagement\Models\NullVehicle;

/**
 * @internal
 */
final class NullVehicleTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\FleetManagement\Models\NullVehicle
     * @group module
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\Modules\FleetManagement\Models\Vehicle', new NullVehicle());
    }

    /**
     * @covers Modules\FleetManagement\Models\NullVehicle
     * @group module
     */
    public function testId() : void
    {
        $null = new NullVehicle(2);
        self::assertEquals(2, $null->id);
    }

    /**
     * @covers Modules\FleetManagement\Models\NullVehicle
     * @group module
     */
    public function testJsonSerialize() : void
    {
        $null = new NullVehicle(2);
        self::assertEquals(['id' => 2], $null->jsonSerialize());
    }
}
