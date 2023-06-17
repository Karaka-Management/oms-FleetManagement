<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   Modules\FleetManagement\Models\Driver
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\FleetManagement\Models\Driver;

use Modules\Admin\Models\AccountMapper;
use Modules\Media\Models\MediaMapper;
use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;
use Modules\Editor\Models\EditorDocMapper;

/**
 * Mapper class.
 *
 * @package Modules\FleetManagement\Models\Driver
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @template T of Driver
 * @extends DataMapperFactory<T>
 */
final class DriverMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'fleetmgmt_driver_id'                      => ['name' => 'fleetmgmt_driver_id',         'type' => 'int',      'internal' => 'id'],
        'fleetmgmt_driver_status'                  => ['name' => 'fleetmgmt_driver_status',      'type' => 'int',   'internal' => 'status'],
        'fleetmgmt_driver_account'             => ['name' => 'fleetmgmt_driver_account',      'type' => 'int',   'internal' => 'account'],
    ];

    /**
     * Has many relation.
     *
     * @var array<string, array{mapper:class-string, table:string, self?:?string, external?:?string, column?:string}>
     * @since 1.0.0
     */
    public const HAS_MANY = [
        'files'        => [
            'mapper'   => MediaMapper::class,
            'table'    => 'fleetmgmt_driver_media',
            'external' => 'fleetmgmt_driver_media_media',
            'self'     => 'fleetmgmt_driver_media_vehicle',
        ],
        'attributes' => [
            'mapper'   => DriverAttributeMapper::class,
            'table'    => 'fleetmgmt_driver_attr',
            'self'     => 'fleetmgmt_driver_attr_driver',
            'external' => null,
        ],
        'notes' => [
            'mapper'   => EditorDocMapper::class,       /* mapper of the related object */
            'table'    => 'fleetmgmt_driver_note',         /* table of the related object, null if no relation table is used (many->1) */
            'external' => 'fleetmgmt_driver_note_doc',
            'self'     => 'fleetmgmt_driver_note_driver',
        ],
    ];

    /**
     * Has one relation.
     *
     * @var array<string, array{mapper:class-string, external:string, by?:string, column?:string, conditional?:bool}>
     * @since 1.0.0
     */
    public const OWNS_ONE = [
        'account' => [
            'mapper'   => AccountMapper::class,
            'external' => 'fleetmgmt_driver_account',
        ],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'fleetmgmt_driver';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'fleetmgmt_driver_id';
}
