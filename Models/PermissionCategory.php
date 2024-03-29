<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\FleetManagement\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\FleetManagement\Models;

use phpOMS\Stdlib\Base\Enum;

/**
 * Permission category enum.
 *
 * @package Modules\FleetManagement\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class PermissionCategory extends Enum
{
    public const VEHICLE = 1;

    public const FUEL_TYPE = 2;

    public const VEHICLE_TYPE = 3;

    public const VEHICLE_INSPECTION_TYPE = 4;

    public const VEHICLE_INSPECTION = 5;

    public const VEHICLE_ATTRIBUTE_TYPE = 6;

    public const DRIVER = 7;

    public const DRIVER_INSPECTION_TYPE = 8;

    public const DRIVER_INSPECTION = 9;

    public const DRIVER_ATTRIBUTE_TYPE = 10;

    public const VEHICLE_NOTE = 11;

    public const DRIVER_NOTE = 12;

    public const ATTRIBUTE = 13;
}
