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

use Modules\Attribute\Models\Attribute;
use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Fleet mapper class.
 *
 * @package Modules\FleetManagement\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @template T of Attribute
 * @extends DataMapperFactory<T>
 */
final class VehicleAttributeMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'fleetmgmt_vehicle_attr_id'    => ['name' => 'fleetmgmt_vehicle_attr_id',    'type' => 'int', 'internal' => 'id'],
        'fleetmgmt_vehicle_attr_vehicle'  => ['name' => 'fleetmgmt_vehicle_attr_vehicle',  'type' => 'int', 'internal' => 'ref'],
        'fleetmgmt_vehicle_attr_type'  => ['name' => 'fleetmgmt_vehicle_attr_type',  'type' => 'int', 'internal' => 'type'],
        'fleetmgmt_vehicle_attr_value' => ['name' => 'fleetmgmt_vehicle_attr_value', 'type' => 'int', 'internal' => 'value'],
    ];

    /**
     * Has one relation.
     *
     * @var array<string, array{mapper:class-string, external:string, by?:string, column?:string, conditional?:bool}>
     * @since 1.0.0
     */
    public const OWNS_ONE = [
        'type' => [
            'mapper'   => VehicleAttributeTypeMapper::class,
            'external' => 'fleetmgmt_vehicle_attr_type',
        ],
        'value' => [
            'mapper'   => VehicleAttributeValueMapper::class,
            'external' => 'fleetmgmt_vehicle_attr_value',
        ],
    ];

    /**
     * Model to use by the mapper.
     *
     * @var class-string<T>
     * @since 1.0.0
     */
    public const MODEL = Attribute::class;

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'fleetmgmt_vehicle_attr';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'fleetmgmt_vehicle_attr_id';
}
