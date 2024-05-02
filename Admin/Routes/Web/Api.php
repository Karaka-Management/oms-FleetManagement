<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use Modules\FleetManagement\Controller\Controller;
use Modules\FleetManagement\Models\PermissionCategory;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^.*/fleet/vehicle/find(\?.*$|$)' => [
        [
            'dest'       => '\Modules\FleetManagement\Controller\ApiVehicleController:apiVehicleFind',
            'verb'       => RouteVerb::GET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => Controller::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::VEHICLE,
            ],
        ],
    ],

    '^.*/fleet/vehicle/attribute(\?.*$|$)' => [
        [
            'dest'       => '\Modules\FleetManagement\Controller\ApiVehicleAttributeController:apiVehicleAttributeCreate',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => Controller::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::VEHICLE,
            ],
        ],
        [
            'dest'       => '\Modules\FleetManagement\Controller\ApiVehicleAttributeController:apiVehicleAttributeUpdate',
            'verb'       => RouteVerb::SET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => Controller::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::VEHICLE,
            ],
        ],
    ],

    '^.*/fleet/driver/attribute(\?.*$|$)' => [
        [
            'dest'       => '\Modules\FleetManagement\Controller\ApiDriverAttributeController:apiDriverAttributeCreate',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => Controller::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::DRIVER,
            ],
        ],
        [
            'dest'       => '\Modules\FleetManagement\Controller\ApiDriverAttributeController:apiDriverAttributeUpdate',
            'verb'       => RouteVerb::SET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => Controller::NAME,
                'type'   => PermissionType::MODIFY,
                'state'  => PermissionCategory::DRIVER,
            ],
        ],
    ],

    '^.*/fleet/vehicle/note(\?.*$|$)' => [
        [
            'dest'       => '\Modules\FleetManagement\Controller\ApiVehicleController:apiNoteCreate',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => Controller::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::VEHICLE_NOTE,
            ],
        ],
        [
            'dest'       => '\Modules\FleetManagement\Controller\ApiVehicleController:apiNoteUpdate',
            'verb'       => RouteVerb::SET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => Controller::NAME,
                'type'   => PermissionType::MODIFY,
                'state'  => PermissionCategory::VEHICLE_NOTE,
            ],
        ],
    ],

    '^.*/fleet/driver/note(\?.*$|$)' => [
        [
            'dest'       => '\Modules\FleetManagement\Controller\ApiDriverController:apiNoteCreate',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => Controller::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::DRIVER_NOTE,
            ],
        ],
        [
            'dest'       => '\Modules\FleetManagement\Controller\ApiDriverController:apiNoteUpdate',
            'verb'       => RouteVerb::SET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => Controller::NAME,
                'type'   => PermissionType::MODIFY,
                'state'  => PermissionCategory::DRIVER_NOTE,
            ],
        ],
    ],
];
