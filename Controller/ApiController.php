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

use Modules\Admin\Models\NullAccount;
use Modules\Attribute\Models\Attribute;
use Modules\Attribute\Models\AttributeType;
use Modules\Attribute\Models\AttributeValue;
use Modules\Attribute\Models\NullAttributeType;
use Modules\Attribute\Models\NullAttributeValue;
use Modules\FleetManagement\Models\FuelTypeL11nMapper;
use Modules\FleetManagement\Models\FuelTypeMapper;
use Modules\FleetManagement\Models\InspectionTypeL11nMapper;
use Modules\FleetManagement\Models\InspectionTypeMapper;
use Modules\FleetManagement\Models\Vehicle;
use Modules\FleetManagement\Models\VehicleAttributeMapper;
use Modules\FleetManagement\Models\VehicleAttributeTypeL11nMapper;
use Modules\FleetManagement\Models\VehicleAttributeTypeMapper;
use Modules\FleetManagement\Models\VehicleAttributeValueL11nMapper;
use Modules\FleetManagement\Models\VehicleAttributeValueMapper;
use Modules\FleetManagement\Models\VehicleMapper;
use Modules\FleetManagement\Models\VehicleStatus;
use Modules\FleetManagement\Models\VehicleTypeL11nMapper;
use Modules\FleetManagement\Models\VehicleTypeMapper;
use Modules\Media\Models\CollectionMapper;
use Modules\Media\Models\MediaMapper;
use Modules\Media\Models\NullMedia;
use Modules\Media\Models\PathSettings;
use Modules\Media\Models\Reference;
use phpOMS\Localization\BaseStringL11n;
use phpOMS\Localization\BaseStringL11nType;
use phpOMS\Localization\ISO639x1Enum;
use phpOMS\Localization\NullBaseStringL11nType;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Message\NotificationLevel;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Model\Message\FormValidation;

