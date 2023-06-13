<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\FleetManagement\Models\Driver
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\FleetManagement\Models\Driver;

use phpOMS\Stdlib\Base\Enum;

/**
 * Inspection status enum.
 *
 * @package Modules\FleetManagement\Models\Driver
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class DriverInspectionStatus extends Enum
{
    public const DONE = 1;

    public const PASSED = 2;

    public const ONGOING = 4;

    public const TODO = 5;
}
