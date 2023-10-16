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

namespace Modules\FleetManagement\tests\Models\Driver;

use Modules\FleetManagement\Models\Driver\NullDriverInspection;

/**
 * @internal
 */
final class NullDriverInspectionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\FleetManagement\Models\Driver\NullDriverInspection
     * @group framework
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\Modules\FleetManagement\Models\Driver\DriverInspection', new NullDriverInspection());
    }

    /**
     * @covers Modules\FleetManagement\Models\Driver\NullDriverInspection
     * @group framework
     */
    public function testId() : void
    {
        $null = new NullDriverInspection(2);
        self::assertEquals(2, $null->id);
    }

    /**
     * @covers Modules\FleetManagement\Models\Driver\NullDriverInspection
     * @group module
     */
    public function testJsonSerialize() : void
    {
        $null = new NullDriverInspection(2);
        self::assertEquals(['id' => 2], $null);
    }
}