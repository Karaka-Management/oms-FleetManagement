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

use phpOMS\Uri\UriFactory;

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
                        <td><?= $this->getHtml('Type'); ?>
                        <td class="wf-100"><?= $this->getHtml('Reference'); ?>
                <tbody>
                <?php
                $count = 0;
                foreach (($this->data['inspections'] ?? []) as $i) :
                    // @todo handle old inspections in the past? maybe use a status?!
                    if ($i['inspection']->next === null) {
                        continue;
                    }

                    ++$count;
                    $inspection = $i['inspection'];

                    $url = UriFactory::build('{/base}/fleet/inspection/' . $i['type'] . '/view?id=' . $inspection->id);
                ?>
                    <tr data-href="<?= $url; ?>">
                        <td><a href="<?= $url; ?>"><?= $inspection->next?->format('Y-m-d H:i'); ?></a>
                        <td><a href="<?= $url; ?>"><?= $this->printHtml($inspection->type->getL11n()); ?></a>
                        <td><a href="<?= $url; ?>"><?= $i['type'] === 'vehicle'
                            ? $this->data['vehicles'][$inspection->reference]->name
                            : $this->data['drivers'][$inspection->reference]->account->name1 . ' ' . $this->data['drivers'][$inspection->reference]->account->name2;
                        ?></a>
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
                        <td><?= $this->getHtml('Type'); ?>
                        <td class="wf-100"><?= $this->getHtml('Reference'); ?>
                <tbody>
                <?php
                $count = 0;
                foreach (($this->data['inspections'] ?? []) as $i) :
                    ++$count;
                    $inspection = $i['inspection'];

                    $url = UriFactory::build('{/base}/fleet/inspection/' . $i['type'] . '/view?id=' . $inspection->id);
                ?>
                    <tr data-href="<?= $url; ?>">
                        <td><a href="<?= $url; ?>"><?= $inspection->date?->format('Y-m-d H:i'); ?></a>
                        <td><a href="<?= $url; ?>"><?= $this->printHtml($inspection->type->getL11n()); ?></a>
                        <td><a href="<?= $url; ?>"><?= $i['type'] === 'vehicle'
                            ? $this->data['vehicles'][$inspection->reference]->name
                            : $this->data['drivers'][$inspection->reference]->account->name1 . ' ' . $this->data['drivers'][$inspection->reference]->account->name2;
                        ?></a>
                <?php endforeach; ?>
                <?php if ($count === 0) : ?>
                <tr><td colspan="3" class="empty"><?= $this->getHtml('Empty', '0', '0'); ?>
                <?php endif; ?>
            </table>
        </section>
    </div>
</div>
