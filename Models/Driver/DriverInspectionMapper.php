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

use Modules\FleetManagement\Models\Inspection;
use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Driver inspection mapper class.
 *
 * @package Modules\FleetManagement\Models\Driver
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @template T of Inspection
 * @extends DataMapperFactory<T>
 */
final class DriverInspectionMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'fleetmgmt_driver_inspection_id'          => ['name' => 'fleetmgmt_driver_inspection_id',    'type' => 'int',    'internal' => 'id'],
        'fleetmgmt_driver_inspection_driver'      => ['name' => 'fleetmgmt_driver_inspection_driver', 'type' => 'int', 'internal' => 'reference'],
        'fleetmgmt_driver_inspection_description' => ['name' => 'fleetmgmt_driver_inspection_description', 'type' => 'string', 'internal' => 'description'],
        'fleetmgmt_driver_inspection_status'      => ['name' => 'fleetmgmt_driver_inspection_status',  'type' => 'int',    'internal' => 'status'],
        'fleetmgmt_driver_inspection_interval'    => ['name' => 'fleetmgmt_driver_inspection_interval',  'type' => 'int', 'internal' => 'interval'],
        'fleetmgmt_driver_inspection_next'        => ['name' => 'fleetmgmt_driver_inspection_next',  'type' => 'DateTime', 'internal' => 'next'],
        'fleetmgmt_driver_inspection_date'        => ['name' => 'fleetmgmt_driver_inspection_date',  'type' => 'DateTime', 'internal' => 'date'],
        'fleetmgmt_driver_inspection_type'        => ['name' => 'fleetmgmt_driver_inspection_type',  'type' => 'int', 'internal' => 'type'],
    ];

    /**
     * Has one relation.
     *
     * @var array<string, array{mapper:class-string, external:string, by?:string, column?:string, conditional?:bool}>
     * @since 1.0.0
     */
    public const OWNS_ONE = [
        'type' => [
            'mapper'   => DriverInspectionTypeMapper::class,
            'external' => 'fleetmgmt_driver_inspection_type',
        ],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'fleetmgmt_driver_inspection';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'fleetmgmt_driver_inspection_id';

    /**
     * Model to use by the mapper.
     *
     * @var class-string<T>
     * @since 1.0.0
     */
    public const MODEL = Inspection::class;
}
