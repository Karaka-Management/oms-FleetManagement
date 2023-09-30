<?php
/**
 * Jingga
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

use Modules\FleetManagement\Models\DriverStatus;
use Modules\FleetManagement\Models\NullDriver;
use Modules\Media\Models\NullMedia;
use phpOMS\Uri\UriFactory;

$countryCodes  = \phpOMS\Localization\ISO3166TwoEnum::getConstants();
$countries     = \phpOMS\Localization\ISO3166NameEnum::getConstants();
$driverStatus  = DriverStatus::getConstants();

/**
 * @var \Modules\FleetManagement\Models\Driver $driver
 */
$driver        = $this->data['driver'] ?? new NullDriver();
$files         = $driver->files;
$driverImage   = $this->data['driverImage'] ?? new NullMedia();
$attributeView = $this->data['attributeView'];

/**
 * @var \phpOMS\Views\View $this
 */
echo $this->data['nav']->render();
?>
<div class="tabview tab-2">
    <div class="box">
        <ul class="tab-links">
            <li><label for="c-tab-1"><?= $this->getHtml('Profile'); ?></label>
            <li><label for="c-tab-2"><?= $this->getHtml('Attributes'); ?></label>
            <li><label for="c-tab-3"><?= $this->getHtml('Files'); ?></label>
            <li><label for="c-tab-4"><?= $this->getHtml('Notes'); ?></label>
            <li><label for="c-tab-5"><?= $this->getHtml('Inspections'); ?></label>
            <li><label for="c-tab-7"><?= $this->getHtml('Milage'); ?></label>
            <li><label for="c-tab-8"><?= $this->getHtml('Costs'); ?></label>
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
                                <label for="iFleetDriverProfileName"><?= $this->getHtml('Name'); ?></label>
                                <input type="text" id="iFleetDriverProfileName" name="name" value="<?= $this->printHtml($driver->account->name1); ?>">
                            </div>

                            <div class="form-group">
                                <label for="iDriverStatus"><?= $this->getHtml('Status'); ?></label>
                                <select id="iDriverStatus" name="driver_status">
                                    <?php foreach ($driverStatus as $status) : ?>
                                        <option value="<?= $status; ?>"<?= $status === $driver->status ? ' selected' : ''; ?>><?= $this->getHtml(':status' . $status); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="iDriverMake"><?= $this->getHtml('Make'); ?></label>
                                <input type="text" id="iDriverMake" name="make" value="<?= $this->printHtml($driver->getAttribute('maker')->value->getValue()); ?>">
                            </div>
                        </div>
                        <div class="portlet-foot">
                            <?php if ($driver->id === 0) : ?>
                                <input id="iCreateSubmit" type="Submit" value="<?= $this->getHtml('Create', '0', '0'); ?>">
                            <?php else : ?>
                                <input id="iSaveSubmit" type="Submit" value="<?= $this->getHtml('Save', '0', '0'); ?>">
                            <?php endif; ?>
                        </div>
                    </section>
                </div>

                <div class="md-hidden col-md-6">
                    <section class="portlet">
                        <div class="portlet-body">
                            <img width="100%" src="<?= $driverImage->id === 0
                                ? 'Web/Backend/img/logo_grey.png'
                                : UriFactory::build($driverImage->getPath()); ?>"></a>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <input type="radio" id="c-tab-2" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-2' ? ' checked' : ''; ?>>
        <div class="tab">
            <div class="row">
                <?= $attributeView->render(
                    $driver->attributes,
                    $this->data['attributeTypes'] ?? [],
                    [],
                    '{/api}fleet/driver/attribute'
                    );
                ?>
            </div>
        </div>

        <input type="radio" id="c-tab-3" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-3' ? ' checked' : ''; ?>>
        <div class="tab col-simple">
            <?= $this->data['media-upload']->render('driver-file', 'files', '', $driver->files); ?>
        </div>

        <input type="radio" id="c-tab-4" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-4' ? ' checked' : ''; ?>>
        <div class="tab">
            <?= $this->data['driver-notes']->render('driver-notes', '', $driver->notes); ?>
        </div>

        <input type="radio" id="c-tab-5" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-5' ? ' checked' : ''; ?>>
        <div class="tab">
            <div class="row">
                <a class="button" href="<?= UriFactory::build('{/app}/fleet/inspection/create?driver=' . $driver->id); ?>"><?= $this->getHtml('Create', '0', '0'); ?></a>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <section class="portlet">
                        <div class="portlet-head"><?= $this->getHtml('Upcoming'); ?></div>
                        <table id="upcomingInspections" class="default">
                            <thead>
                                <tr>
                                    <td><?= $this->getHtml('Date'); ?>
                                    <td class="wf-100"><?= $this->getHtml('Type'); ?>
                                    <td><?= $this->getHtml('Responsible'); ?>
                            <tbody>
                                <tr>
                                    <td>
                                    <td>
                                    <td>
                        </table>
                    </section>
                </div>

                <div class="col-xs-12 col-md-6">
                    <section class="portlet">
                        <div class="portlet-head"><?= $this->getHtml('History'); ?></div>
                        <table id="historicInspections" class="default">
                            <thead>
                                <tr>
                                    <td><?= $this->getHtml('Date'); ?>
                                    <td class="wf-100"><?= $this->getHtml('Type'); ?>
                                    <td><?= $this->getHtml('Responsible'); ?>
                            <tbody>
                                <tr>
                                    <td>
                                    <td>
                                    <td>
                        </table>
                    </section>
                </div>
            </div>
        </div>

        <input type="radio" id="c-tab-7" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-7' ? ' checked' : ''; ?>>
        <div class="tab">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <section class="portlet">
                        <form id="milageForm" action="<?= UriFactory::build(''); ?>" method="post"
                            data-ui-container="#milageTable tbody"
                            data-add-form="milageForm"
                            data-add-tpl="#milageTable tbody .oms-add-tpl-milage">
                            <div class="portlet-head"><?= $this->getHtml('Milage'); ?></div>
                            <div class="portlet-body">
                                <div class="form-group">
                                    <label for="iAttributeId"><?= $this->getHtml('ID', '0', '0'); ?></label>
                                    <input type="text" id="iAttributeId" name="id" data-tpl-text="/id" data-tpl-value="/id" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="iDriver"><?= $this->getHtml('Driver'); ?></label>
                                    <div class="ipt-wrap">
                                        <div class="ipt-first">
                                            <span class="input">
                                                <button type="button" formaction="">
                                                    <i class="fa fa-book"></i>
                                                </button>
                                                <input type="text" id="iDriver" name="bill_client" value="">
                                            </span>
                                        </div>
                                        <?php if (0 > 0) : ?>
                                        <div class="ipt-second">
                                             <a class="button" href="<?= UriFactory::build('{/app}/sales/client/profile?id=' . 0); ?>"><?= $this->getHtml('Driver'); ?></a>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="iStartDate"><?= $this->getHtml('Start'); ?></label>
                                    <input type="datetime-local" id="iStartDate" name="bill_invoice_date"
                                        value="">
                                </div>

                                <div class="form-group">
                                    <label for="iEndDate"><?= $this->getHtml('End'); ?></label>
                                    <input type="datetime-local" id="iEndDate" name="bill_invoice_date"
                                        value="">
                                </div>

                                <div class="form-group">
                                    <label for="iFrom"><?= $this->getHtml('From'); ?></label>
                                    <input type="text" id="iFrom" name="bill_invoice_date"
                                        value="">
                                </div>

                                <div class="form-group">
                                    <label for="iTo"><?= $this->getHtml('To'); ?></label>
                                    <input type="text" id="iTo" name="bill_invoice_date"
                                        value="">
                                </div>

                                <div class="form-group">
                                    <label for="iMilage"><?= $this->getHtml('Milage'); ?></label>
                                    <input type="number" id="iMilage" name="bill_invoice_date"
                                        value="">
                                </div>

                                <div class="form-group">
                                    <label for="iMilageDescription"><?= $this->getHtml('Description'); ?></label>
                                    <pre class="textarea contenteditable" id="iMilageDescription" data-name="description" data-tpl-value="/value" contenteditable></pre>
                                </div>
                            </div>
                            <div class="portlet-foot">
                                <input id="bAttributeAdd" formmethod="put" type="submit" class="add-form" value="<?= $this->getHtml('Add', '0', '0'); ?>">
                                <input id="bAttributeSave" formmethod="post" type="submit" class="save-form hidden button save" value="<?= $this->getHtml('Update', '0', '0'); ?>">
                                <input type="submit" class="cancel-form hidden button close" value="<?= $this->getHtml('Cancel', '0', '0'); ?>">
                            </div>
                        </form>
                    </section>
                </div>

                <div class="col-xs-12 col-md-6">
                    <section class="portlet">
                        <div class="portlet-head"><?= $this->getHtml('Milage'); ?><i class="lni lni-download download btn end-xs"></i></div>
                        <div class="slider">
                        <table id="milageTable" class="default"
                            data-tag="form"
                            data-ui-element="tr"
                            data-add-tpl=".oms-add-tpl-milage"
                            data-update-form="milageForm">
                            <thead>
                                <tr>
                                    <td>
                                    <td><?= $this->getHtml('ID', '0', '0'); ?>
                                    <td class="wf-100"><?= $this->getHtml('Driver'); ?><i class="sort-asc fa fa-chevron-up"></i><i class="sort-desc fa fa-chevron-down"></i>
                                    <td><?= $this->getHtml('Milage'); ?><i class="sort-asc fa fa-chevron-up"></i><i class="sort-desc fa fa-chevron-down"></i>
                                    <td><?= $this->getHtml('Start'); ?><i class="sort-asc fa fa-chevron-up"></i><i class="sort-desc fa fa-chevron-down"></i>
                                    <td><?= $this->getHtml('End'); ?><i class="sort-asc fa fa-chevron-up"></i><i class="sort-desc fa fa-chevron-down"></i>
                            <tbody>
                                <template class="oms-add-tpl-milage">
                                    <tr data-id="" draggable="false">
                                        <td>
                                            <i class="fa fa-cogs btn update-form"></i>
                                            <input id="milageTable-remove-0" type="checkbox" class="hidden">
                                            <label for="milageTable-remove-0" class="checked-visibility-alt"><i class="fa fa-times btn form-action"></i></label>
                                            <span class="checked-visibility">
                                                <label for="milageTable-remove-0" class="link default"><?= $this->getHtml('Cancel', '0', '0'); ?></label>
                                                <label for="milageTable-remove-0" class="remove-form link cancel"><?= $this->getHtml('Delete', '0', '0'); ?></label>
                                            </span>
                                        <td data-tpl-text="/id" data-tpl-value="/id"></td>
                                        <td data-tpl-text="/type" data-tpl-value="/type" data-value=""></td>
                                        <td data-tpl-text="/value" data-tpl-value="/value"></td>
                                        <td data-tpl-text="/unit" data-tpl-value="/unit"></td>
                                        <td data-tpl-text="/unit" data-tpl-value="/unit"></td>
                                    </tr>
                                </template>
                                <?php $c = 0;
                                $milage  = [];
                                foreach ($milage as $key => $value) : ++$c; ?>
                                    <tr data-id="<?= $value->id; ?>">
                                        <td>
                                            <i class="fa fa-cogs btn update-form"></i>
                                            <?php if (!$value->type->isRequired) : ?>
                                            <input id="milageTable-remove-<?= $value->id; ?>" type="checkbox" class="hidden">
                                            <label for="milageTable-remove-<?= $value->id; ?>" class="checked-visibility-alt"><i class="fa fa-times btn form-action"></i></label>
                                            <span class="checked-visibility">
                                                <label for="milageTable-remove-<?= $value->id; ?>" class="link default"><?= $this->getHtml('Cancel', '0', '0'); ?></label>
                                                <label for="milageTable-remove-<?= $value->id; ?>" class="remove-form link cancel"><?= $this->getHtml('Delete', '0', '0'); ?></label>
                                            </span>
                                            <?php endif; ?>
                                        <td data-tpl-text="/id" data-tpl-value="/id"><?= $value->id; ?>
                                        <td data-tpl-text="/type" data-tpl-value="/type" data-value="<?= $value->type->id; ?>"><?= $this->printHtml($value->type->getL11n()); ?>
                                        <td data-tpl-text="/value" data-tpl-value="/value"><?= $value->value->getValue() instanceof \DateTime ? $value->value->getValue()->format('Y-m-d') : $this->printHtml((string) $value->value->getValue()); ?>
                                        <td data-tpl-text="/unit" data-tpl-value="/unit" data-value="<?= $value->value->unit; ?>"><?= $this->printHtml($value->value->unit); ?>
                                        <td data-tpl-text="/unit" data-tpl-value="/unit" data-value="<?= $value->value->unit; ?>"><?= $this->printHtml($value->value->unit); ?>
                                <?php endforeach; ?>
                                <?php if ($c === 0) : ?>
                                <tr>
                                    <td colspan="6" class="empty"><?= $this->getHtml('Empty', '0', '0'); ?>
                                <?php endif; ?>
                        </table>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <input type="radio" id="c-tab-8" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-8' ? ' checked' : ''; ?>>
        <div class="tab">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                </div>
            </div>
        </div>
    </div>
</div>