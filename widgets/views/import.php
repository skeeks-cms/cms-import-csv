<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 30.08.2016
 */
/* @var $this yii\web\View */
/* @var $widget \skeeks\cms\importCsv\widgets\ImportCsvWidget */

$widget = $this->context;
$this->registerCss(<<<CSS
.sx-matching-widget
{
    overflow: auto;
}
CSS
);
?>
<?= \yii\helpers\Html::beginTag('div', $widget->options); ?>
    <? if ($widget->showButton) : ?>
        <div style="text-align: center">
            <?= \yii\helpers\Html::button('Запустить импорт', $widget->buttonOptions); ?>
        </div>
    <? endif; ?>

    <div style="text-align: center" id="sx-rows"></div>
    <div class="sx-progress-tasks" id="sx-progress-tasks" style="display: none;">
        <span style="vertical-align:middle;"><h3>Процесс импорта: <span class="sx-executing-ptc"></span>%</h3></span>
        <span style="vertical-align:middle;"><span class="sx-executing-task-name"></span></span>
        <div>
            <div class="progress progress-striped active">
                <div class="progress-bar progress-bar-success"></div>
            </div>
        </div>
    </div>

<?= \yii\helpers\Html::endTag('div'); ?>

<?
$jsImport = \yii\helpers\Json::encode($widget->clientOptions);

$this->registerJs(<<<JS
sx.CsvImport = new sx.classes.csv.Import({$jsImport});
JS
);
?>
