<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\FleetManagement\Models\Driver
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\FleetManagement\Models\Driver;

use phpOMS\Stdlib\Base\Enum;

/**
 * Driver status enum.
 *
 * @package Modules\FleetManagement\Models\Driver
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class DriverStatus extends Enum
{
    public const ACTIVE = 1;

    public const INACTIVE = 2;

    public const SUSPENDED = 3;
}
