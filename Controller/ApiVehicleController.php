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

use Modules\Admin\Models\NullAccount;
use Modules\FleetManagement\Models\Driver\NullDriver;
use Modules\FleetManagement\Models\FuelTypeL11nMapper;
use Modules\FleetManagement\Models\FuelTypeMapper;
use Modules\FleetManagement\Models\Inspection;
use Modules\FleetManagement\Models\InspectionMapper;
use Modules\FleetManagement\Models\InspectionStatus;
use Modules\FleetManagement\Models\InspectionTypeL11nMapper;
use Modules\FleetManagement\Models\InspectionTypeMapper;
use Modules\FleetManagement\Models\Milage;
use Modules\FleetManagement\Models\MilageMapper;
use Modules\FleetManagement\Models\Vehicle;
use Modules\FleetManagement\Models\VehicleMapper;
use Modules\FleetManagement\Models\VehicleStatus;
use Modules\FleetManagement\Models\VehicleTypeL11nMapper;
use Modules\FleetManagement\Models\VehicleTypeMapper;
use Modules\Media\Models\CollectionMapper;
use Modules\Media\Models\MediaMapper;
use Modules\Media\Models\NullMedia;
use Modules\Media\Models\PathSettings;
use Modules\Media\Models\Reference;
use Modules\Media\Models\ReferenceMapper;
use phpOMS\Localization\BaseStringL11n;
use phpOMS\Localization\BaseStringL11nType;
use phpOMS\Localization\ISO639x1Enum;
use phpOMS\Localization\NullBaseStringL11nType;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Message\NotificationLevel;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;

/**
 * FleetManagement class.
 *
 * @package Modules\FleetManagement
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class ApiVehicleController extends Controller
{
    /**
     * Api method to create a vehicle
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiVehicleFind(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
    }

    /**
     * Api method to create a vehicle
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiMilageCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateMilageCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        /** @var \Modules\FleetManagement\Models\Milage $milage */
        $milage = $this->createMilageFromRequest($request);
        $this->createModel($request->header->account, $milage, MilageMapper::class, 'milage', $request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $milage);
    }

    /**
     * Method to create vehicle from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return Milage Returns the created vehicle from the request
     *
     * @since 1.0.0
     */
    public function createMilageFromRequest(RequestAbstract $request) : Milage
    {
        $milage              = new Milage();
        $milage->vehicle     = (int) $request->getData('vehicle');
        $milage->driver      = $request->hasData('driver') ? new NullDriver((int) $request->getData('driver')) : null;
        $milage->milage      = $request->getDataInt('milage') ?? 0;
        $milage->status      = $request->getDataInt('status') ?? 0;
        $milage->fuelUsage   = $request->getDataInt('fuel') ?? 0;
        $milage->description = $request->getDataString('description') ?? '';
        $milage->from        = $request->getDataString('from') ?? '';
        $milage->to          = $request->getDataString('to') ?? '';
        $milage->start       = $request->getDataDateTime('start');
        $milage->end         = $request->getDataDateTime('end');

        return $milage;
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
    private function validateMilageCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['vehicle'] = !$request->hasData('vehicle'))) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create a vehicle
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiInspectionCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateInspectionCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        /** @var \Modules\FleetManagement\Models\Inspection $inspection */
        $inspection = $this->createInspectionFromRequest($request);
        $this->createModel($request->header->account, $inspection, InspectionMapper::class, 'inspection', $request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $inspection);
    }

    /**
     * Method to create vehicle from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return Inspection Returns the created vehicle from the request
     *
     * @since 1.0.0
     */
    public function createInspectionFromRequest(RequestAbstract $request) : Inspection
    {
        $inspection              = new Inspection();
        $inspection->reference   = (int) $request->getData('ref');
        $inspection->description = $request->getDataString('description') ?? '';
        $inspection->status      = $request->getDataInt('status') ?? InspectionStatus::TODO;
        $inspection->next        = $request->getDataDateTime('next') ?? null;
        $inspection->date        = $request->getDataDateTime('date') ?? null;
        $inspection->interval    = $request->getDataInt('interval') ?? 0;
        $inspection->type        = new NullBaseStringL11nType((int) $request->getData('type'));

        return $inspection;
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
    private function validateInspectionCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['ref'] = !$request->hasData('ref'))) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create a vehicle
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiVehicleTypeCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateVehicleTypeCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        /** @var BaseStringL11nType $vehicle */
        $vehicle = $this->createVehicleTypeFromRequest($request);
        $this->createModel($request->header->account, $vehicle, VehicleTypeMapper::class, 'vehicle_type', $request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $vehicle);
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
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiVehicleTypeL11nCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateVehicleTypeL11nCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        $typeL11n = $this->createVehicleTypeL11nFromRequest($request);
        $this->createModel($request->header->account, $typeL11n, VehicleTypeL11nMapper::class, 'vehicle_type_l11n', $request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $typeL11n);
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
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiFuelTypeCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateFuelTypeCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        /** @var BaseStringL11nType $vehicle */
        $vehicle = $this->createFuelTypeFromRequest($request);
        $this->createModel($request->header->account, $vehicle, FuelTypeMapper::class, 'fuel_type', $request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $vehicle);
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
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiFuelTypeL11nCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateFuelTypeL11nCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        $typeL11n = $this->createFuelTypeL11nFromRequest($request);
        $this->createModel($request->header->account, $typeL11n, FuelTypeL11nMapper::class, 'fuel_type_l11n', $request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $typeL11n);
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
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiVehicleCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateVehicleCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

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

        $this->createStandardCreateResponse($request, $response, $vehicle);
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
        $vehicle->status   = $request->getDataInt('status') ?? VehicleStatus::INACTIVE;
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
                    'files',
                    '',
                    $request->getOrigin()
                );

                if ($collection === null) {
                    /** @var \Modules\Media\Models\Collection $collection */
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
                    'files',
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
                    /** @var \Modules\Media\Models\Collection $collection */
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
     * Api method to create a bill
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiMediaAddToVehicle(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateMediaAddToVehicle($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidAddResponse($request, $response, $val);

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
                readContent: $request->getDataBool('parse_content') ?? false
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
                    /** @var \Modules\Media\Models\Collection $collection */
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
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiInspectionTypeCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateInspectionTypeCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        /** @var BaseStringL11nType $inspection */
        $inspection = $this->createInspectionTypeFromRequest($request);
        $this->createModel($request->header->account, $inspection, InspectionTypeMapper::class, 'inspection_type', $request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $inspection);
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
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiInspectionTypeL11nCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateInspectionTypeL11nCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        $typeL11n = $this->createInspectionTypeL11nFromRequest($request);
        $this->createModel($request->header->account, $typeL11n, InspectionTypeL11nMapper::class, 'inspection_type_l11n', $request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $typeL11n);
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
     * Api method to create notes
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiNoteCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateNoteCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        $request->setData('virtualpath', '/Modules/FleetManagement/Vehicle/' . $request->getData('id'), true);
        $this->app->moduleManager->get('Editor', 'Api')->apiEditorCreate($request, $response, $data);

        if ($response->header->status !== RequestStatusCode::R_200) {
            return;
        }

        $responseData = $response->getDataArray($request->uri->__toString());
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

    /**
     * Api method to update Note
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiNoteUpdate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        // @todo: check permissions
        $this->app->moduleManager->get('Editor', 'Api')->apiEditorDocUpdate($request, $response, $data);
    }

    /**
     * Api method to delete Note
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiNoteDelete(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        // @todo: check permissions
        $this->app->moduleManager->get('Editor', 'Api')->apiEditorDocDelete($request, $response, $data);
    }
}
