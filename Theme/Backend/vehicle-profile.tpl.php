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
use Modules\Profile\Models\ContactType;
use phpOMS\Uri\UriFactory;

$countryCodes = \phpOMS\Localization\ISO3166TwoEnum::getConstants();
$countries    = \phpOMS\Localization\ISO3166NameEnum::getConstants();

/**
 * @var \Modules\FleetManagement\Models\Vehicle $vehicle
 */
$vehicle = $this->getData('vehicle') ?? new NullVehicle();
$files  = $client->getFiles();

/**
 * @var \phpOMS\Views\View $this
 */
echo $this->getData('nav')->render();
?>
<div class="tabview tab-2">
    <div class="box">
        <ul class="tab-links">
            <li><label for="c-tab-1"><?= $this->getHtml('Profile'); ?></label></li>
            <li><label for="c-tab-6"><?= $this->getHtml('Attributes'); ?></label>
            <li><label for="c-tab-3"><?= $this->getHtml('Files'); ?></label></li>
            <li><label for="c-tab-3"><?= $this->getHtml('Notes'); ?></label></li>
            <li><label for="c-tab-4"><?= $this->getHtml('Inspections'); ?></label></li>
            <li><label for="c-tab-5"><?= $this->getHtml('Drivers'); ?></label>
            <li><label for="c-tab-6"><?= $this->getHtml('Milage'); ?></label>
        </ul>
    </div>
    <div class="tab-content">
        <input type="radio" id="c-tab-1" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-1' ? ' checked' : ''; ?>>
        <div class="tab">
            <div class="row">
                <div class="col-xs-12 col-md-8">
                    <section class="portlet">
                        <div class="form-group">
                            <label for="iFleetVehicleProfileName"><?= $this->getHtml('Name'); ?></label>
                            <input type="text" id="iFleetVehicleProfileName" name="name" value="<?= $this->printHtml($vehicle->name); ?>">
                        </div>

                        <div class="form-group">
                            <label for="iVehicleEnd"><?= $this->getHtml('Type'); ?></label>
                            <input type="text" id="iVehicleEnd" name="vehicle_type" value="<?= $this->printHtml($vehivle->getAttribute('vehicle_type')->value->getValue()); ?>">
                        </div>

                        <div class="form-group">
                            <label for="iVehicleMake"><?= $this->getHtml('make'); ?></label>
                            <input type="text" id="iVehicleMake" name="make" value="<?= $this->printHtml($vehivle->getAttribute('maker')->value->getValue()); ?>">
                        </div>

                        <div class="form-group">
                            <label for="iVehicleModel"><?= $this->getHtml('Model'); ?></label>
                            <input type="text" id="iVehicleModel" name="vehicle_model" value="<?= $this->printHtml($vehivle->getAttribute('vehicle_model')->value->getValue()); ?>">
                        </div>

                        <div class="form-group">
                            <label for="iVehicleStart"><?= $this->getHtml('Start'); ?></label>
                            <input type="text" id="iVehicleStart" name="ownership_start" value="<?= $vehivle->getAttribute('ownership_start')->value->getValue()->format('Y-m-d'); ?>">
                        </div>

                        <div class="form-group">
                            <label for="iVehicleEnd"><?= $this->getHtml('End'); ?></label>
                            <input type="text" id="iVehicleEnd" name="ownership_end" value="<?= $vehivle->getAttribute('ownership_end')->value->getValue()->format('Y-m-d'); ?>">
                        </div>

                        <div class="form-group">
                            <label for="iVehiclePrice"><?= $this->getHtml('PurchasePrice'); ?></label>
                            <input type="text" id="iVehiclePrice" name="purchase_price" value="<?= $this->printHtml($vehivle->getAttribute('purchase_price')->value->getValue()); ?>">
                        </div>

                        <div class="form-group">
                            <label for="iVehiclePrice"><?= $this->getHtml('LeasingFee'); ?></label>
                            <input type="text" id="iVehiclePrice" name="leasing_fee" value="<?= $this->printHtml($vehivle->getAttribute('leasing_fee')->value->getValue()); ?>">
                        </div>
                    </section>
                </div>

                <div class="col-xs-12 col-md-4 sm-hidden">
                    <section class="portlet">
                    </section>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <section class="portlet">
                        <!-- Notes -->
                    </section>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <section class="portlet">
                        <!-- Files/Locations/??? -->
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>