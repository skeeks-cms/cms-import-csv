<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 08.03.2016
 */
/* @var $this yii\web\View */
/* @var $model \skeeks\cms\importCsvContent\models\ImportTaskModel */
use \skeeks\cms\modules\admin\widgets\form\ActiveFormUseTab as ActiveForm;
?>
<? $form = ActiveForm::begin([
    'id' => 'sx-form',
    'usePjax' => false,
    'useAjaxSubmit' => false,
    'enableAjaxValidation' => true,
    'validationUrl' => \skeeks\cms\helpers\UrlHelper::construct(['/importCsvContent/admin-import/validate'])->enableAdmin()->toString(),
]); ?>

    <? $this->registerJs(<<<JS
$("#importtaskmodel-importfilepath").on('change', function()
{
    $("#sx-form").submit();
    return false;
});
JS
); ?>

    <?= $form->fieldSet('Основное'); ?>
        <?= $form->field($model, 'importFilePath')->widget(
            \skeeks\cms\modules\admin\widgets\formInputs\OneImage::className()
        ); ?>
        <?= $form->field($model, 'cms_content_id')->listBox(\skeeks\cms\models\CmsContent::getDataForSelect(), ['size' => 1]); ?>
    <?= $form->fieldSetEnd();?>

    <? if ($model->importFilePath) : ?>

        <?= $form->fieldSet('Настройки'); ?>
            <?= $form->field($model, 'csv_type')->radioList(\skeeks\cms\importCsvContent\models\ImportTaskModel::getCsvTypes()); ?>
        <?= $form->fieldSetEnd();?>

        <?= $form->fieldSet('Поля'); ?>
            <? if ($model->getCsvColumns()) : ?>
                <? foreach ($model->getCsvColumns() as $key => $name) : ?>
                    <p>Поле <?= $key; ?> (<?= $name; ?>)</p>
                <? endforeach; ?>
            <? endif; ?>
        <?= $form->fieldSetEnd();?>
    <? endif; ?>

    <?/*= $form->buttonsStandart($model);*/?>
<? ActiveForm::end(); ?>
<br />
<br />
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

<?
\skeeks\cms\assets\JsTaskManagerAsset::register($this);

$jsImport = \yii\helpers\Json::encode([
    'backend' => \skeeks\cms\helpers\UrlHelper::construct(['/admin-import-stock-sale/import-product'])->enableAdmin()->toString()
]);

$this->registerJs(<<<JS
(function(sx, $, _)
{
    sx.classes.ImportProgressBar = sx.classes.tasks.ProgressBar.extend({

        _init: function()
        {
            var self = this;
            this.applyParentMethod(sx.classes.tasks.ProgressBar, '_init', []);

            this.bind('update', function(e, data)
            {
                $(".sx-executing-task-name", self.getWrapper()).empty().append(data.Task.get('name'));
                $(".sx-executing-ptc", self.getWrapper()).empty().append(self.getExecutedPtc());
            });
        }

    });

    sx.classes.Import = sx.classes.Component.extend({

        _init: function()
        {
            this._initTaskManager();
        },

        _initTaskManager: function()
        {
            this.TaskManager = new sx.classes.tasks.Manager({
                'tasks' : [],
                'delayQueque' : 200
            });

            this.ProgressBar = new sx.classes.ImportProgressBar(this.TaskManager, "#sx-progress-tasks");
        },

        loadTasks: function(countRows)
        {
            var stepRange    = 20;
            var steps = countRows / stepRange;
            steps = steps.toFixed();
            steps = Number(steps) + 1;

            var tasks = [];

            var from    = 0;

            for (var step = 0; step < steps; step ++)
            {

                from = stepRange * step;


                var ajaxQuery = sx.ajax.preparePostQuery(this.get('backend'), {
                    'rowStart': from,
                    'rowEnd': from + stepRange,
                    'importfilepath': $('#importstocksalemodel-importfilepath').val()
                });

                var Task = new sx.classes.tasks.AjaxTask(ajaxQuery, {
                    'name': 'Строка: ' + from + ' - ' + (from + stepRange)
                });

                tasks.push(Task);
            }

            this.TaskManager.setTasks(tasks);
        }

    });

    sx.Import = new sx.classes.Import({$jsImport});

})(sx, sx.$, sx._);
JS
);
?>
