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

use Modules\FleetManagement\Models\Driver\DriverMapper;
use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Milage mapper class.
 *
 * @package Modules\FleetManagement\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @template T of Milage
 * @extends DataMapperFactory<T>
 */
final class MilageMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'fleetmgmt_milage_id'          => ['name' => 'fleetmgmt_milage_id',    'type' => 'int',    'internal' => 'id'],
        'fleetmgmt_milage_description' => ['name' => 'fleetmgmt_milage_description', 'type' => 'string', 'internal' => 'description'],
        'fleetmgmt_milage_status'      => ['name' => 'fleetmgmt_milage_status',  'type' => 'int',    'internal' => 'status'],
        'fleetmgmt_milage_driver'      => ['name' => 'fleetmgmt_milage_driver',  'type' => 'int', 'internal' => 'driver'],
        'fleetmgmt_milage_vehicle'     => ['name' => 'fleetmgmt_milage_vehicle',  'type' => 'int', 'internal' => 'vehicle'],
        'fleetmgmt_milage_start'       => ['name' => 'fleetmgmt_milage_start',  'type' => 'DateTime', 'internal' => 'start'],
        'fleetmgmt_milage_end'         => ['name' => 'fleetmgmt_milage_end',  'type' => 'DateTime', 'internal' => 'end'],
        'fleetmgmt_milage_milage'      => ['name' => 'fleetmgmt_milage_milage',  'type' => 'int', 'internal' => 'milage'],
        'fleetmgmt_milage_fuel'        => ['name' => 'fleetmgmt_milage_fuel',  'type' => 'int', 'internal' => 'fuelUsage'],
    ];

    /**
     * Has one relation.
     *
     * @var array<string, array{mapper:class-string, external:string, by?:string, column?:string, conditional?:bool}>
     * @since 1.0.0
     */
    public const OWNS_ONE = [
        'driver' => [
            'mapper'   => DriverMapper::class,
            'external' => 'fleetmgmt_milage_driver',
        ],
        'vehicle' => [
            'mapper'   => VehicleMapper::class,
            'external' => 'fleetmgmt_milage_vehicle',
        ],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'fleetmgmt_milage';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'fleetmgmt_milage_id';

    /**
     * Model to use by the mapper.
     *
     * @var class-string<T>
     * @since 1.0.0
     */
    public const MODEL = Milage::class;
}
