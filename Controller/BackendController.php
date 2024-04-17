<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\FleetManagement
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\FleetManagement\Controller;

use Modules\Attribute\Models\NullAttributeType;
use Modules\Attribute\Models\NullAttributeValue;
use Modules\FleetManagement\Models\Attribute\DriverAttributeTypeMapper;
use Modules\FleetManagement\Models\Attribute\DriverAttributeValueL11nMapper;
use Modules\FleetManagement\Models\Attribute\DriverAttributeValueMapper;
use Modules\FleetManagement\Models\Attribute\VehicleAttributeTypeL11nMapper;
use Modules\FleetManagement\Models\Attribute\VehicleAttributeTypeMapper;
use Modules\FleetManagement\Models\Attribute\VehicleAttributeValueL11nMapper;
use Modules\FleetManagement\Models\Attribute\VehicleAttributeValueMapper;
use Modules\FleetManagement\Models\Driver\DriverInspectionMapper;
use Modules\FleetManagement\Models\Driver\DriverMapper;
use Modules\FleetManagement\Models\Inspection;
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
        $view              = new \Modules\Attribute\Theme\Backend\Components\AttributeTypeListView($this->app->l11nManager, $request, $response);
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1003505001, $request, $response);

        $view->attributes = VehicleAttributeTypeMapper::getAll()
            ->with('l11n')
            ->where('l11n/language', $response->header->l11n->language)
            ->executeGetArray();

        $view->path = 'fleet/vehicle';

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
        $view              = new \Modules\Attribute\Theme\Backend\Components\AttributeTypeListView($this->app->l11nManager, $request, $response);
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1003506001, $request, $response);

        $view->attributes = DriverAttributeTypeMapper::getAll()
            ->with('l11n')
            ->where('l11n/language', $response->header->l11n->language)
            ->executeGetArray();

        $view->path = 'fleet/driver';

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
            ->executeGetArray();

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
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1003503001, $request, $response);

        $list = DriverMapper::getAll()
            ->with('account')
            ->sort('id', 'DESC')
            ->executeGetArray();

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
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1003504001, $request, $response);

        $vehicles = InspectionMapper::getAll()
            ->with('type')
            ->with('type/l11n')
            ->where('type/l11n/language', $request->header->l11n->language)
            ->sort('id', 'DESC')
            ->limit(100)
            ->executeGetArray();

        $drivers = DriverInspectionMapper::getAll()
            ->with('type')
            ->with('type/l11n')
            ->where('type/l11n/language', $request->header->l11n->language)
            ->sort('id', 'DESC')
            ->limit(100)
            ->executeGetArray();

        $inspections = [];
        foreach ($vehicles as $inspection) {
            $inspections[] = ['type' => 'vehicle', 'inspection' => $inspection];
        }

        foreach ($drivers as $inspection) {
            $inspections[] = ['type' => 'driver', 'inspection' => $inspection];
        }

        \usort($inspections, function (array $a, array $b) : int {
            return $a['inspection']->date?->getTimestamp() <=> $b['inspection']->date?->getTimestamp();
        });

        $view->data['inspections'] = $inspections;
        $view->data['vehicles']    = VehicleMapper::getAll()
            ->where('id', \array_map(function (Inspection $inspection) { return $inspection->reference; }, $vehicles))
            ->executeGetArray();

        $view->data['drivers'] = DriverMapper::getAll()
            ->with('account')
            ->where('id', \array_map(function (Inspection $inspection) { return $inspection->reference; }, $drivers))
            ->executeGetArray();

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

        $view->data['inspections'] = InspectionTypeMapper::getAll()
            ->with('l11n')
            ->where('l11n/language', $response->header->l11n->language)
            ->sort('id', 'DESC')
            ->executeGetArray();

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
    public function viewFleetManagementDriverInspectionTypeList(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/FleetManagement/Theme/Backend/inspection-type-list');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1003502001, $request, $response);

        $view->data['inspections'] = InspectionTypeMapper::getAll()
            ->with('l11n')
            ->where('l11n/language', $response->header->l11n->language)
            ->sort('id', 'DESC')
            ->executeGetArray();

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
    public function viewFleetManagementDriverInspectionCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);

        $view->setTemplate('/Modules/FleetManagement/Theme/Backend/inspection-view');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1003502001, $request, $response);

        $view->data['inspections'] = InspectionMapper::getAll()
            ->sort('id', 'DESC')
            ->executeGetArray();

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
        $view              = new \Modules\Attribute\Theme\Backend\Components\AttributeTypeView($this->app->l11nManager, $request, $response);
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1003505001, $request, $response);

        $view->attribute = VehicleAttributeTypeMapper::get()
            ->with('l11n')
            ->with('defaults')
            ->with('defaults/l11n')
            ->where('id', (int) $request->getData('id'))
            ->where('l11n/language', $response->header->l11n->language)
            ->where('defaults/l11n/language', [$response->header->l11n->language, null])
            ->execute();

        $view->l11ns = VehicleAttributeTypeL11nMapper::getAll()
            ->where('ref', $view->attribute->id)
            ->executeGetArray();

        $view->path = 'fleet/vehicle';

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
        $view              = new \Modules\Attribute\Theme\Backend\Components\AttributeTypeView($this->app->l11nManager, $request, $response);
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1003506001, $request, $response);

        $view->attribute = VehicleAttributeTypeMapper::get()
            ->with('l11n')
            ->with('defaults')
            ->with('defaults/l11n')
            ->where('id', (int) $request->getData('id'))
            ->where('l11n/language', $response->header->l11n->language)
            ->where('defaults/l11n/language', [$response->header->l11n->language, null])
            ->execute();

        $view->l11ns = VehicleAttributeTypeL11nMapper::getAll()
            ->where('ref', $view->attribute->id)
            ->executeGetArray();

        $view->path = 'fleet/driver';

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
    public function viewFleetManagementAttributeValue(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view              = new \Modules\Attribute\Theme\Backend\Components\AttributeValueView($this->app->l11nManager, $request, $response);
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1003505001, $request, $response);

        $view->attribute = VehicleAttributeValueMapper::get()
            ->with('l11n')
            ->where('id', (int) $request->getData('id'))
            ->where('l11n/language', [$response->header->l11n->language, null])
            ->execute();

        $view->l11ns = VehicleAttributeValueL11nMapper::getAll()
            ->where('ref', $view->attribute->id)
            ->executeGetArray();

        // @todo Also find the ItemAttributeType

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
    public function viewFleetManagementDriverAttributeValue(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view              = new \Modules\Attribute\Theme\Backend\Components\AttributeValueView($this->app->l11nManager, $request, $response);
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1003506001, $request, $response);

        $view->attribute = DriverAttributeValueMapper::get()
            ->with('l11n')
            ->where('id', (int) $request->getData('id'))
            ->where('l11n/language', [$response->header->l11n->language, null])
            ->execute();

        $view->l11ns = DriverAttributeValueL11nMapper::getAll()
            ->where('ref', $view->attribute->id)
            ->executeGetArray();

        // @todo Also find the ItemAttributeType

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
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1003503001, $request, $response);

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

        $vehicle = VehicleMapper::get()
            ->with('attributes')
            ->with('attributes/type')
            ->with('attributes/value')
            ->with('attributes/type/l11n')
            ->with('attributes/value/l11n')
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
            ->where('attributes/value/l11n/language', [$response->header->l11n->language, null])
            ->execute();

        $view->data['vehicle'] = $vehicle;

        $inspections = InspectionMapper::getAll()
            ->with('type')
            ->with('type/l11n')
            ->where('reference', $vehicle->id)
            ->where('type/l11n/language', $response->header->l11n->language)
            ->executeGetArray();

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
            ->executeGetArray();

        $view->data['units'] = UnitMapper::getAll()
            ->executeGetArray();

        $view->data['attributeTypes'] = VehicleAttributeTypeMapper::getAll()
            ->with('l11n')
            ->where('l11n/language', $response->header->l11n->language)
            ->executeGetArray();

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
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1003503001, $request, $response);

        $driver = DriverMapper::get()
            ->with('attributes')
            ->with('attributes/type')
            ->with('attributes/value')
            ->with('attributes/type/l11n')
            ->with('attributes/value/l11n')
            ->with('files')
            ->with('files/types')
            ->where('id', (int) $request->getData('id'))
            ->where('attributes/type/l11n/language', $response->header->l11n->language)
            ->where('attributes/value/l11n/language', [$response->header->l11n->language, null])
            ->execute();

        $view->data['driver'] = $driver;

        $view->data['inspections'] = DriverInspectionMapper::getAll()
            ->with('type')
            ->with('type/l11n')
            ->where('reference', $driver->id)
            ->where('type/l11n/language', $response->header->l11n->language)
            ->executeGetArray();

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

        $view->data['attributeTypes'] = DriverAttributeTypeMapper::getAll()
            ->with('l11n')
            ->where('l11n/language', $response->header->l11n->language)
            ->executeGetArray();

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
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewFleetManagementDriverAttributeTypeCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view              = new \Modules\Attribute\Theme\Backend\Components\AttributeTypeView($this->app->l11nManager, $request, $response);
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1003506001, $request, $response);

        $view->attribute = new NullAttributeType();

        $view->path = 'fleet/driver';

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
    public function viewFleetManagementAttributeTypeCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view              = new \Modules\Attribute\Theme\Backend\Components\AttributeTypeView($this->app->l11nManager, $request, $response);
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1003505001, $request, $response);

        $view->attribute = new NullAttributeType();

        $view->path = 'fleet/driver';

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
    public function viewFleetManagementAttributeValueCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view              = new \Modules\Attribute\Theme\Backend\Components\AttributeValueView($this->app->l11nManager, $request, $response);
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1003505001, $request, $response);

        $view->type      = VehicleAttributeTypeMapper::get()->where('id', (int) $request->getData('type'))->execute();
        $view->attribute = new NullAttributeValue();

        $view->path = 'contract';

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
    public function viewFleetManagementDriverAttributeValueCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view              = new \Modules\Attribute\Theme\Backend\Components\AttributeValueView($this->app->l11nManager, $request, $response);
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1003506001, $request, $response);

        $view->type      = DriverAttributeTypeMapper::get()->where('id', (int) $request->getData('type'))->execute();
        $view->attribute = new NullAttributeValue();

        $view->path = 'contract';

        return $view;
    }
}
