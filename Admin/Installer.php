<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\FleetManagement\Admin
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\FleetManagement\Admin;

use phpOMS\Application\ApplicationAbstract;
use phpOMS\Config\SettingsInterface;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\HttpResponse;
use phpOMS\Module\InstallerAbstract;
use phpOMS\Module\ModuleInfo;

/**
 * Installer class.
 *
 * @package Modules\FleetManagement\Admin
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Installer extends InstallerAbstract
{
    /**
     * Path of the file
     *
     * @var string
     * @since 1.0.0
     */
    public const PATH = __DIR__;

    /**
     * {@inheritdoc}
     */
    public static function install(ApplicationAbstract $app, ModuleInfo $info, SettingsInterface $cfgHandler) : void
    {
        parent::install($app, $info, $cfgHandler);

        /* Vehicle Attributes */
        $fileContent = \file_get_contents(__DIR__ . '/Install/vehicle_attributes.json');
        if ($fileContent === false) {
            return;
        }

        /** @var array $attributes */
        $attributes = \json_decode($fileContent, true);
        $attrTypes  = self::createVehicleAttributeTypes($app, $attributes);
        $attrValues = self::createVehicleAttributeValues($app, $attrTypes, $attributes);

        /* Driver Attributes */
        $fileContent = \file_get_contents(__DIR__ . '/Install/driver_attributes.json');
        if ($fileContent === false) {
            return;
        }

        /** @var array $attributes */
        $attributes = \json_decode($fileContent, true);
        $attrTypes  = self::createDriverAttributeTypes($app, $attributes);
        $attrValues = self::createDriverAttributeValues($app, $attrTypes, $attributes);

        /* Fuel types */
        $fileContent = \file_get_contents(__DIR__ . '/Install/fueltype.json');
        if ($fileContent === false) {
            return;
        }

        /** @var array $types */
        $types     = \json_decode($fileContent, true);
        $fuelTypes = self::createFuelTypes($app, $types);

        /* Vehicle types */
        $fileContent = \file_get_contents(__DIR__ . '/Install/vehicle_type.json');
        if ($fileContent === false) {
            return;
        }

        /** @var array $types */
        $types        = \json_decode($fileContent, true);
        $vehicleTypes = self::createVehicleTypes($app, $types);

        /* Vehicle Inspection types */
        $fileContent = \file_get_contents(__DIR__ . '/Install/vehicle_inspectiontype.json');
        if ($fileContent === false) {
            return;
        }

        /** @var array $types */
        $types           = \json_decode($fileContent, true);
        $inspectionTypes = self::createVehicleInspectionTypes($app, $types);

        /* Driver Inspection types */
        $fileContent = \file_get_contents(__DIR__ . '/Install/driver_inspectiontype.json');
        if ($fileContent === false) {
            return;
        }

        /** @var array $types */
        $types           = \json_decode($fileContent, true);
        $inspectionTypes = self::createDriverInspectionTypes($app, $types);
    }

    /**
     * Install fuel type
     *
     * @param ApplicationAbstract $app   Application
     * @param array               $types Attribute definition
     *
     * @return array
     *
     * @since 1.0.0
     */
    private static function createFuelTypes(ApplicationAbstract $app, array $types) : array
    {
        /** @var array<string, array> $fuelTypes */
        $fuelTypes = [];

        /** @var \Modules\FleetManagement\Controller\ApiVehicleController $module */
        $module = $app->moduleManager->get('FleetManagement', 'ApiVehicle');

        /** @var array $type */
        foreach ($types as $type) {
            $response = new HttpResponse();
            $request  = new HttpRequest();

            $request->header->account = 1;
            $request->setData('name', $type['name'] ?? '');
            $request->setData('title', \reset($type['l11n']));
            $request->setData('language', \array_keys($type['l11n'])[0] ?? 'en');

            $module->apiFuelTypeCreate($request, $response);

            $responseData = $response->getData('');
            if (!\is_array($responseData)) {
                continue;
            }

            $fuelTypes[$type['name']] = \is_array($responseData['response'])
                ? $responseData['response']
                : $responseData['response']->toArray();

            $isFirst = true;
            foreach ($type['l11n'] as $language => $l11n) {
                if ($isFirst) {
                    $isFirst = false;
                    continue;
                }

                $response = new HttpResponse();
                $request  = new HttpRequest();

                $request->header->account = 1;
                $request->setData('title', $l11n);
                $request->setData('language', $language);
                $request->setData('type', $fuelTypes[$type['name']]['id']);

                $module->apiFuelTypeL11nCreate($request, $response);
            }
        }

        return $fuelTypes;
    }

    /**
     * Install vehicle type
     *
     * @param ApplicationAbstract $app   Application
     * @param array               $types Attribute definition
     *
     * @return array
     *
     * @since 1.0.0
     */
    private static function createVehicleTypes(ApplicationAbstract $app, array $types) : array
    {
        /** @var array<string, array> $vehicleTypes */
        $vehicleTypes = [];

        /** @var \Modules\FleetManagement\Controller\ApiVehicleController $module */
        $module = $app->moduleManager->get('FleetManagement', 'ApiVehicle');

        /** @var array $type */
        foreach ($types as $type) {
            $response = new HttpResponse();
            $request  = new HttpRequest();

            $request->header->account = 1;
            $request->setData('name', $type['name'] ?? '');
            $request->setData('title', \reset($type['l11n']));
            $request->setData('language', \array_keys($type['l11n'])[0] ?? 'en');

            $module->apiVehicleTypeCreate($request, $response);

            $responseData = $response->getData('');
            if (!\is_array($responseData)) {
                continue;
            }

            $vehicleTypes[$type['name']] = \is_array($responseData['response'])
                ? $responseData['response']
                : $responseData['response']->toArray();

            $isFirst = true;
            foreach ($type['l11n'] as $language => $l11n) {
                if ($isFirst) {
                    $isFirst = false;
                    continue;
                }

                $response = new HttpResponse();
                $request  = new HttpRequest();

                $request->header->account = 1;
                $request->setData('title', $l11n);
                $request->setData('language', $language);
                $request->setData('type', $vehicleTypes[$type['name']]['id']);

                $module->apiVehicleTypeL11nCreate($request, $response);
            }
        }

        return $vehicleTypes;
    }

    /**
     * Install inspection type
     *
     * @param ApplicationAbstract $app   Application
     * @param array               $types Attribute definition
     *
     * @return array
     *
     * @since 1.0.0
     */
    private static function createVehicleInspectionTypes(ApplicationAbstract $app, array $types) : array
    {
        /** @var array<string, array> $inspectionTypes */
        $inspectionTypes = [];

        /** @var \Modules\FleetManagement\Controller\ApiVehicleController $module */
        $module = $app->moduleManager->get('FleetManagement', 'ApiVehicle');

        /** @var array $type */
        foreach ($types as $type) {
            $response = new HttpResponse();
            $request  = new HttpRequest();

            $request->header->account = 1;
            $request->setData('name', $type['name'] ?? '');
            $request->setData('title', \reset($type['l11n']));
            $request->setData('language', \array_keys($type['l11n'])[0] ?? 'en');

            $module->apiInspectionTypeCreate($request, $response);

            $responseData = $response->getData('');
            if (!\is_array($responseData)) {
                continue;
            }

            $inspectionTypes[$type['name']] = \is_array($responseData['response'])
                ? $responseData['response']
                : $responseData['response']->toArray();

            $isFirst = true;
            foreach ($type['l11n'] as $language => $l11n) {
                if ($isFirst) {
                    $isFirst = false;
                    continue;
                }

                $response = new HttpResponse();
                $request  = new HttpRequest();

                $request->header->account = 1;
                $request->setData('title', $l11n);
                $request->setData('language', $language);
                $request->setData('type', $inspectionTypes[$type['name']]['id']);

                $module->apiInspectionTypeL11nCreate($request, $response);
            }
        }

        return $inspectionTypes;
    }

    /**
     * Install inspection type
     *
     * @param ApplicationAbstract $app   Application
     * @param array               $types Attribute definition
     *
     * @return array
     *
     * @since 1.0.0
     */
    private static function createDriverInspectionTypes(ApplicationAbstract $app, array $types) : array
    {
        /** @var array<string, array> $inspectionTypes */
        $inspectionTypes = [];

        /** @var \Modules\FleetManagement\Controller\ApiDriverController $module */
        $module = $app->moduleManager->get('FleetManagement', 'ApiDriver');

        /** @var array $type */
        foreach ($types as $type) {
            $response = new HttpResponse();
            $request  = new HttpRequest();

            $request->header->account = 1;
            $request->setData('name', $type['name'] ?? '');
            $request->setData('title', \reset($type['l11n']));
            $request->setData('language', \array_keys($type['l11n'])[0] ?? 'en');

            $module->apiDriverInspectionTypeCreate($request, $response);

            $responseData = $response->getData('');
            if (!\is_array($responseData)) {
                continue;
            }

            $inspectionTypes[$type['name']] = \is_array($responseData['response'])
                ? $responseData['response']
                : $responseData['response']->toArray();

            $isFirst = true;
            foreach ($type['l11n'] as $language => $l11n) {
                if ($isFirst) {
                    $isFirst = false;
                    continue;
                }

                $response = new HttpResponse();
                $request  = new HttpRequest();

                $request->header->account = 1;
                $request->setData('title', $l11n);
                $request->setData('language', $language);
                $request->setData('type', $inspectionTypes[$type['name']]['id']);

                $module->apiDriverInspectionTypeL11nCreate($request, $response);
            }
        }

        return $inspectionTypes;
    }

    /**
     * Install default attribute types
     *
     * @param ApplicationAbstract $app        Application
     * @param array               $attributes Attribute definition
     *
     * @return array
     *
     * @since 1.0.0
     */
    private static function createVehicleAttributeTypes(ApplicationAbstract $app, array $attributes) : array
    {
        /** @var array<string, array> $itemAttrType */
        $itemAttrType = [];

        /** @var \Modules\FleetManagement\Controller\ApiVehicleAttributeController $module */
        $module = $app->moduleManager->get('FleetManagement', 'ApiVehicleAttribute');

        /** @var array $attribute */
        foreach ($attributes as $attribute) {
            $response = new HttpResponse();
            $request  = new HttpRequest();

            $request->header->account = 1;
            $request->setData('name', $attribute['name'] ?? '');
            $request->setData('title', \reset($attribute['l11n']));
            $request->setData('language', \array_keys($attribute['l11n'])[0] ?? 'en');
            $request->setData('repeatable', $attribute['repeatable'] ?? false);
            $request->setData('internal', $attribute['internal'] ?? false);
            $request->setData('is_required', $attribute['is_required'] ?? false);
            $request->setData('custom', $attribute['is_custom_allowed'] ?? false);
            $request->setData('validation_pattern', $attribute['validation_pattern'] ?? '');
            $request->setData('datatype', (int) $attribute['value_type']);

            $module->apiVehicleAttributeTypeCreate($request, $response);

            $responseData = $response->getData('');
            if (!\is_array($responseData)) {
                continue;
            }

            $itemAttrType[$attribute['name']] = \is_array($responseData['response'])
                ? $responseData['response']
                : $responseData['response']->toArray();

            $isFirst = true;
            foreach ($attribute['l11n'] as $language => $l11n) {
                if ($isFirst) {
                    $isFirst = false;
                    continue;
                }

                $response = new HttpResponse();
                $request  = new HttpRequest();

                $request->header->account = 1;
                $request->setData('title', $l11n);
                $request->setData('language', $language);
                $request->setData('type', $itemAttrType[$attribute['name']]['id']);

                $module->apiVehicleAttributeTypeL11nCreate($request, $response);
            }
        }

        return $itemAttrType;
    }

    /**
     * Create default attribute values for types
     *
     * @param ApplicationAbstract                                                                                                                                                              $app          Application
     * @param array                                                                                                                                                                            $itemAttrType Attribute types
     * @param array<array{name:string, l11n?:array<string, string>, is_required?:bool, is_custom_allowed?:bool, validation_pattern?:string, value_type?:string, values?:array<string, mixed>}> $attributes   Attribute definition
     *
     * @return array
     *
     * @since 1.0.0
     */
    private static function createVehicleAttributeValues(ApplicationAbstract $app, array $itemAttrType, array $attributes) : array
    {
        /** @var array<string, array> $itemAttrValue */
        $itemAttrValue = [];

        /** @var \Modules\FleetManagement\Controller\ApiVehicleAttributeController $module */
        $module = $app->moduleManager->get('FleetManagement', 'ApiVehicleAttribute');

        foreach ($attributes as $attribute) {
            $itemAttrValue[$attribute['name']] = [];

            /** @var array $value */
            foreach ($attribute['values'] as $value) {
                $response = new HttpResponse();
                $request  = new HttpRequest();

                $request->header->account = 1;
                $request->setData('value', $value['value'] ?? '');
                $request->setData('unit', $value['unit'] ?? '');
                $request->setData('default', true); // always true since all defined values are possible default values
                $request->setData('type', $itemAttrType[$attribute['name']]['id']);

                if (isset($value['l11n']) && !empty($value['l11n'])) {
                    $request->setData('title', \reset($value['l11n']));
                    $request->setData('language', \array_keys($value['l11n'])[0] ?? 'en');
                }

                $module->apiVehicleAttributeValueCreate($request, $response);

                $responseData = $response->getData('');
                if (!\is_array($responseData)) {
                    continue;
                }

                $attrValue = \is_array($responseData['response'])
                    ? $responseData['response']
                    : $responseData['response']->toArray();

                $itemAttrValue[$attribute['name']][] = $attrValue;

                $isFirst = true;
                foreach (($value['l11n'] ?? []) as $language => $l11n) {
                    if ($isFirst) {
                        $isFirst = false;
                        continue;
                    }

                    $response = new HttpResponse();
                    $request  = new HttpRequest();

                    $request->header->account = 1;
                    $request->setData('title', $l11n);
                    $request->setData('language', $language);
                    $request->setData('value', $attrValue['id']);

                    $module->apiVehicleAttributeValueL11nCreate($request, $response);
                }
            }
        }

        return $itemAttrValue;
    }

    /**
     * Install default attribute types
     *
     * @param ApplicationAbstract $app        Application
     * @param array               $attributes Attribute definition
     *
     * @return array
     *
     * @since 1.0.0
     */
    private static function createDriverAttributeTypes(ApplicationAbstract $app, array $attributes) : array
    {
        /** @var array<string, array> $itemAttrType */
        $itemAttrType = [];

        /** @var \Modules\FleetManagement\Controller\ApiDriverAttributeController $module */
        $module = $app->moduleManager->get('FleetManagement', 'ApiDriverAttribute');

        /** @var array $attribute */
        foreach ($attributes as $attribute) {
            $response = new HttpResponse();
            $request  = new HttpRequest();

            $request->header->account = 1;
            $request->setData('name', $attribute['name'] ?? '');
            $request->setData('title', \reset($attribute['l11n']));
            $request->setData('language', \array_keys($attribute['l11n'])[0] ?? 'en');
            $request->setData('repeatable', $attribute['repeatable'] ?? false);
            $request->setData('internal', $attribute['internal'] ?? false);
            $request->setData('is_required', $attribute['is_required'] ?? false);
            $request->setData('custom', $attribute['is_custom_allowed'] ?? false);
            $request->setData('validation_pattern', $attribute['validation_pattern'] ?? '');
            $request->setData('datatype', (int) $attribute['value_type']);

            $module->apiDriverAttributeTypeCreate($request, $response);

            $responseData = $response->getData('');
            if (!\is_array($responseData)) {
                continue;
            }

            $itemAttrType[$attribute['name']] = \is_array($responseData['response'])
                ? $responseData['response']
                : $responseData['response']->toArray();

            $isFirst = true;
            foreach ($attribute['l11n'] as $language => $l11n) {
                if ($isFirst) {
                    $isFirst = false;
                    continue;
                }

                $response = new HttpResponse();
                $request  = new HttpRequest();

                $request->header->account = 1;
                $request->setData('title', $l11n);
                $request->setData('language', $language);
                $request->setData('type', $itemAttrType[$attribute['name']]['id']);

                $module->apiDriverAttributeTypeL11nCreate($request, $response);
            }
        }

        return $itemAttrType;
    }

    /**
     * Create default attribute values for types
     *
     * @param ApplicationAbstract                                                                                                                                                              $app          Application
     * @param array                                                                                                                                                                            $itemAttrType Attribute types
     * @param array<array{name:string, l11n?:array<string, string>, is_required?:bool, is_custom_allowed?:bool, validation_pattern?:string, value_type?:string, values?:array<string, mixed>}> $attributes   Attribute definition
     *
     * @return array
     *
     * @since 1.0.0
     */
    private static function createDriverAttributeValues(ApplicationAbstract $app, array $itemAttrType, array $attributes) : array
    {
        /** @var array<string, array> $itemAttrValue */
        $itemAttrValue = [];

        /** @var \Modules\FleetManagement\Controller\ApiDriverAttributeController $module */
        $module = $app->moduleManager->get('FleetManagement', 'ApiDriverAttribute');

        foreach ($attributes as $attribute) {
            $itemAttrValue[$attribute['name']] = [];

            /** @var array $value */
            foreach ($attribute['values'] as $value) {
                $response = new HttpResponse();
                $request  = new HttpRequest();

                $request->header->account = 1;
                $request->setData('value', $value['value'] ?? '');
                $request->setData('unit', $value['unit'] ?? '');
                $request->setData('default', true); // always true since all defined values are possible default values
                $request->setData('type', $itemAttrType[$attribute['name']]['id']);

                if (isset($value['l11n']) && !empty($value['l11n'])) {
                    $request->setData('title', \reset($value['l11n']));
                    $request->setData('language', \array_keys($value['l11n'])[0] ?? 'en');
                }

                $module->apiDriverAttributeValueCreate($request, $response);

                $responseData = $response->getData('');
                if (!\is_array($responseData)) {
                    continue;
                }

                $attrValue = \is_array($responseData['response'])
                    ? $responseData['response']
                    : $responseData['response']->toArray();

                $itemAttrValue[$attribute['name']][] = $attrValue;

                $isFirst = true;
                foreach (($value['l11n'] ?? []) as $language => $l11n) {
                    if ($isFirst) {
                        $isFirst = false;
                        continue;
                    }

                    $response = new HttpResponse();
                    $request  = new HttpRequest();

                    $request->header->account = 1;
                    $request->setData('title', $l11n);
                    $request->setData('language', $language);
                    $request->setData('value', $attrValue['id']);

                    $module->apiDriverAttributeValueL11nCreate($request, $response);
                }
            }
        }

        return $itemAttrValue;
    }
}
