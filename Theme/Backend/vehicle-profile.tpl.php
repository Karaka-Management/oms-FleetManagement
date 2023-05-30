<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\ClientManagement
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use Modules\FleetManagement\Models\NullVehicle;
use Modules\FleetManagement\Models\VehicleStatus;
use Modules\Media\Models\NullMedia;
use phpOMS\Uri\UriFactory;

$countryCodes  = \phpOMS\Localization\ISO3166TwoEnum::getConstants();
$countries     = \phpOMS\Localization\ISO3166NameEnum::getConstants();
$vehicleStatus = VehicleStatus::getConstants();

/**
 * @var \Modules\FleetManagement\Models\Vehicle $vehicle
 */
$vehicle      = $this->getData('vehicle') ?? new NullVehicle();
$files        = $vehicle->files;
$vehicleImage = $this->getData('vehicleImage') ?? new NullMedia();
$vehicleTypes = $this->getData('types') ?? [];

/**
 * @var \phpOMS\Views\View $this
 */
echo $this->getData('nav')->render();
?>
<div class="tabview tab-2">
    <div class="box">
        <ul class="tab-links">
            <li><label for="c-tab-1"><?= $this->getHtml('Profile'); ?></label>
            <li><label for="c-tab-6"><?= $this->getHtml('Attributes'); ?></label>
            <li><label for="c-tab-3"><?= $this->getHtml('Files'); ?></label>
            <li><label for="c-tab-3"><?= $this->getHtml('Notes'); ?></label>
            <li><label for="c-tab-4"><?= $this->getHtml('Inspections'); ?></label>
            <li><label for="c-tab-5"><?= $this->getHtml('Drivers'); ?></label>
            <li><label for="c-tab-6"><?= $this->getHtml('Milage'); ?></label>
        </ul>
    </div>
    <div class="tab-content">
        <input type="radio" id="c-tab-1" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-1' ? ' checked' : ''; ?>>
        <div class="tab">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <section class="portlet">
                        <div class="portlet-head"><?= $this->getHtml('Profile'); ?></div>
                        <div class="portlet-body">
                            <div class="form-group">
                                <label for="iFleetVehicleProfileName"><?= $this->getHtml('Name'); ?></label>
                                <input type="text" id="iFleetVehicleProfileName" name="name" value="<?= $this->printHtml($vehicle->name); ?>">
                            </div>

                            <div class="form-group">
                                <label for="iVehicleDriver"><?= $this->getHtml('Driver'); ?></label>
                                <input type="text" id="iVehicleDriver" name="driver" value="" disabled>
                            </div>

                            <div class="form-group">
                                <label for="iVehicleVin"><?= $this->getHtml('Vin'); ?></label>
                                <input type="text" id="iVehicleVin" name="vin" value="<?= $this->printHtml($vehicle->getAttribute('vin')->value->getValue()); ?>">
                            </div>

                            <div class="form-group">
                                <label for="iVehicleStatus"><?= $this->getHtml('Status'); ?></label>
                                <select id="iVehicleStatus" name="vehicle_status">
                                    <?php foreach ($vehicleStatus as $status) : ?>
                                        <option value="<?= $status; ?>"<?= $status === $vehicle->status ? ' selected' : ''; ?>><?= $this->getHtml(':status' . $status); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="iVehicleEnd"><?= $this->getHtml('Type'); ?></label>
                                <select id="iVehicleEnd" name="vehicle_type">
                                    <?php foreach ($vehicleTypes as $type) : ?>
                                        <option value="<?= $type->id; ?>"<?= $vehicle->type->id === $type->id ? ' selected' : ''; ?>><?= $this->printHtml($type->getL11n()); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="iVehicleMake"><?= $this->getHtml('Make'); ?></label>
                                <input type="text" id="iVehicleMake" name="make" value="<?= $this->printHtml($vehicle->getAttribute('maker')->value->getValue()); ?>">
                            </div>

                            <div class="form-group">
                                <label for="iVehicleModel"><?= $this->getHtml('Model'); ?></label>
                                <input type="text" id="iVehicleModel" name="vehicle_model" value="<?= $this->printHtml($vehicle->getAttribute('vehicle_model')->value->getValue()); ?>">
                            </div>

                            <div class="form-group">
                                <label for="iVehicleStart"><?= $this->getHtml('Start'); ?></label>
                                <input type="datetime-local" id="iVehicleStart" name="ownership_start" value="<?= $vehicle->getAttribute('ownership_start')->value->getValue()?->format('Y-m-d\TH:i') ?? $vehicle->createdAt->format('Y-m-d\TH:i'); ?>">
                            </div>

                            <div class="form-group">
                                <label for="iVehicleEnd"><?= $this->getHtml('End'); ?></label>
                                <input type="datetime-local" id="iVehicleEnd" name="ownership_end" value="<?= $vehicle->getAttribute('ownership_end')->value->getValue()?->format('Y-m-d\TH:i'); ?>">
                            </div>

                            <div class="form-group">
                                <label for="iVehiclePrice"><?= $this->getHtml('PurchasePrice'); ?></label>
                                <input type="text" id="iVehiclePrice" name="purchase_price" value="<?= $this->printHtml($vehicle->getAttribute('purchase_price')->value->getValue()); ?>">
                            </div>

                            <div class="form-group">
                                <label for="iVehiclePrice"><?= $this->getHtml('LeasingFee'); ?></label>
                                <input type="text" id="iVehiclePrice" name="leasing_fee" value="<?= $this->printHtml($vehicle->getAttribute('leasing_fee')->value->getValue()); ?>">
                            </div>
                        </div>
                    </section>
                </div>

                <div class="md-hidden col-md-6">
                    <section class="portlet">
                        <div class="portlet-body">
                            <img width="100%" src="<?= $vehicleImage->id === 0
                                ? 'Web/Backend/img/logo_grey.png'
                                : UriFactory::build($vehicleImage->getPath()); ?>"></a>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>