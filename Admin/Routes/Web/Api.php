<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use Modules\FleetManagement\Controller\ApiController;
use Modules\FleetManagement\Models\PermissionCategory;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^.*/fleet/vehicle/find.*$' => [
        [
            'dest'       => '\Modules\FleetManagement\Controller\ApiController:apiVehicleFind',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::VEHICLE,
            ],
        ],
    ],
    '^.*/fleet/vehicle/attribute.*$' => [
        [
            'dest'       => '\Modules\FleetManagement\Controller\ApiController:apiVehicleAttributeCreate',
            'verb'       => RouteVerb::PUT,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::VEHICLE,
            ],
        ],
        [
            'dest'       => '\Modules\FleetManagement\Controller\ApiController:apiVehicleAttributeUpdate',
            'verb'       => RouteVerb::SET,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::VEHICLE,
            ],
        ],
    ],
];