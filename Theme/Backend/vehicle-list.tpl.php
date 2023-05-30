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

use phpOMS\Uri\UriFactory;

/** @var \phpOMS\Views\View $this */
$vehicles = $this->data['vehicles'] ?? [];

echo $this->data['nav']->render(); ?>
<div class="row">
    <div class="col-xs-12">
        <section class="portlet">
            <div class="portlet-head"><?= $this->getHtml('Vehicles'); ?><i class="lni lni-download download btn end-xs"></i></div>
            <div class="slider">
            <table id="iSalesClientList" class="default sticky">
                <thead>
                <tr>
                    <td>
                    <td><?= $this->getHtml('ID', '0', '0'); ?>
                        <label for="iSalesClientList-sort-1">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-1">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="iSalesClientList-sort-2">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-2">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                    <td><?= $this->getHtml('Status'); ?>
                        <label for="iSalesClientList-sort-3">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-3">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="iSalesClientList-sort-4">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-4">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                    <td class="wf-100"><?= $this->getHtml('Name'); ?>
                        <label for="iSalesClientList-sort-5">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-5">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="iSalesClientList-sort-6">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-6">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                    <td><?= $this->getHtml('Type'); ?>
                        <label for="iSalesClientList-sort-7">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-7">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="iSalesClientList-sort-8">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-8">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                <tbody>
                <?php
                    $count = 0;
                    foreach ($vehicles as $key => $value) :
                        ++$count;
                        $url = UriFactory::build('{/base}/fleet/vehicle/profile?{?}&id=' . $value->id);
                ?>
                <tr data-href="<?= $url; ?>">
                    <td>
                    <td data-label="<?= $this->getHtml('ID', '0', '0'); ?>"><a href="<?= $url; ?>"><?= $this->printHtml((string) $value->id); ?></a>
                    <td data-label="<?= $this->getHtml('Status'); ?>"><a href="<?= $url; ?>"><?= $this->getHtml(':status' . $value->status); ?></a>
                    <td data-label="<?= $this->getHtml('Name'); ?>"><a href="<?= $url; ?>"><?= $this->printHtml($value->name); ?></a>
                    <td data-label="<?= $this->getHtml('Type'); ?>"><a href="<?= $url; ?>"><?= $this->printHtml($value->type->getL11n()); ?></a>
                <?php endforeach; ?>
                <?php if ($count === 0) : ?>
                    <tr><td colspan="8" class="empty"><?= $this->getHtml('Empty', '0', '0'); ?>
                <?php endif; ?>
            </table>
            </div>
        </section>
    </div>
</div>
