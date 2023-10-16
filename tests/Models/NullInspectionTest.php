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

use Modules\FleetManagement\Models\NullInspection;

/**
 * @internal
 */
final class NullInspectionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\FleetManagement\Models\NullInspection
     * @group module
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\Modules\FleetManagement\Models\Inspection', new NullInspection());
    }

    /**
     * @covers Modules\FleetManagement\Models\NullInspection
     * @group module
     */
    public function testId() : void
    {
        $null = new NullInspection(2);
        self::assertEquals(2, $null->id);
    }

    /**
     * @covers Modules\FleetManagement\Models\NullInspection
     * @group module
     */
    public function testJsonSerialize() : void
    {
        $null = new NullInspection(2);
        self::assertEquals(['id' => 2], $null);
    }
}