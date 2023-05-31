<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\FleetManagement
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\FleetManagement\Controller;

use Modules\FleetManagement\Models\VehicleAttributeTypeL11nMapper;
use Modules\FleetManagement\Models\VehicleAttributeTypeMapper;
use Modules\FleetManagement\Models\VehicleMapper;
use Modules\FleetManagement\Models\VehicleTypeMapper;
use Modules\Media\Models\MediaMapper;
use Modules\Media\Models\MediaTypeMapper;
use phpOMS\Contract\RenderableInterface;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Views\View;

/**
 * FleetManagement class.
 *
 * @package Modules\FleetManagement
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 * @codeCoverageIgnore
 */
final class BackendController extends Controller
{
    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface Returns a renderable object
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewFleetManagementAttributeTypeList(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/FleetManagement/Theme/Backend/attribute-type-list');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1003503001, $request, $response);

        /** @var \Modules\Attribute\Models\AttributeType[] $attributes */
        $attributes = VehicleAttributeTypeMapper::getAll()
            ->with('l11n')
            ->where('l11n/language', $response->header->l11n->language)
            ->execute();

        $view->data['attributes'] = $attributes;

        return $view;
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface Returns a renderable object
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewFleetManagementVehicleList(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);

        $view->setTemplate('/Modules/FleetManagement/Theme/Backend/vehicle-list');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1003502001, $request, $response);

        $list = VehicleMapper::getAll()
            ->with('type')
            ->with('type/l11n')
            ->where('type/l11n/language', $response->header->l11n->language)
            ->sort('id', 'DESC')
            ->execute();

        $view->data['vehicles'] = $list;

        return $view;
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewFleetManagementAttributeType(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/FleetManagement/Theme/Backend/attribute-type');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1004801001, $request, $response);

        /** @var \Modules\Attribute\Models\AttributeType $attribute */
        $attribute = VehicleAttributeTypeMapper::get()
            ->with('l11n')
            ->where('id', (int) $request->getData('id'))
            ->where('l11n/language', $response->header->l11n->language)
            ->execute();

        $l11ns = VehicleAttributeTypeL11nMapper::getAll()
            ->where('ref', $attribute->id)
            ->execute();

        $view->data['attribute'] = $attribute;
        $view->data['l11ns']     = $l11ns;

        return $view;
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface Returns a renderable object
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewFleetManagementVehicleProfile(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);

        $view->setTemplate('/Modules/FleetManagement/Theme/Backend/vehicle-profile');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1003502001, $request, $response);

        $vehicle = VehicleMapper::get()
            ->with('attributes')
            ->with('attributes/type')
            ->with('attributes/value')
            ->with('attributes/type/l11n')
            ->with('type')
            ->with('type/l11n')
            ->with('fuelType')
            ->with('fuelType/l11n')
            ->where('id', (int) $request->getData('id'))
            ->where('type/l11n/language', $response->header->l11n->language)
            ->where('fuelType/l11n/language', $response->header->l11n->language)
            ->where('attributes/type/l11n/language', $response->header->l11n->language)
            ->execute();

        $view->data['vehicle'] = $vehicle;

        $query   = new Builder($this->app->dbPool->get());
        $results = $query->selectAs(VehicleMapper::HAS_MANY['files']['external'], 'file')
            ->from(VehicleMapper::TABLE)
            ->leftJoin(VehicleMapper::HAS_MANY['files']['table'])
                ->on(VehicleMapper::HAS_MANY['files']['table'] . '.' . VehicleMapper::HAS_MANY['files']['self'], '=', VehicleMapper::TABLE . '.' . VehicleMapper::PRIMARYFIELD)
            ->leftJoin(MediaMapper::TABLE)
                ->on(VehicleMapper::HAS_MANY['files']['table'] . '.' . VehicleMapper::HAS_MANY['files']['external'], '=', MediaMapper::TABLE . '.' . MediaMapper::PRIMARYFIELD)
             ->leftJoin(MediaMapper::HAS_MANY['types']['table'])
                ->on(MediaMapper::TABLE . '.' . MediaMapper::PRIMARYFIELD, '=', MediaMapper::HAS_MANY['types']['table'] . '.' . MediaMapper::HAS_MANY['types']['self'])
            ->leftJoin(MediaTypeMapper::TABLE)
                ->on(MediaMapper::HAS_MANY['types']['table'] . '.' . MediaMapper::HAS_MANY['types']['external'], '=', MediaTypeMapper::TABLE . '.' . MediaTypeMapper::PRIMARYFIELD)
            ->where(VehicleMapper::HAS_MANY['files']['self'], '=', $vehicle->id)
            ->where(MediaTypeMapper::TABLE . '.' . MediaTypeMapper::getColumnByMember('name'), '=', 'vehicle_profile_image');

        $vehicleImage = MediaMapper::get()
            ->with('types')
            ->where('id', $results)
            ->limit(1)
            ->execute();

        $view->data['vehicleImage'] = $vehicleImage;

        $vehicleTypes = VehicleTypeMapper::getAll()
            ->with('l11n')
            ->where('l11n/language', $response->header->l11n->language)
            ->execute();

        $view->data['types'] = $vehicleTypes;

        return $view;
    }
}
