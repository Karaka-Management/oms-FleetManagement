<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\FleetManagement\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\FleetManagement\Models;

/**
 *  class.
 *
 * @package Modules\Attribute\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Vehicle implements \JsonSerializable
{
    public int $id = 0;

    public string $name = '';

    public int $status = VehicleStatus::ACTIVE;

    public VehicleType $type;

    public FuelType $fuelType;

    public string $info = '';

    public array $drivers = [];

    public array $inspections = [];

    public array $milage = [];

    public array $notes = [];

    public int $unit = 0;

    public ?int $responsible = null;

    public \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable('now');
        $this->type      = new VehicleType();
        $this->fuelType  = new FuelType();
    }

    /**
     * {@inheritdoc}
     */
    public function toArray() : array
    {
        return [
            'id'    => $this->id,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize() : mixed
    {
        return $this->toArray();
    }

    use \Modules\Media\Models\MediaListTrait;
    use \Modules\Attribute\Models\AttributeHolderTrait;
}
