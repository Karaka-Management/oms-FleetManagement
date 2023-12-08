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

use Modules\Attribute\Models\Attribute;
use Modules\Attribute\Models\AttributeType;
use Modules\Attribute\Models\AttributeValue;
use Modules\FleetManagement\Models\Attribute\DriverAttributeMapper;
use Modules\FleetManagement\Models\Attribute\DriverAttributeTypeL11nMapper;
use Modules\FleetManagement\Models\Attribute\DriverAttributeTypeMapper;
use Modules\FleetManagement\Models\Attribute\DriverAttributeValueL11nMapper;
use Modules\FleetManagement\Models\Attribute\DriverAttributeValueMapper;
use phpOMS\Localization\BaseStringL11n;
use phpOMS\Message\Http\RequestStatusCode;
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
final class ApiDriverAttributeController extends Controller
{
    use \Modules\Attribute\Controller\ApiAttributeTraitController;

    /**
     * Api method to create item attribute
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
    public function apiDriverAttributeCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateAttributeCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        $type      = DriverAttributeTypeMapper::get()->with('defaults')->where('id', (int) $request->getData('type'))->execute();
        $attribute = $this->createAttributeFromRequest($request, $type);
        $this->createModel($request->header->account, $attribute, DriverAttributeMapper::class, 'attribute', $request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $attribute);
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
    public function apiDriverAttributeTypeL11nCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateAttributeTypeL11nCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        $attrL11n = $this->createAttributeTypeL11nFromRequest($request);
        $this->createModel($request->header->account, $attrL11n, DriverAttributeTypeL11nMapper::class, 'attr_type_l11n', $request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $attrL11n);
    }

    /**
     * Api method to create driver attribute type
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
    public function apiDriverAttributeTypeCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateAttributeTypeCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        $attrType = $this->createAttributeTypeFromRequest($request);
        $this->createModel($request->header->account, $attrType, DriverAttributeTypeMapper::class, 'attr_type', $request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $attrType);
    }

    /**
     * Api method to create driver attribute value
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
    public function apiDriverAttributeValueCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateAttributeValueCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        /** @var \Modules\Attribute\Models\AttributeType $type */
        $type = DriverAttributeTypeMapper::get()
            ->where('id', $request->getDataInt('type') ?? 0)
            ->execute();

        $attrValue = $this->createAttributeValueFromRequest($request, $type);
        $this->createModel($request->header->account, $attrValue, DriverAttributeValueMapper::class, 'attr_value', $request->getOrigin());

        if ($attrValue->isDefault) {
            $this->createModelRelation(
                $request->header->account,
                (int) $request->getData('type'),
                $attrValue->id,
                DriverAttributeTypeMapper::class, 'defaults', '', $request->getOrigin()
            );
        }

