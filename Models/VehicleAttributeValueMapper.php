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

use Modules\Attribute\Models\AttributeValue;
use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Vehicle mapper class.
 *
 * @package Modules\FleetManagement\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @template T of AttributeValue
 * @extends DataMapperFactory<T>
 */
final class VehicleAttributeValueMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'fleetmgmt_attr_value_id'            => ['name' => 'fleetmgmt_attr_value_id',       'type' => 'int',      'internal' => 'id'],
        'fleetmgmt_attr_value_default'       => ['name' => 'fleetmgmt_attr_value_default',  'type' => 'bool',     'internal' => 'isDefault'],
        'fleetmgmt_attr_value_valueStr'      => ['name' => 'fleetmgmt_attr_value_valueStr', 'type' => 'string',   'internal' => 'valueStr'],
        'fleetmgmt_attr_value_valueInt'      => ['name' => 'fleetmgmt_attr_value_valueInt', 'type' => 'int',      'internal' => 'valueInt'],
        'fleetmgmt_attr_value_valueDec'      => ['name' => 'fleetmgmt_attr_value_valueDec', 'type' => 'float',    'internal' => 'valueDec'],
        'fleetmgmt_attr_value_valueDat'      => ['name' => 'fleetmgmt_attr_value_valueDat', 'type' => 'DateTime', 'internal' => 'valueDat'],
        'fleetmgmt_attr_value_unit'          => ['name' => 'fleetmgmt_attr_value_unit', 'type' => 'string', 'internal' => 'unit'],
        'fleetmgmt_attr_value_deptype'          => ['name' => 'fleetmgmt_attr_value_deptype', 'type' => 'int', 'internal' => 'dependingAttributeType'],
        'fleetmgmt_attr_value_depvalue'          => ['name' => 'fleetmgmt_attr_value_depvalue', 'type' => 'int', 'internal' => 'dependingAttributeValue'],
    ];

    /**
     * Has many relation.
     *
     * @var array<string, array{mapper:class-string, table:string, self?:?string, external?:?string, column?:string}>
     * @since 1.0.0
     */
    public const HAS_MANY = [
        'l11n' => [
            'mapper'   => VehicleAttributeValueL11nMapper::class,
            'table'    => 'fleetmgmt_attr_value_l11n',
            'self'     => 'fleetmgmt_attr_value_l11n_value',
            'external' => null,
        ],
    ];

    /**
     * Model to use by the mapper.
     *
     * @var class-string<T>
     * @since 1.0.0
     */
    public const MODEL = AttributeValue::class;

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'fleetmgmt_attr_value';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'fleetmgmt_attr_value_id';
}
