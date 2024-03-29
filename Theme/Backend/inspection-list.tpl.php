<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\ItemManagement
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

echo $this->data['nav']->render();
?>

<div class="row">
    <div class="col-xs-12">
        <section class="portlet">
            <div class="portlet-head"><?= $this->getHtml('Upcoming'); ?></div>
            <table id="upcomingInspections" class="default sticky">
                <thead>
                    <tr>
                        <td><?= $this->getHtml('Date'); ?>
                        <td class="wf-100"><?= $this->getHtml('Type'); ?>
                        <td><?= $this->getHtml('Responsible'); ?>
                <tbody>
                <?php
                $count = 0;
                foreach (($this->data['inspections'] ?? []) as $inspection) :
                    // @todo handle old inspections in the past? maybe use a status?!
                    if ($inspection->next === null) {
                        continue;
                    }

                    ++$count;
                ?>
                    <tr>
                        <td><?= $inspection->next->format('Y-m-d H:i'); ?>
                        <td><?= $this->printHtml($inspection->type->getL11n()); ?>
                        <td>
                <?php endforeach; ?>
                <?php if ($count === 0) : ?>
                <tr><td colspan="3" class="empty"><?= $this->getHtml('Empty', '0', '0'); ?>
                <?php endif; ?>
            </table>
        </section>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <section class="portlet">
            <div class="portlet-head"><?= $this->getHtml('History'); ?></div>
            <table id="historicInspections" class="default sticky">
                <thead>
                    <tr>
                        <td><?= $this->getHtml('Date'); ?>
                        <td class="wf-100"><?= $this->getHtml('Type'); ?>
                        <td><?= $this->getHtml('Responsible'); ?>
                <tbody>
                <?php
                $count = 0;
                foreach (($this->data['inspections'] ?? []) as $inspection) :
                    ++$count;
                ?>
                    <tr>
                        <td><?= $inspection->date->format('Y-m-d H:i'); ?>
                        <td><?= $this->printHtml($inspection->type->getL11n()); ?>
                        <td>
                <?php endforeach; ?>
                <?php if ($count === 0) : ?>
                <tr><td colspan="3" class="empty"><?= $this->getHtml('Empty', '0', '0'); ?>
                <?php endif; ?>
            </table>
        </section>
    </div>
</div>
