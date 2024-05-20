<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\FleetManagement
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\FleetManagement\Controller;

use Modules\Admin\Models\NullAccount;
use Modules\FleetManagement\Models\Driver\Driver;
use Modules\FleetManagement\Models\Driver\DriverInspectionMapper;
use Modules\FleetManagement\Models\Driver\DriverInspectionTypeL11nMapper;
use Modules\FleetManagement\Models\Driver\DriverInspectionTypeMapper;
use Modules\FleetManagement\Models\Driver\DriverMapper;
use Modules\FleetManagement\Models\Driver\DriverStatus;
use Modules\FleetManagement\Models\Inspection;
use Modules\FleetManagement\Models\InspectionStatus;
use Modules\FleetManagement\Models\PermissionCategory;
use Modules\Media\Models\NullCollection;
use Modules\Media\Models\PathSettings;
use phpOMS\Account\PermissionType;
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
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class ApiDriverController extends Controller
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
    public function apiInspectionCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateInspectionCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        /** @var Inspection $inspection */
        $inspection = $this->createInspectionFromRequest($request);
        $this->createModel($request->header->account, $inspection, DriverInspectionMapper::class, 'inspection', $request->getOrigin());
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
        $inspection->status      = InspectionStatus::tryFromValue($request->getDataInt('status')) ?? InspectionStatus::TODO;
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
     * Api method to create a driver
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
    public function apiDriverCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateDriverCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        /** @var Driver $driver */
        $driver = $this->createDriverFromRequest($request);
        $this->createModel($request->header->account, $driver, DriverMapper::class, 'driver', $request->getOrigin());

        if (!empty($request->files)
            || !empty($request->getDataJson('media'))
        ) {
            $this->createDriverMedia($driver, $request);
        }

        $this->createStandardCreateResponse($request, $response, $driver);
    }

    /**
     * Method to create driver from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return Driver Returns the created driver from the request
     *
     * @since 1.0.0
     */
    public function createDriverFromRequest(RequestAbstract $request) : Driver
    {
        $driver          = new Driver();
        $driver->account = new NullAccount($request->getDataInt('account') ?? 1);
        $driver->status  = DriverStatus::tryFromValue($request->getDataInt('status')) ?? DriverStatus::INACTIVE;

        return $driver;
    }

    /**
     * Create media files for driver
     *
     * @param Driver          $driver  Driver
     * @param RequestAbstract $request Request incl. media do upload
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function createDriverMedia(Driver $driver, RequestAbstract $request) : void
    {
        $path = $this->createDriverDir($driver);

        if (!empty($request->files)) {
            $this->app->moduleManager->get('Media', 'Api')->uploadFiles(
                names: [],
                fileNames: [],
                files: $request->files,
                account: $request->header->account,
                basePath: __DIR__ . '/../../../Modules/Media/Files' . $path,
                virtualPath: $path,
                pathSettings: PathSettings::FILE_PATH,
                rel: $driver->id,
                mapper: DriverMapper::class,
                field: 'files'
            );
        }

        if (!empty($media = $request->getDataJson('media'))) {
            $this->app->moduleManager->get('Media', 'Api')->addMediaToCollectionAndModel(
                $request->header->account,
                $media,
                $driver->id,
                DriverMapper::class,
                'files',
                $path
            );
        }
    }

    /**
     * Validate driver create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool> Returns the validation array of the request
     *
     * @since 1.0.0
     */
    private function validateDriverCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['account'] = !$request->hasData('account'))
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
    public function apiMediaAddToDriver(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateMediaAddToDriver($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidAddResponse($request, $response, $val);

            return;
        }

        /** @var \Modules\FleetManagement\Models\Driver\Driver $driver */
        $driver = DriverMapper::get()->where('id', (int) $request->getData('driver'))->execute();
        $path   = $this->createDriverDir($driver);

        $uploaded = new NullCollection();
        if (!empty($request->files)) {
            $uploaded = $this->app->moduleManager->get('Media', 'Api')->uploadFiles(
                names: [],
                fileNames: [],
                files: $request->files,
                account: $request->header->account,
                basePath: __DIR__ . '/../../../Modules/Media/Files' . $path,
                virtualPath: $path,
                pathSettings: PathSettings::FILE_PATH,
                hasAccountRelation: false,
                readContent: $request->getDataBool('parse_content') ?? false,
                tag: $request->getDataInt('tag'),
                rel: $driver->id,
                mapper: DriverMapper::class,
                field: 'files'
            );
        }

        if (!empty($media = $request->getDataJson('media'))) {
            $this->app->moduleManager->get('Media', 'Api')->addMediaToCollectionAndModel(
                $request->header->account,
                $media,
                $driver->id,
                DriverMapper::class,
                'files',
                $path
            );
        }

        $this->fillJsonResponse($request, $response, NotificationLevel::OK, '', $this->app->l11nManager->getText($response->header->l11n->language, '0', '0', 'SuccessfulAdd'), [
            'upload' => $uploaded->sources,
            'media'  => $media,
        ]);
    }

    /**
     * Create media directory path
     *
     * @param Driver $driver Driver
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function createDriverDir(Driver $driver) : string
    {
        return '/Modules/FleetManagement/Driver/'
            . $this->app->unitId . '/'
            . $driver->id;
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
    private function validateMediaAddToDriver(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['media'] = (!$request->hasData('media') && empty($request->files)))
            || ($val['driver'] = !$request->hasData('driver'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create a driver
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
    public function apiDriverInspectionTypeCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateDriverInspectionTypeCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        /** @var BaseStringL11nType $inspection */
        $inspection = $this->createDriverInspectionTypeFromRequest($request);
        $this->createModel($request->header->account, $inspection, DriverInspectionTypeMapper::class, 'driver_inspection_type', $request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $inspection);
    }

    /**
     * Method to create driver from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return BaseStringL11nType Returns the created driver from the request
     *
     * @since 1.0.0
     */
    public function createDriverInspectionTypeFromRequest(RequestAbstract $request) : BaseStringL11nType
    {
        $type        = new BaseStringL11nType();
        $type->title = $request->getDataString('name') ?? '';
        $type->setL11n(
            $request->getDataString('title') ?? '',
            ISO639x1Enum::tryFromValue($request->getDataString('language')) ?? ISO639x1Enum::_EN
        );

        return $type;
    }

    /**
     * Validate driver create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool> Returns the validation array of the request
     *
     * @since 1.0.0
     */
    private function validateDriverInspectionTypeCreate(RequestAbstract $request) : array
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
     * Api method to create driver attribute l11n
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
    public function apiDriverInspectionTypeL11nCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateDriverInspectionTypeL11nCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        $typeL11n = $this->createDriverInspectionTypeL11nFromRequest($request);
        $this->createModel($request->header->account, $typeL11n, DriverInspectionTypeL11nMapper::class, 'driver_inspection_type_l11n', $request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $typeL11n);
    }

    /**
     * Method to create driver attribute l11n from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return BaseStringL11n
     *
     * @since 1.0.0
     */
    private function createDriverInspectionTypeL11nFromRequest(RequestAbstract $request) : BaseStringL11n
    {
        $typeL11n           = new BaseStringL11n();
        $typeL11n->ref      = $request->getDataInt('type') ?? 0;
        $typeL11n->language = ISO639x1Enum::tryFromValue($request->getDataString('language')) ?? $request->header->l11n->language;
        $typeL11n->content  = $request->getDataString('title') ?? '';

        return $typeL11n;
    }

    /**
     * Validate driver attribute l11n create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateDriverInspectionTypeL11nCreate(RequestAbstract $request) : array
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

        $request->setData('virtualpath', '/Modules/FleetManagement/Driver/' . $request->getData('id'), true);
        $this->app->moduleManager->get('Editor', 'Api')->apiEditorCreate($request, $response, $data);

        if ($response->header->status !== RequestStatusCode::R_200) {
            return;
        }

        $responseData = $response->getDataArray($request->uri->__toString());
        if (!\is_array($responseData)) {
            return;
        }

        $model = $responseData['response'];
        $this->createModelRelation($request->header->account, (int) $request->getData('id'), $model->id, DriverMapper::class, 'notes', '', $request->getOrigin());
    }

    /**
     * Validate note create request
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
        $accountId = $request->header->account;
        if (!$this->app->accountManager->get($accountId)->hasPermission(
            PermissionType::MODIFY, $this->app->unitId, $this->app->appId, self::NAME, PermissionCategory::DRIVER_NOTE, $request->getDataInt('id'))
        ) {
            $this->fillJsonResponse($request, $response, NotificationLevel::HIDDEN, '', '', []);
            $response->header->status = RequestStatusCode::R_403;

            return;
        }

        $this->app->moduleManager->get('Editor', 'Api')->apiEditorUpdate($request, $response, $data);
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
        $accountId = $request->header->account;
        if (!$this->app->accountManager->get($accountId)->hasPermission(
            PermissionType::DELETE, $this->app->unitId, $this->app->appId, self::NAME, PermissionCategory::DRIVER_NOTE, $request->getDataInt('id'))
        ) {
            $this->fillJsonResponse($request, $response, NotificationLevel::HIDDEN, '', '', []);
            $response->header->status = RequestStatusCode::R_403;

            return;
        }

        $this->app->moduleManager->get('Editor', 'Api')->apiEditorDelete($request, $response, $data);
    }
}