        $this->createStandardCreateResponse($request, $response, $attrValue);
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
    public function apiDriverAttributeValueL11nCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateAttributeValueL11nCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        $attrL11n = $this->createAttributeValueL11nFromRequest($request);
        $this->createModel($request->header->account, $attrL11n, DriverAttributeValueL11nMapper::class, 'attr_value_l11n', $request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $attrL11n);
    }

    /**
     * Api method to update DriverAttribute
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
    public function apiDriverAttributeUpdate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateAttributeUpdate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidUpdateResponse($request, $response, $val);

            return;
        }

        /** @var Attribute $old */
        $old = DriverAttributeMapper::get()
            ->with('type')
            ->with('type/defaults')
            ->with('value')
            ->where('id', (int) $request->getData('id'))
            ->execute();

        $new = $this->updateAttributeFromRequest($request, clone $old);

        if ($new->id === 0) {
            // Set response header to invalid request because of invalid data
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidUpdateResponse($request, $response, $new);

            return;
        }

        $this->updateModel($request->header->account, $old, $new, DriverAttributeMapper::class, 'driver_attribute', $request->getOrigin());

        if ($new->value->getValue() !== $old->value->getValue()
            && $new->type->custom
        ) {
            $this->updateModel($request->header->account, $old->value, $new->value, DriverAttributeValueMapper::class, 'attribute_value', $request->getOrigin());
        }

        $this->createStandardUpdateResponse($request, $response, $new);
    }

    /**
     * Api method to delete DriverAttribute
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
    public function apiDriverAttributeDelete(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateAttributeDelete($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidDeleteResponse($request, $response, $val);

            return;
        }

        $driverAttribute = DriverAttributeMapper::get()
            ->with('type')
            ->where('id', (int) $request->getData('id'))
            ->execute();

        if ($driverAttribute->type->isRequired) {
            $this->createInvalidDeleteResponse($request, $response, []);

            return;
        }

        $this->deleteModel($request->header->account, $driverAttribute, DriverAttributeMapper::class, 'driver_attribute', $request->getOrigin());
        $this->createStandardDeleteResponse($request, $response, $driverAttribute);
    }

    /**
     * Api method to update DriverAttributeTypeL11n
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
    public function apiDriverAttributeTypeL11nUpdate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateAttributeTypeL11nUpdate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidUpdateResponse($request, $response, $val);

            return;
        }

        /** @var BaseStringL11n $old */
        $old = DriverAttributeTypeL11nMapper::get()->where('id', (int) $request->getData('id'))->execute();
        $new = $this->updateAttributeTypeL11nFromRequest($request, clone $old);

        $this->updateModel($request->header->account, $old, $new, DriverAttributeTypeL11nMapper::class, 'driver_attribute_type_l11n', $request->getOrigin());
        $this->createStandardUpdateResponse($request, $response, $new);
    }

    /**
     * Api method to delete DriverAttributeTypeL11n
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
    public function apiDriverAttributeTypeL11nDelete(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateAttributeTypeL11nDelete($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidDeleteResponse($request, $response, $val);

            return;
        }

        /** @var BaseStringL11n $driverAttributeTypeL11n */
        $driverAttributeTypeL11n = DriverAttributeTypeL11nMapper::get()->where('id', (int) $request->getData('id'))->execute();
        $this->deleteModel($request->header->account, $driverAttributeTypeL11n, DriverAttributeTypeL11nMapper::class, 'driver_attribute_type_l11n', $request->getOrigin());
        $this->createStandardDeleteResponse($request, $response, $driverAttributeTypeL11n);
    }

    /**
     * Api method to update DriverAttributeType
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
    public function apiDriverAttributeTypeUpdate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateAttributeTypeUpdate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidUpdateResponse($request, $response, $val);

            return;
        }

        /** @var AttributeType $old */
        $old = DriverAttributeTypeMapper::get()->with('defaults')->where('id', (int) $request->getData('id'))->execute();
        $new = $this->updateAttributeTypeFromRequest($request, clone $old);

        $this->updateModel($request->header->account, $old, $new, DriverAttributeTypeMapper::class, 'driver_attribute_type', $request->getOrigin());
        $this->createStandardUpdateResponse($request, $response, $new);
    }

    /**
     * Api method to delete DriverAttributeType
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @todo Implement API function
     *
     * @since 1.0.0
     */
    public function apiDriverAttributeTypeDelete(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateAttributeTypeDelete($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidDeleteResponse($request, $response, $val);

            return;
        }

        /** @var AttributeType $driverAttributeType */
        $driverAttributeType = DriverAttributeTypeMapper::get()->with('defaults')->where('id', (int) $request->getData('id'))->execute();
        $this->deleteModel($request->header->account, $driverAttributeType, DriverAttributeTypeMapper::class, 'driver_attribute_type', $request->getOrigin());
        $this->createStandardDeleteResponse($request, $response, $driverAttributeType);
    }

    /**
     * Api method to update DriverAttributeValue
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
    public function apiDriverAttributeValueUpdate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateAttributeValueUpdate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidUpdateResponse($request, $response, $val);

            return;
        }

        /** @var AttributeValue $old */
        $old = DriverAttributeValueMapper::get()->where('id', (int) $request->getData('id'))->execute();

        /** @var \Modules\Attribute\Models\Attribute $attr */
        $attr = DriverAttributeMapper::get()
            ->with('type')
            ->where('id', $request->getDataInt('attribute') ?? 0)
            ->execute();

        $new = $this->updateAttributeValueFromRequest($request, clone $old, $attr);

        $this->updateModel($request->header->account, $old, $new, DriverAttributeValueMapper::class, 'driver_attribute_value', $request->getOrigin());
        $this->createStandardUpdateResponse($request, $response, $new);
    }

    /**
     * Api method to delete DriverAttributeValue
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
    public function apiDriverAttributeValueDelete(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        // @todo I don't think values can be deleted? Only Attributes
        // However, It should be possible to remove UNUSED default values
        // either here or other function?
        // if (!empty($val = $this->validateAttributeValueDelete($request))) {
        //     $response->header->status = RequestStatusCode::R_400;
        //     $this->createInvalidDeleteResponse($request, $response, $val);

        //     return;
        // }

        // /** @var \Modules\FleetManagement\Models\DriverAttributeValue $driverAttributeValue */
        // $driverAttributeValue = DriverAttributeValueMapper::get()->where('id', (int) $request->getData('id'))->execute();
        // $this->deleteModel($request->header->account, $driverAttributeValue, DriverAttributeValueMapper::class, 'driver_attribute_value', $request->getOrigin());
        // $this->createStandardDeleteResponse($request, $response, $driverAttributeValue);
    }

    /**
     * Api method to update DriverAttributeValueL11n
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
    public function apiDriverAttributeValueL11nUpdate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateAttributeValueL11nUpdate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidUpdateResponse($request, $response, $val);

            return;
        }

        /** @var BaseStringL11n $old */
        $old = DriverAttributeValueL11nMapper::get()->where('id', (int) $request->getData('id'));
        $new = $this->updateAttributeValueL11nFromRequest($request, clone $old);

        $this->updateModel($request->header->account, $old, $new, DriverAttributeValueL11nMapper::class, 'driver_attribute_value_l11n', $request->getOrigin());
        $this->createStandardUpdateResponse($request, $response, $new);
    }

    /**
     * Api method to delete DriverAttributeValueL11n
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
    public function apiDriverAttributeValueL11nDelete(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateAttributeValueL11nDelete($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidDeleteResponse($request, $response, $val);

            return;
        }

        /** @var BaseStringL11n $driverAttributeValueL11n */
        $driverAttributeValueL11n = DriverAttributeValueL11nMapper::get()->where('id', (int) $request->getData('id'))->execute();
        $this->deleteModel($request->header->account, $driverAttributeValueL11n, DriverAttributeValueL11nMapper::class, 'driver_attribute_value_l11n', $request->getOrigin());
        $this->createStandardDeleteResponse($request, $response, $driverAttributeValueL11n);
    }
}
