<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\FleetManagement\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\FleetManagement\Models;

use Modules\FleetManagement\Models\Driver\Driver;

/**
 * Milage class.
 *
 * @package Modules\Attribute\Models
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Milage implements \JsonSerializable
{
    public int $id = 0;

    public int $vehicle = 0;

    public ?Driver $driver = null;

    public ?\DateTime $start = null;

    public ?\DateTime $end = null;

    public string $from = '';

    public string $to = '';

    /**
     * Milage in km
     *
     * @var int
     */
    public int $milage = 0;

    /**
     * Fuel usage in l
     *
     * @var int
     */
    public int $fuelUsage = 0;

    public string $description = '';

    public int $status = 0;

    /**
     * {@inheritdoc}
     */
    public function toArray() : array
    {
        return [
            'id' => $this->id,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize() : mixed
    {
        return $this->toArray();
    }
}
