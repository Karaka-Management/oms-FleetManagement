<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\FleetManagement\tests\Controller\Api;

use Modules\FleetManagement\Models\FuelTypeMapper;
use Modules\FleetManagement\Models\VehicleTypeMapper;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\HttpResponse;
use phpOMS\Utils\RnG\Text;

trait ApiControllerVehicleTrait
{
    /**
     * @covers Modules\FleetManagement\Controller\ApiVehicleController
     * @group module
     */
    public function testApiVehicleCreate() : void
    {
        $vehicleType      = VehicleTypeMapper::getAll()->execute();
        $vehicleTypeCount = \count($vehicleType);
        $fuelTypeCount    = FuelTypeMapper::count()->executeCount();

        $response = new HttpResponse();
        $request  = new HttpRequest();

        $LOREM       = \array_slice(Text::LOREM_IPSUM, 0, 25);
        $LOREM_COUNT = \count($LOREM) - 1;

        $request->header->account = 1;
        $request->setData('name', \ucfirst(Text::LOREM_IPSUM[\mt_rand(0, $LOREM_COUNT - 1)]));
        $request->setData('type', \mt_rand(1, $vehicleTypeCount));
        $request->setData('fuel', \mt_rand(1, $fuelTypeCount));
        $request->setData('status', 1);

        $this->module->apiVehicleCreate($request, $response);
        self::assertGreaterThan(0, $response->getDataArray('')['response']->id);
    }
}
