<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use Modules\FleetManagement\Controller\BackendController;
use Modules\FleetManagement\Models\PermissionCategory;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^.*/fleet/vehicle/attribute/type/list.*$' => [
        [
            'dest'       => '\Modules\FleetManagement\Controller\BackendController:viewFleetManagementAttributeTypeList',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::VEHICLE,
            ],
        ],
    ],
    '^.*/fleet/vehicle/attribute/type\?.*$' => [
        [
            'dest'       => '\Modules\FleetManagement\Controller\BackendController:viewFleetManagementAttributeType',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::VEHICLE,
            ],
        ],
    ],
    '^.*/fleet/vehicle/list.*$' => [
        [
            'dest'       => '\Modules\FleetManagement\Controller\BackendController:viewFleetManagementVehicleList',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::VEHICLE,
            ],
        ],
    ],
    '^.*/fleet/vehicle/create.*$' => [
        [
            'dest'       => '\Modules\FleetManagement\Controller\BackendController:viewFleetManagementVehicleCreate',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::VEHICLE,
            ],
        ],
    ],
    '^.*/fleet/vehicle/profile.*$' => [
        [
            'dest'       => '\Modules\FleetManagement\Controller\BackendController:viewFleetManagementVehicleProfile',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::VEHICLE,
            ],
        ],
    ],
];
