<?php
/**
 * Jingga
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

use Modules\FleetManagement\Models\Attribute\DriverAttributeTypeMapper;
use Modules\FleetManagement\Models\Attribute\VehicleAttributeTypeL11nMapper;
use Modules\FleetManagement\Models\Attribute\VehicleAttributeTypeMapper;
use Modules\FleetManagement\Models\Driver\DriverInspectionMapper;
use Modules\FleetManagement\Models\Driver\DriverMapper;
use Modules\FleetManagement\Models\InspectionMapper;
use Modules\FleetManagement\Models\InspectionTypeMapper;
use Modules\FleetManagement\Models\VehicleMapper;
use Modules\FleetManagement\Models\VehicleTypeMapper;
use Modules\Media\Models\MediaMapper;
use Modules\Media\Models\MediaTypeMapper;
use Modules\Organization\Models\UnitMapper;
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
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface Returns a renderable object
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewFleetManagementAttributeTypeList(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
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
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface Returns a renderable object
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewFleetManagementDriverAttributeTypeList(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/FleetManagement/Theme/Backend/attribute-type-list');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1003503001, $request, $response);

        /** @var \Modules\Attribute\Models\AttributeType[] $attributes */
        $attributes = DriverAttributeTypeMapper::getAll()
            ->with('l11n')
            ->where('l11n/language', $response->header->l11n->language)
            ->execute();

        $view->data['attributes'] = $attributes;

        return $view;
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface Returns a renderable object
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewFleetManagementVehicleList(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
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
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface Returns a renderable object
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewFleetManagementDriverList(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);

        $view->setTemplate('/Modules/FleetManagement/Theme/Backend/driver-list');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1003502001, $request, $response);

        $list = DriverMapper::getAll()
            ->with('account')
            ->sort('id', 'DESC')
            ->execute();

        $view->data['drivers'] = $list;

        return $view;
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface Returns a renderable object
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewFleetManagementInspectionList(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);

        $view->setTemplate('/Modules/FleetManagement/Theme/Backend/inspection-list');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1003501001, $request, $response);

        $list = InspectionMapper::getAll()
            ->sort('id', 'DESC')
            ->execute();

        $view->data['inspections'] = $list;

        return $view;
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface Returns a renderable object
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewFleetManagementInspectionTypeList(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);

        $view->setTemplate('/Modules/FleetManagement/Theme/Backend/inspection-type-list');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1003502001, $request, $response);

        $list = InspectionTypeMapper::getAll()
            ->sort('id', 'DESC')
            ->execute();

        $view->data['inspections'] = $list;

        return $view;
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface Returns a renderable object
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewFleetManagementInspectionCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);

        $view->setTemplate('/Modules/FleetManagement/Theme/Backend/inspection-list');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1003502001, $request, $response);

        $list = InspectionMapper::getAll()
            ->sort('id', 'DESC')
            ->execute();

        $view->data['inspections'] = $list;

        return $view;
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewFleetManagementAttributeType(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
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
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewFleetManagementDriverAttributeType(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
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
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface Returns a renderable object
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewFleetManagementVehicleCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);

        $view->setTemplate('/Modules/FleetManagement/Theme/Backend/vehicle-view');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1003502001, $request, $response);

        $view->data['attributeView']                               = new \Modules\Attribute\Theme\Backend\Components\AttributeView($this->app->l11nManager, $request, $response);
        $view->data['attributeView']->data['default_localization'] = $this->app->l11nServer;

        $view->data['media-upload']  = new \Modules\Media\Theme\Backend\Components\Upload\BaseView($this->app->l11nManager, $request, $response);
        $view->data['vehicle-notes'] = new \Modules\Editor\Theme\Backend\Components\Compound\BaseView($this->app->l11nManager, $request, $response);

        return $view;
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface Returns a renderable object
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewFleetManagementDriverCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);

        $view->setTemplate('/Modules/FleetManagement/Theme/Backend/driver-view');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1003502001, $request, $response);

        $view->data['attributeView']                               = new \Modules\Attribute\Theme\Backend\Components\AttributeView($this->app->l11nManager, $request, $response);
        $view->data['attributeView']->data['default_localization'] = $this->app->l11nServer;

        $view->data['media-upload'] = new \Modules\Media\Theme\Backend\Components\Upload\BaseView($this->app->l11nManager, $request, $response);
        $view->data['driver-notes'] = new \Modules\Editor\Theme\Backend\Components\Compound\BaseView($this->app->l11nManager, $request, $response);

        return $view;
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface Returns a renderable object
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewFleetManagementInspectionView(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);

        $view->setTemplate('/Modules/FleetManagement/Theme/Backend/inspection-view');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1003502001, $request, $response);

        return $view;
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface Returns a renderable object
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewFleetManagementVehicleView(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);

        $view->setTemplate('/Modules/FleetManagement/Theme/Backend/vehicle-view');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1003502001, $request, $response);

        // @todo This language filtering doesn't work. But it was working with the old mappers. Maybe there is a bug in the where() definition. Need to inspect the actual query.
        $vehicle = VehicleMapper::get()
            ->with('attributes')
            ->with('attributes/type')
            ->with('attributes/value')
            ->with('attributes/type/l11n')
            //->with('attributes/value/l11n')
            ->with('files')
            ->with('files/types')
            ->with('type')
            ->with('type/l11n')
            ->with('fuelType')
            ->with('fuelType/l11n')
            ->where('id', (int) $request->getData('id'))
            ->where('type/l11n/language', $response->header->l11n->language)
            ->where('fuelType/l11n/language', $response->header->l11n->language)
            ->where('attributes/type/l11n/language', $response->header->l11n->language)
            //->where('attributes/value/l11n/language', $response->header->l11n->language)
            ->execute();

        $view->data['vehicle'] = $vehicle;

        $inspections = InspectionMapper::getAll()
            ->with('type')
            ->with('type/l11n')
            ->where('reference', $vehicle->id)
            ->where('type/l11n/language', $response->header->l11n->language)
            ->execute();

        $view->data['inspections'] = $inspections;

        // @feature Create a new read mapper function that returns relation models instead of its own model
        //      https://github.com/Karaka-Management/phpOMS/issues/320
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

            $view->data['vehicleImage'] = MediaMapper::get()
            ->with('types')
            ->where('id', $results)
            ->limit(1)
            ->execute();

        $view->data['types'] = VehicleTypeMapper::getAll()
            ->with('l11n')
            ->where('l11n/language', $response->header->l11n->language)
            ->execute();

        $view->data['units'] = UnitMapper::getAll()
            ->execute();

        /** @var \Modules\Attribute\Models\AttributeType[] */
        $view->data['attributeTypes'] = VehicleAttributeTypeMapper::getAll()
            ->with('l11n')
            ->where('l11n/language', $response->header->l11n->language)
            ->execute();

        $view->data['attributeView']                               = new \Modules\Attribute\Theme\Backend\Components\AttributeView($this->app->l11nManager, $request, $response);
        $view->data['attributeView']->data['default_localization'] = $this->app->l11nServer;

        $view->data['media-upload']  = new \Modules\Media\Theme\Backend\Components\Upload\BaseView($this->app->l11nManager, $request, $response);
        $view->data['vehicle-notes'] = new \Modules\Editor\Theme\Backend\Components\Compound\BaseView($this->app->l11nManager, $request, $response);

        return $view;
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface Returns a renderable object
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewFleetManagementDriverView(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);

        $view->setTemplate('/Modules/FleetManagement/Theme/Backend/driver-view');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1003502001, $request, $response);

        // @todo This language filtering doesn't work. But it was working with the old mappers. Maybe there is a bug in the where() definition. Need to inspect the actual query.
        $driver = DriverMapper::get()
            ->with('attributes')
            ->with('attributes/type')
            ->with('attributes/value')
            ->with('attributes/type/l11n')
            //->with('attributes/value/l11n')
            ->with('files')
            ->with('files/types')
            ->where('id', (int) $request->getData('id'))
            ->where('attributes/type/l11n/language', $response->header->l11n->language)
            //->where('attributes/value/l11n/language', $response->header->l11n->language)
            ->execute();

        $view->data['driver'] = $driver;

        $view->data['inspections'] = DriverInspectionMapper::getAll()
            ->with('type')
            ->with('type/l11n')
            ->where('reference', $driver->id)
            ->where('type/l11n/language', $response->header->l11n->language)
            ->execute();

        // @feature Create a new read mapper function that returns relation models instead of its own model
        //      https://github.com/Karaka-Management/phpOMS/issues/320
        $query   = new Builder($this->app->dbPool->get());
        $results = $query->selectAs(DriverMapper::HAS_MANY['files']['external'], 'file')
            ->from(DriverMapper::TABLE)
            ->leftJoin(DriverMapper::HAS_MANY['files']['table'])
                ->on(DriverMapper::HAS_MANY['files']['table'] . '.' . DriverMapper::HAS_MANY['files']['self'], '=', DriverMapper::TABLE . '.' . DriverMapper::PRIMARYFIELD)
            ->leftJoin(MediaMapper::TABLE)
                ->on(DriverMapper::HAS_MANY['files']['table'] . '.' . DriverMapper::HAS_MANY['files']['external'], '=', MediaMapper::TABLE . '.' . MediaMapper::PRIMARYFIELD)
             ->leftJoin(MediaMapper::HAS_MANY['types']['table'])
                ->on(MediaMapper::TABLE . '.' . MediaMapper::PRIMARYFIELD, '=', MediaMapper::HAS_MANY['types']['table'] . '.' . MediaMapper::HAS_MANY['types']['self'])
            ->leftJoin(MediaTypeMapper::TABLE)
                ->on(MediaMapper::HAS_MANY['types']['table'] . '.' . MediaMapper::HAS_MANY['types']['external'], '=', MediaTypeMapper::TABLE . '.' . MediaTypeMapper::PRIMARYFIELD)
            ->where(DriverMapper::HAS_MANY['files']['self'], '=', $driver->id)
            ->where(MediaTypeMapper::TABLE . '.' . MediaTypeMapper::getColumnByMember('name'), '=', 'driver_profile_image');

            $view->data['driverImage'] = MediaMapper::get()
            ->with('types')
            ->where('id', $results)
            ->limit(1)
            ->execute();

        /** @var \Modules\Attribute\Models\AttributeType[] */
        $view->data['attributeTypes'] = DriverAttributeTypeMapper::getAll()
            ->with('l11n')
            ->where('l11n/language', $response->header->l11n->language)
            ->execute();

        $view->data['attributeView']                               = new \Modules\Attribute\Theme\Backend\Components\AttributeView($this->app->l11nManager, $request, $response);
        $view->data['attributeView']->data['default_localization'] = $this->app->l11nServer;

        $view->data['media-upload'] = new \Modules\Media\Theme\Backend\Components\Upload\BaseView($this->app->l11nManager, $request, $response);
        $view->data['driver-notes'] = new \Modules\Editor\Theme\Backend\Components\Compound\BaseView($this->app->l11nManager, $request, $response);

        return $view;
    }
}