/**
 * FleetManagement class.
 *
 * @package Modules\FleetManagement
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class ApiController extends Controller
{
    /**
     * Api method to create a vehicle
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiVehicleTypeCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateVehicleTypeCreate($request))) {
            $response->data[$request->uri->__toString()] = new FormValidation($val);
            $response->header->status                    = RequestStatusCode::R_400;

            return;
        }

        /** @var BaseStringL11nType $vehicle */
        $vehicle = $this->createVehicleTypeFromRequest($request);
        $this->createModel($request->header->account, $vehicle, VehicleTypeMapper::class, 'vehicle_type', $request->getOrigin());

        $this->fillJsonResponse(
            $request,
            $response,
            NotificationLevel::OK,
            '',
            $this->app->l11nManager->getText($response->header->l11n->language, '0', '0', 'SucessfulCreate'),
            $vehicle
        );
    }

    /**
     * Method to create vehicle from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return BaseStringL11nType Returns the created vehicle from the request
     *
     * @since 1.0.0
     */
    public function createVehicleTypeFromRequest(RequestAbstract $request) : BaseStringL11nType
    {
        $type        = new BaseStringL11nType();
        $type->title = $request->getDataString('name') ?? '';
        $type->setL11n($request->getDataString('title') ?? '', $request->getDataString('language') ?? ISO639x1Enum::_EN);

        return $type;
    }

    /**
     * Validate vehicle create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool> Returns the validation array of the request
     *
     * @since 1.0.0
     */
    private function validateVehicleTypeCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['name'] = !$request->hasData('name'))
            || ($val['title'] = !$request->hasData('title'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create vehicle attribute l11n
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiVehicleTypeL11nCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateVehicleTypeL11nCreate($request))) {
            $response->data['vehicle_type_l11n_create'] = new FormValidation($val);
            $response->header->status                   = RequestStatusCode::R_400;

            return;
        }

        $typeL11n = $this->createVehicleTypeL11nFromRequest($request);
        $this->createModel($request->header->account, $typeL11n, VehicleTypeL11nMapper::class, 'vehicle_type_l11n', $request->getOrigin());
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Localization', 'Localization successfully created', $typeL11n);
    }

    /**
     * Method to create vehicle attribute l11n from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return BaseStringL11n
     *
     * @since 1.0.0
     */
    private function createVehicleTypeL11nFromRequest(RequestAbstract $request) : BaseStringL11n
    {
        $typeL11n      = new BaseStringL11n();
        $typeL11n->ref = $request->getDataInt('type') ?? 0;
        $typeL11n->setLanguage(
            $request->getDataString('language') ?? $request->header->l11n->language
        );
        $typeL11n->content = $request->getDataString('title') ?? '';

        return $typeL11n;
    }

    /**
     * Validate vehicle attribute l11n create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateVehicleTypeL11nCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['title'] = !$request->hasData('title'))
            || ($val['type'] = !$request->hasData('type'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create a vehicle
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiFuelTypeCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateFuelTypeCreate($request))) {
            $response->data[$request->uri->__toString()] = new FormValidation($val);
            $response->header->status                    = RequestStatusCode::R_400;

            return;
        }

        /** @var BaseStringL11nType $vehicle */
        $vehicle = $this->createFuelTypeFromRequest($request);
        $this->createModel($request->header->account, $vehicle, FuelTypeMapper::class, 'fuel_type', $request->getOrigin());

        $this->fillJsonResponse(
            $request,
            $response,
            NotificationLevel::OK,
            '',
            $this->app->l11nManager->getText($response->header->l11n->language, '0', '0', 'SucessfulCreate'),
            $vehicle
        );
    }

    /**
     * Method to create vehicle from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return BaseStringL11nType Returns the created vehicle from the request
     *
     * @since 1.0.0
     */
    public function createFuelTypeFromRequest(RequestAbstract $request) : BaseStringL11nType
    {
        $type        = new BaseStringL11nType();
        $type->title = $request->getDataString('name') ?? '';
        $type->setL11n($request->getDataString('title') ?? '', $request->getDataString('language') ?? ISO639x1Enum::_EN);

        return $type;
    }

    /**
     * Validate vehicle create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool> Returns the validation array of the request
     *
     * @since 1.0.0
     */
    private function validateFuelTypeCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['name'] = !$request->hasData('name'))
            || ($val['title'] = !$request->hasData('title'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create vehicle attribute l11n
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiFuelTypeL11nCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateFuelTypeL11nCreate($request))) {
            $response->data['fuel_type_l11n_create'] = new FormValidation($val);
            $response->header->status                = RequestStatusCode::R_400;

            return;
        }

        $typeL11n = $this->createFuelTypeL11nFromRequest($request);
        $this->createModel($request->header->account, $typeL11n, FuelTypeL11nMapper::class, 'fuel_type_l11n', $request->getOrigin());
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Localization', 'Localization successfully created', $typeL11n);
    }

    /**
     * Method to create vehicle attribute l11n from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return BaseStringL11n
     *
     * @since 1.0.0
     */
    private function createFuelTypeL11nFromRequest(RequestAbstract $request) : BaseStringL11n
    {
        $typeL11n      = new BaseStringL11n();
        $typeL11n->ref = $request->getDataInt('type') ?? 0;
        $typeL11n->setLanguage(
            $request->getDataString('language') ?? $request->header->l11n->language
        );
        $typeL11n->content = $request->getDataString('title') ?? '';

        return $typeL11n;
    }

    /**
     * Validate vehicle attribute l11n create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateFuelTypeL11nCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['title'] = !$request->hasData('title'))
            || ($val['type'] = !$request->hasData('type'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create a vehicle
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiVehicleCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateVehicleCreate($request))) {
            $response->data[$request->uri->__toString()] = new FormValidation($val);
            $response->header->status                    = RequestStatusCode::R_400;

            return;
        }

        /** @var Vehicle $vehicle */
        $vehicle = $this->createVehicleFromRequest($request);
        $this->createModel($request->header->account, $vehicle, VehicleMapper::class, 'vehicle', $request->getOrigin());

        if (!empty($request->files)
            || !empty($request->getDataJson('media'))
        ) {
            $this->createVehicleMedia($vehicle, $request);
        }

        $this->fillJsonResponse(
            $request,
            $response,
            NotificationLevel::OK,
            '',
            $this->app->l11nManager->getText($response->header->l11n->language, '0', '0', 'SucessfulCreate'),
            $vehicle
        );
    }

    /**
     * Method to create vehicle from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return Vehicle Returns the created vehicle from the request
     *
     * @since 1.0.0
     */
    public function createVehicleFromRequest(RequestAbstract $request) : Vehicle
    {
        $vehicle           = new Vehicle();
        $vehicle->name     = $request->getDataString('name') ?? '';
        $vehicle->info     = $request->getDataString('info') ?? '';
        $vehicle->type     = new NullBaseStringL11nType((int) ($request->getDataInt('type') ?? 0));
        $vehicle->fuelType = new NullBaseStringL11nType((int) ($request->getDataInt('fuel') ?? 0));
        $vehicle->status   = (int) ($request->getDataInt('status') ?? VehicleStatus::INACTIVE);
        $vehicle->unit     = $request->getDataInt('unit') ?? $this->app->unitId;

        return $vehicle;
    }

    /**
     * Create media files for vehicle
     *
     * @param Vehicle         $vehicle Vehicle
     * @param RequestAbstract $request Request incl. media do upload
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function createVehicleMedia(Vehicle $vehicle, RequestAbstract $request) : void
    {
        $path = $this->createVehicleDir($vehicle);

        if (!empty($uploadedFiles = $request->files)) {
            $uploaded = $this->app->moduleManager->get('Media')->uploadFiles(
                names: [],
                fileNames: [],
                files: $uploadedFiles,
                account: $request->header->account,
                basePath: __DIR__ . '/../../../Modules/Media/Files' . $path,
                virtualPath: $path,
                pathSettings: PathSettings::FILE_PATH
            );

            $collection = null;
            foreach ($uploaded as $media) {
                $this->createModelRelation(
                    $request->header->account,
                    $vehicle->id,
                    $media->id,
                    VehicleMapper::class,
                    'media',
                    '',
                    $request->getOrigin()
                );

                if ($collection === null) {
                    /** @var \Modules\Media\Models\Media $media */
                    $collection = MediaMapper::getParentCollection($path)->limit(1)->execute();

                    if ($collection->id === 0) {
                        $collection = $this->app->moduleManager->get('Media')->createRecursiveMediaCollection(
                            $path,
                            $request->header->account,
                            __DIR__ . '/../../../Modules/Media/Files' . $path
                        );
                    }
                }

                $this->createModelRelation(
                    $request->header->account,
                    $collection->id,
                    $media->id,
                    CollectionMapper::class,
                    'sources',
                    '',
                    $request->getOrigin()
                );
            }
        }

        if (!empty($mediaFiles = $request->getDataJson('media'))) {
            $collection = null;

            foreach ($mediaFiles as $file) {
                /** @var \Modules\Media\Models\Media $media */
                $media = MediaMapper::get()->where('id', (int) $file)->limit(1)->execute();

                $this->createModelRelation(
                    $request->header->account,
                    $vehicle->id,
                    $media->id,
                    VehicleMapper::class,
                    'media',
                    '',
                    $request->getOrigin()
                );

                $ref            = new Reference();
                $ref->name      = $media->name;
                $ref->source    = new NullMedia($media->id);
                $ref->createdBy = new NullAccount($request->header->account);
                $ref->setVirtualPath($path);

                $this->createModel($request->header->account, $ref, ReferenceMapper::class, 'media_reference', $request->getOrigin());

                if ($collection === null) {
                    /** @var \Modules\Media\Models\Media $media */
                    $collection = MediaMapper::getParentCollection($path)->limit(1)->execute();

                    if ($collection->id === 0) {
                        $collection = $this->app->moduleManager->get('Media')->createRecursiveMediaCollection(
                            $path,
                            $request->header->account,
                            __DIR__ . '/../../../Modules/Media/Files' . $path
                        );
                    }
                }

                $this->createModelRelation(
                    $request->header->account,
                    $collection->id,
                    $ref->id,
                    CollectionMapper::class,
                    'sources',
                    '',
                    $request->getOrigin()
                );
            }
        }
    }

    /**
     * Validate vehicle create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool> Returns the validation array of the request
     *
     * @since 1.0.0
     */
    private function validateVehicleCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['name'] = !$request->hasData('name'))
            || ($val['type'] = !$request->hasData('type'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create vehicle attribute
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiVehicleAttributeCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateVehicleAttributeCreate($request))) {
            $response->data['attribute_create'] = new FormValidation($val);
            $response->header->status           = RequestStatusCode::R_400;

            return;
        }

        $attribute = $this->createVehicleAttributeFromRequest($request);
        $this->createModel($request->header->account, $attribute, VehicleAttributeMapper::class, 'attribute', $request->getOrigin());

        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Attribute', 'Attribute successfully created', $attribute);
    }

    /**
     * Method to create vehicle attribute from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return Attribute
     *
     * @since 1.0.0
     */
    private function createVehicleAttributeFromRequest(RequestAbstract $request) : Attribute
    {
        $attribute       = new Attribute();
        $attribute->ref  = (int) $request->getData('vehicle');
        $attribute->type = new NullAttributeType((int) $request->getData('type'));

        if ($request->hasData('value')) {
            $attribute->value = new NullAttributeValue((int) $request->getData('value'));
        } else {
            $newRequest = clone $request;
            $newRequest->setData('value', $request->getData('custom'), true);

            $value = $this->createAttributeValueFromRequest($newRequest);

            $attribute->value = $value;
        }

        return $attribute;
    }

    /**
     * Validate vehicle attribute create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateVehicleAttributeCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['type'] = !$request->hasData('type'))
            || ($val['value'] = (!$request->hasData('value') && !$request->hasData('custom')))
            || ($val['vehicle'] = !$request->hasData('vehicle'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create vehicle attribute
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiVehicleAttributeUpdate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateVehicleAttributeUpdate($request))) {
            $response->data['attribute_update'] = new FormValidation($val);
            $response->header->status           = RequestStatusCode::R_400;

            return;
        }

        $old = VehicleAttributeMapper::get()
            ->with('type')
            ->with('type/defaults')
            ->with('value')
            ->where('id', (int) $request->getData('id'))
            ->execute();

        $new = $this->updateVehicleAttributeFromRequest($request, $old->deepClone());
        $this->updateModel($request->header->account, $old, $new, VehicleAttributeMapper::class, 'attribute', $request->getOrigin());

        if ($new->value->getValue() !== $old->value->getValue()) {
            $this->updateModel($request->header->account, $old->value, $new->value, VehicleAttributeValueMapper::class, 'attribute_value', $request->getOrigin());
        }

        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Attribute', 'Attribute successfully updated', $new);
    }

    /**
     * Method to create vehicle attribute from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return Attribute
     *
     * @since 1.0.0
     */
    private function updateVehicleAttributeFromRequest(RequestAbstract $request, Attribute $attribute) : Attribute
    {
        if ($attribute->type->custom) {
            if ($request->hasData('value')) {
                // @question: we are overwriting the old value, could there be a use case where we want to create a new value and keep the old one?
                $attribute->value->setValue($request->getData('value'), $attribute->type->datatype);
            }
        } else {
            if ($request->hasData('value')) {
                // @todo: fix by only accepting the value id to be used
                // this is a workaround for now because the front end doesn't allow to dynamically show default values.
                $value = $attribute->type->getDefaultByValue($request->getData('value'));

                if ($value->id !== 0) {
                    $attribute->value = $attribute->type->getDefaultByValue($request->getData('value'));
                }
            }
        }

        return $attribute;
    }

    /**
     * Validate vehicle attribute create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateVehicleAttributeUpdate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['id'] = !$request->hasData('id'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create vehicle attribute l11n
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiVehicleAttributeTypeL11nCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateVehicleAttributeTypeL11nCreate($request))) {
            $response->data['attr_type_l11n_create'] = new FormValidation($val);
            $response->header->status                = RequestStatusCode::R_400;

            return;
        }

        $attrL11n = $this->createVehicleAttributeTypeL11nFromRequest($request);
        $this->createModel($request->header->account, $attrL11n, VehicleAttributeTypeL11nMapper::class, 'attr_type_l11n', $request->getOrigin());
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Localization', 'Localization successfully created', $attrL11n);
    }

    /**
     * Method to create vehicle attribute l11n from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return BaseStringL11n
     *
     * @since 1.0.0
     */
    private function createVehicleAttributeTypeL11nFromRequest(RequestAbstract $request) : BaseStringL11n
    {
        $attrL11n      = new BaseStringL11n();
        $attrL11n->ref = $request->getDataInt('type') ?? 0;
        $attrL11n->setLanguage(
            $request->getDataString('language') ?? $request->header->l11n->language
        );
        $attrL11n->content = $request->getDataString('title') ?? '';

        return $attrL11n;
    }

    /**
     * Validate vehicle attribute l11n create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateVehicleAttributeTypeL11nCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['title'] = !$request->hasData('title'))
            || ($val['type'] = !$request->hasData('type'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create vehicle attribute type
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiVehicleAttributeTypeCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateVehicleAttributeTypeCreate($request))) {
            $response->data['attr_type_create'] = new FormValidation($val);
            $response->header->status           = RequestStatusCode::R_400;

            return;
        }

        $attrType = $this->createAttributeTypeFromRequest($request);
        $this->createModel($request->header->account, $attrType, VehicleAttributeTypeMapper::class, 'attr_type', $request->getOrigin());

        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Attribute type', 'Attribute type successfully created', $attrType);
    }

    /**
     * Method to create vehicle attribute from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return AttributeType
     *
     * @since 1.0.0
     */
    private function createAttributeTypeFromRequest(RequestAbstract $request) : AttributeType
    {
        $attrType                    = new AttributeType($request->getDataString('name') ?? '');
        $attrType->datatype          = $request->getDataInt('datatype') ?? 0;
        $attrType->custom            = $request->getDataBool('custom') ?? false;
        $attrType->isRequired        = (bool) ($request->getData('is_required') ?? false);
        $attrType->validationPattern = $request->getDataString('validation_pattern') ?? '';
        $attrType->setL11n($request->getDataString('title') ?? '', $request->getDataString('language') ?? ISO639x1Enum::_EN);
        $attrType->setFields($request->getDataInt('fields') ?? 0);

        return $attrType;
    }

    /**
     * Validate vehicle attribute create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateVehicleAttributeTypeCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['title'] = !$request->hasData('title'))
            || ($val['name'] = !$request->hasData('name'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create vehicle attribute value
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiVehicleAttributeValueCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateVehicleAttributeValueCreate($request))) {
            $response->data['attr_value_create'] = new FormValidation($val);
            $response->header->status            = RequestStatusCode::R_400;

            return;
        }

        $attrValue = $this->createAttributeValueFromRequest($request);
        $this->createModel($request->header->account, $attrValue, VehicleAttributeValueMapper::class, 'attr_value', $request->getOrigin());

        if ($attrValue->isDefault) {
            $this->createModelRelation(
                $request->header->account,
                (int) $request->getData('type'),
                $attrValue->id,
                VehicleAttributeTypeMapper::class, 'defaults', '', $request->getOrigin()
            );
        }

        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Attribute value', 'Attribute value successfully created', $attrValue);
    }

    /**
     * Method to create vehicle attribute value from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return AttributeValue
     *
     * @since 1.0.0
     */
    private function createAttributeValueFromRequest(RequestAbstract $request) : AttributeValue
    {
        /** @var AttributeType $type */
        $type = VehicleAttributeTypeMapper::get()
            ->where('id', $request->getDataInt('type') ?? 0)
            ->execute();

        $attrValue            = new AttributeValue();
        $attrValue->isDefault = $request->getDataBool('default') ?? false;
        $attrValue->setValue($request->getData('value'), $type->datatype);

        if ($request->hasData('title')) {
            $attrValue->setL11n($request->getDataString('title') ?? '', $request->getDataString('language') ?? ISO639x1Enum::_EN);
        }

        return $attrValue;
    }

    /**
     * Validate vehicle attribute value create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateVehicleAttributeValueCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['type'] = !$request->hasData('type'))
            || ($val['value'] = !$request->hasData('value'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create vehicle attribute l11n
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiVehicleAttributeValueL11nCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateVehicleAttributeValueL11nCreate($request))) {
            $response->data['attr_value_l11n_create'] = new FormValidation($val);
            $response->header->status                 = RequestStatusCode::R_400;

            return;
        }

        $attrL11n = $this->createAttributeValueL11nFromRequest($request);
        $this->createModel($request->header->account, $attrL11n, VehicleAttributeValueL11nMapper::class, 'attr_value_l11n', $request->getOrigin());
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Localization', 'Localization successfully created', $attrL11n);
    }

    /**
     * Method to create vehicle attribute l11n from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return BaseStringL11n
     *
     * @since 1.0.0
     */
    private function createAttributeValueL11nFromRequest(RequestAbstract $request) : BaseStringL11n
    {
        $attrL11n      = new BaseStringL11n();
        $attrL11n->ref = $request->getDataInt('value') ?? 0;
        $attrL11n->setLanguage(
            $request->getDataString('language') ?? $request->header->l11n->language
        );
        $attrL11n->content = $request->getDataString('title') ?? '';

        return $attrL11n;
    }

    /**
     * Validate vehicle attribute l11n create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateVehicleAttributeValueL11nCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['title'] = !$request->hasData('title'))
            || ($val['value'] = !$request->hasData('value'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to handle api vehicle attributes
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiVehicleAttribute(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateVehicleAttributeValueL11nCreate($request))) {
            $response->data['attr_value_l11n_create'] = new FormValidation($val);
            $response->header->status                 = RequestStatusCode::R_400;

            return;
        }

        $attrL11n = $this->createAttributeValueL11nFromRequest($request);
        $this->createModel($request->header->account, $attrL11n, VehicleAttributeValueL11nMapper::class, 'attr_value_l11n', $request->getOrigin());
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Localization', 'Localization successfully created', $attrL11n);
    }

    /**
     * Api method to create a bill
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiMediaAddToVehicle(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateMediaAddToVehicle($request))) {
            $response->data[$request->uri->__toString()] = new FormValidation($val);
            $response->header->status                    = RequestStatusCode::R_400;

            return;
        }

        /** @var \Modules\FleetManagement\Models\Vehicle $vehicle */
        $vehicle = VehicleMapper::get()->where('id', (int) $request->getData('vehicle'))->execute();
        $path    = $this->createVehicleDir($vehicle);

        $uploaded = [];
        if (!empty($uploadedFiles = $request->files)) {
            $uploaded = $this->app->moduleManager->get('Media')->uploadFiles(
                names: [],
                fileNames: [],
                files: $uploadedFiles,
                account: $request->header->account,
                basePath: __DIR__ . '/../../../Modules/Media/Files' . $path,
                virtualPath: $path,
                pathSettings: PathSettings::FILE_PATH,
                hasAccountRelation: false,
                readContent: (bool) ($request->getData('parse_content') ?? false)
            );

            $collection = null;
            foreach ($uploaded as $media) {
                $this->createModelRelation(
                    $request->header->account,
                    $vehicle->id,
                    $media->id,
                    VehicleMapper::class,
                    'files',
                    '',
                    $request->getOrigin()
                );

                if ($request->hasData('type')) {
                    $this->createModelRelation(
                        $request->header->account,
                        $media->id,
                        $request->getDataInt('type'),
                        MediaMapper::class,
                        'types',
                        '',
                        $request->getOrigin()
                    );
                }

                if ($collection === null) {
                    $collection = MediaMapper::getParentCollection($path)->limit(1)->execute();

                    if ($collection->id === 0) {
                        $collection = $this->app->moduleManager->get('Media')->createRecursiveMediaCollection(
                            $path,
                            $request->header->account,
                            __DIR__ . '/../../../Modules/Media/Files' . $path,
                        );
                    }
                }

                $this->createModelRelation(
                    $request->header->account,
                    $collection->id,
                    $media->id,
                    CollectionMapper::class,
                    'sources',
                    '',
                    $request->getOrigin()
                );
            }
        }

        if (!empty($mediaFiles = $request->getDataJson('media'))) {
            foreach ($mediaFiles as $media) {
                $this->createModelRelation(
                    $request->header->account,
                    $vehicle->id,
                    (int) $media,
                    VehicleMapper::class,
                    'files',
                    '',
                    $request->getOrigin()
                );
            }
        }

        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Media', 'Media added to vehicle.', [
            'upload' => $uploaded,
            'media'  => $mediaFiles,
        ]);
    }

    /**
     * Create media directory path
     *
     * @param Vehicle $vehicle Vehicle
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function createVehicleDir(Vehicle $vehicle) : string
    {
        return '/Modules/FleetManagement/Vehicle/'
            . $this->app->unitId . '/'
            . $vehicle->id;
    }

    /**
     * Method to validate bill creation from request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateMediaAddToVehicle(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['media'] = (!$request->hasData('media') && empty($request->files)))
            || ($val['vehicle'] = !$request->hasData('vehicle'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create a vehicle
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiInspectionTypeCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateInspectionTypeCreate($request))) {
            $response->data[$request->uri->__toString()] = new FormValidation($val);
            $response->header->status                    = RequestStatusCode::R_400;

            return;
        }

        /** @var BaseStringL11nType $vehicle */
        $vehicle = $this->createInspectionTypeFromRequest($request);
        $this->createModel($request->header->account, $vehicle, InspectionTypeMapper::class, 'inspection_type', $request->getOrigin());

        $this->fillJsonResponse(
            $request,
            $response,
            NotificationLevel::OK,
            '',
            $this->app->l11nManager->getText($response->header->l11n->language, '0', '0', 'SucessfulCreate'),
            $vehicle
        );
    }

    /**
     * Method to create vehicle from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return BaseStringL11nType Returns the created vehicle from the request
     *
     * @since 1.0.0
     */
    public function createInspectionTypeFromRequest(RequestAbstract $request) : BaseStringL11nType
    {
        $type        = new BaseStringL11nType();
        $type->title = $request->getDataString('name') ?? '';
        $type->setL11n($request->getDataString('title') ?? '', $request->getDataString('language') ?? ISO639x1Enum::_EN);

        return $type;
    }

    /**
     * Validate vehicle create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool> Returns the validation array of the request
     *
     * @since 1.0.0
     */
    private function validateInspectionTypeCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['name'] = !$request->hasData('name'))
            || ($val['title'] = !$request->hasData('title'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create vehicle attribute l11n
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiInspectionTypeL11nCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateInspectionTypeL11nCreate($request))) {
            $response->data['inspection_type_l11n_create'] = new FormValidation($val);
            $response->header->status                      = RequestStatusCode::R_400;

            return;
        }

        $typeL11n = $this->createInspectionTypeL11nFromRequest($request);
        $this->createModel($request->header->account, $typeL11n, InspectionTypeL11nMapper::class, 'inspection_type_l11n', $request->getOrigin());
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Localization', 'Localization successfully created', $typeL11n);
    }

    /**
     * Method to create vehicle attribute l11n from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return BaseStringL11n
     *
     * @since 1.0.0
     */
    private function createInspectionTypeL11nFromRequest(RequestAbstract $request) : BaseStringL11n
    {
        $typeL11n      = new BaseStringL11n();
        $typeL11n->ref = $request->getDataInt('type') ?? 0;
        $typeL11n->setLanguage(
            $request->getDataString('language') ?? $request->header->l11n->language
        );
        $typeL11n->content = $request->getDataString('title') ?? '';

        return $typeL11n;
    }

    /**
     * Validate vehicle attribute l11n create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateInspectionTypeL11nCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['title'] = !$request->hasData('title'))
            || ($val['type'] = !$request->hasData('type'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create item files
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiNoteCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateNoteCreate($request))) {
            $response->data['vehicle_note_create'] = new FormValidation($val);
            $response->header->status           = RequestStatusCode::R_400;

            return;
        }

        $request->setData('virtualpath', '/Modules/FleetManagement/Items/' . $request->getData('id'), true);
        $this->app->moduleManager->get('Editor', 'Api')->apiEditorCreate($request, $response, $data);

        if ($response->header->status !== RequestStatusCode::R_200) {
            return;
        }

        $responseData = $response->get($request->uri->__toString());
        if (!\is_array($responseData)) {
            return;
        }

        $model = $responseData['response'];
        $this->createModelRelation($request->header->account, (int) $request->getData('id'), $model->id, VehicleMapper::class, 'notes', '', $request->getOrigin());
    }

    /**
     * Validate item note create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateNoteCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['id'] = !$request->hasData('id'))
        ) {
            return $val;
        }

        return [];
    }
}
