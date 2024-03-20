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

use Modules\Editor\Models\EditorDocMapper;
use Modules\FleetManagement\Models\Attribute\VehicleAttributeMapper;
use Modules\Media\Models\MediaMapper;
use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Mapper class.
 *
 * @package Modules\FleetManagement\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @template T of Vehicle
 * @extends DataMapperFactory<T>
 */
final class VehicleMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'fleetmgmt_vehicle_id'          => ['name' => 'fleetmgmt_vehicle_id',         'type' => 'int',      'internal' => 'id'],
        'fleetmgmt_vehicle_name'        => ['name' => 'fleetmgmt_vehicle_name',      'type' => 'string',   'internal' => 'name'],
        'fleetmgmt_vehicle_status'      => ['name' => 'fleetmgmt_vehicle_status',      'type' => 'int',   'internal' => 'status'],
        'fleetmgmt_vehicle_info'        => ['name' => 'fleetmgmt_vehicle_info',      'type' => 'string',   'internal' => 'info'],
        'fleetmgmt_vehicle_unit'        => ['name' => 'fleetmgmt_vehicle_unit',      'type' => 'int',   'internal' => 'unit'],
        'fleetmgmt_vehicle_type'        => ['name' => 'fleetmgmt_vehicle_type',      'type' => 'int',   'internal' => 'type'],
        'fleetmgmt_vehicle_fuel'        => ['name' => 'fleetmgmt_vehicle_fuel',      'type' => 'int',   'internal' => 'fuelType'],
        'fleetmgmt_vehicle_responsible' => ['name' => 'fleetmgmt_vehicle_responsible',      'type' => 'int',   'internal' => 'responsible'],
        'fleetmgmt_vehicle_created_at'  => ['name' => 'fleetmgmt_vehicle_created_at', 'type' => 'DateTimeImmutable', 'internal' => 'createdAt', 'readonly' => true],
    ];

    /**
     * Has many relation.
     *
     * @var array<string, array{mapper:class-string, table:string, self?:?string, external?:?string, column?:string}>
     * @since 1.0.0
     */
    public const HAS_MANY = [
        'files' => [
            'mapper'   => MediaMapper::class,
            'table'    => 'fleetmgmt_vehicle_media',
            'external' => 'fleetmgmt_vehicle_media_media',
            'self'     => 'fleetmgmt_vehicle_media_vehicle',
        ],
        'attributes' => [
            'mapper'   => VehicleAttributeMapper::class,
            'table'    => 'fleetmgmt_vehicle_attr',
            'self'     => 'fleetmgmt_vehicle_attr_vehicle',
            'external' => null,
        ],
        'notes' => [
            'mapper'   => EditorDocMapper::class,       /* mapper of the related object */
            'table'    => 'fleetmgmt_vehicle_note',         /* table of the related object, null if no relation table is used (many->1) */
            'external' => 'fleetmgmt_vehicle_note_doc',
            'self'     => 'fleetmgmt_vehicle_note_vehicle',
        ],
    ];

    /**
     * Has one relation.
     *
     * @var array<string, array{mapper:class-string, external:string, by?:string, column?:string, conditional?:bool}>
     * @since 1.0.0
     */
    public const OWNS_ONE = [
        'type' => [
            'mapper'   => VehicleTypeMapper::class,
            'external' => 'fleetmgmt_vehicle_type',
        ],
        'fuelType' => [
            'mapper'   => FuelTypeMapper::class,
            'external' => 'fleetmgmt_vehicle_fuel',
        ],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'fleetmgmt_vehicle';

    /**
     * Created at.
     *
     * @var string
     * @since 1.0.0
     */
    public const CREATED_AT = 'fleetmgmt_vehicle_created_at';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'fleetmgmt_vehicle_id';
}
