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

use Modules\FleetManagement\Controller\BackendController;
use Modules\FleetManagement\Models\PermissionCategory;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^/fleet/vehicle/attribute/type/list(\?.*$|$)' => [
        [
            'dest'       => '\Modules\FleetManagement\Controller\BackendController:viewFleetManagementAttributeTypeList',
            'verb'       => RouteVerb::GET,
            'active' => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::ATTRIBUTE,
            ],
        ],
    ],
    '^/fleet/vehicle/attribute/type/view(\?.*$|$)' => [
        [
            'dest'       => '\Modules\FleetManagement\Controller\BackendController:viewFleetManagementAttributeType',
            'verb'       => RouteVerb::GET,
            'active' => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::ATTRIBUTE,
            ],
        ],
    ],
    '^/fleet/vehicle/attribute/type/create(\?.*$|$)' => [
        [
            'dest'       => '\Modules\FleetManagement\Controller\BackendController:viewFleetManagementAttributeTypeCreate',
            'verb'       => RouteVerb::GET,
            'active' => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::ATTRIBUTE,
            ],
        ],
    ],
    '^/fleet/vehicle/attribute/value/view(\?.*$|$)' => [
        [
            'dest'       => '\Modules\FleetManagement\Controller\BackendController:viewFleetManagementAttributeValue',
            'verb'       => RouteVerb::GET,
            'active' => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::ATTRIBUTE,
            ],
        ],
    ],
    '^/fleet/vehicle/attribute/value/create(\?.*$|$)' => [
        [
            'dest'       => '\Modules\FleetManagement\Controller\BackendController:viewFleetManagementAttributeValueCreate',
            'verb'       => RouteVerb::GET,
            'active' => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::ATTRIBUTE,
            ],
        ],
    ],

    '^/fleet/vehicle/list(\?.*$|$)' => [
        [
            'dest'       => '\Modules\FleetManagement\Controller\BackendController:viewFleetManagementVehicleList',
            'verb'       => RouteVerb::GET,
            'active' => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::VEHICLE,
            ],
        ],
    ],
    '^/fleet/vehicle/create(\?.*$|$)' => [
        [
            'dest'       => '\Modules\FleetManagement\Controller\BackendController:viewFleetManagementVehicleCreate',
            'verb'       => RouteVerb::GET,
            'active' => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::VEHICLE,
            ],
        ],
    ],
    '^/fleet/vehicle/view(\?.*$|$)' => [
        [
            'dest'       => '\Modules\FleetManagement\Controller\BackendController:viewFleetManagementVehicleView',
            'verb'       => RouteVerb::GET,
            'active' => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::VEHICLE,
            ],
        ],
    ],

    '^/fleet/driver/attribute/type/list(\?.*$|$)' => [
        [
            'dest'       => '\Modules\FleetManagement\Controller\BackendController:viewFleetManagementDriverAttributeTypeList',
            'verb'       => RouteVerb::GET,
            'active' => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::ATTRIBUTE,
            ],
        ],
    ],
    '^/fleet/driver/attribute/type/view(\?.*$|$)' => [
        [
            'dest'       => '\Modules\FleetManagement\Controller\BackendController:viewFleetManagementDriverAttributeType',
            'verb'       => RouteVerb::GET,
            'active' => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::ATTRIBUTE,
            ],
        ],
    ],
    '^/fleet/driver/attribute/type/create(\?.*$|$)' => [
        [
            'dest'       => '\Modules\FleetManagement\Controller\BackendController:viewFleetManagementDriverAttributeTypeCreate',
            'verb'       => RouteVerb::GET,
            'active' => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::ATTRIBUTE,
            ],
        ],
    ],
    '^/fleet/driver/attribute/value/view(\?.*$|$)' => [
        [
            'dest'       => '\Modules\FleetManagement\Controller\BackendController:viewFleetManagementDriverAttributeValue',
            'verb'       => RouteVerb::GET,
            'active' => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::ATTRIBUTE,
            ],
        ],
    ],
    '^/fleet/driver/attribute/value/create(\?.*$|$)' => [
        [
            'dest'       => '\Modules\FleetManagement\Controller\BackendController:viewFleetManagementDriverAttributeValueCreate',
            'verb'       => RouteVerb::GET,
            'active' => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::ATTRIBUTE,
            ],
        ],
    ],

    '^/fleet/driver/list(\?.*$|$)' => [
        [
            'dest'       => '\Modules\FleetManagement\Controller\BackendController:viewFleetManagementDriverList',
            'verb'       => RouteVerb::GET,
            'active' => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::VEHICLE,
            ],
        ],
    ],
    '^/fleet/driver/create(\?.*$|$)' => [
        [
            'dest'       => '\Modules\FleetManagement\Controller\BackendController:viewFleetManagementDriverCreate',
            'verb'       => RouteVerb::GET,
            'active' => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::VEHICLE,
            ],
        ],
    ],
    '^/fleet/driver/view(\?.*$|$)' => [
        [
            'dest'       => '\Modules\FleetManagement\Controller\BackendController:viewFleetManagementDriverView',
            'verb'       => RouteVerb::GET,
            'active' => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::VEHICLE,
            ],
        ],
    ],

    '^/fleet/inspection/list(\?.*$|$)' => [
        [
            'dest'       => '\Modules\FleetManagement\Controller\BackendController:viewFleetManagementInspectionList',
            'verb'       => RouteVerb::GET,
            'active' => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::VEHICLE,
            ],
        ],
    ],
    '^/fleet/inspection/vehicle/type/list(\?.*$|$)' => [
        [
            'dest'       => '\Modules\FleetManagement\Controller\BackendController:viewFleetManagementInspectionTypeList',
            'verb'       => RouteVerb::GET,
            'active' => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::VEHICLE,
            ],
        ],
    ],
    '^/fleet/inspection/vehicle/create(\?.*$|$)' => [
        [
            'dest'       => '\Modules\FleetManagement\Controller\BackendController:viewFleetManagementInspectionCreate',
            'verb'       => RouteVerb::GET,
            'active' => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::VEHICLE,
            ],
        ],
    ],
    '^/fleet/inspection/vehicle/view(\?.*$|$)' => [
        [
            'dest'       => '\Modules\FleetManagement\Controller\BackendController:viewFleetManagementInspectionView',
            'verb'       => RouteVerb::GET,
            'active' => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::VEHICLE,
            ],
        ],
    ],
    '^/fleet/inspection/driver/type/list(\?.*$|$)' => [
        [
            'dest'       => '\Modules\FleetManagement\Controller\BackendController:viewFleetManagementDriverInspectionTypeList',
            'verb'       => RouteVerb::GET,
            'active' => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::VEHICLE,
            ],
        ],
    ],
    '^/fleet/inspection/driver/create(\?.*$|$)' => [
        [
            'dest'       => '\Modules\FleetManagement\Controller\BackendController:viewFleetManagementDriverInspectionCreate',
            'verb'       => RouteVerb::GET,
            'active' => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::VEHICLE,
            ],
        ],
    ],
    '^/fleet/inspection/driver/view(\?.*$|$)' => [
        [
            'dest'       => '\Modules\FleetManagement\Controller\BackendController:viewFleetManagementDriverInspectionView',
            'verb'       => RouteVerb::GET,
            'active' => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::VEHICLE,
            ],
        ],
    ],
];
