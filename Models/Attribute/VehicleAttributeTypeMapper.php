<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   Modules\FleetManagement\Models\Attribute
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\FleetManagement\Models\Attribute;

use Modules\Attribute\Models\AttributeType;
use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Vehicle mapper class.
 *
 * @package Modules\FleetManagement\Models\Attribute
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @template T of AttributeType
 * @extends DataMapperFactory<T>
 */
final class VehicleAttributeTypeMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'fleetmgmt_vehicle_attr_type_id'         => ['name' => 'fleetmgmt_vehicle_attr_type_id',       'type' => 'int',    'internal' => 'id'],
        'fleetmgmt_vehicle_attr_type_name'       => ['name' => 'fleetmgmt_vehicle_attr_type_name',     'type' => 'string', 'internal' => 'name', 'autocomplete' => true],
        'fleetmgmt_vehicle_attr_type_datatype'   => ['name' => 'fleetmgmt_vehicle_attr_type_datatype',   'type' => 'int',    'internal' => 'datatype'],
        'fleetmgmt_vehicle_attr_type_fields'     => ['name' => 'fleetmgmt_vehicle_attr_type_fields',   'type' => 'int',    'internal' => 'fields'],
        'fleetmgmt_vehicle_attr_type_custom'     => ['name' => 'fleetmgmt_vehicle_attr_type_custom',   'type' => 'bool',   'internal' => 'custom'],
        'fleetmgmt_vehicle_attr_type_repeatable' => ['name' => 'fleetmgmt_vehicle_attr_type_repeatable',   'type' => 'bool',   'internal' => 'repeatable'],
        'fleetmgmt_vehicle_attr_type_internal'   => ['name' => 'fleetmgmt_vehicle_attr_type_internal',   'type' => 'bool',   'internal' => 'isInternal'],
        'fleetmgmt_vehicle_attr_type_pattern'    => ['name' => 'fleetmgmt_vehicle_attr_type_pattern',  'type' => 'string', 'internal' => 'validationPattern'],
        'fleetmgmt_vehicle_attr_type_required'   => ['name' => 'fleetmgmt_vehicle_attr_type_required', 'type' => 'bool',   'internal' => 'isRequired'],
    ];

    /**
     * Has many relation.
     *
     * @var array<string, array{mapper:class-string, table:string, self?:?string, external?:?string, column?:string}>
     * @since 1.0.0
     */
    public const HAS_MANY = [
        'l11n' => [
            'mapper'   => VehicleAttributeTypeL11nMapper::class,
            'table'    => 'fleetmgmt_vehicle_attr_type_l11n',
            'self'     => 'fleetmgmt_vehicle_attr_type_l11n_type',
            'column'   => 'content',
            'external' => null,
        ],
        'defaults' => [
            'mapper'   => VehicleAttributeValueMapper::class,
            'table'    => 'fleetmgmt_vehicle_attr_default',
            'self'     => 'fleetmgmt_vehicle_attr_default_type',
            'external' => 'fleetmgmt_vehicle_attr_default_value',
        ],
    ];

    /**
     * Model to use by the mapper.
     *
     * @var class-string<T>
     * @since 1.0.0
     */
    public const MODEL = AttributeType::class;

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'fleetmgmt_vehicle_attr_type';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'fleetmgmt_vehicle_attr_type_id';
}
