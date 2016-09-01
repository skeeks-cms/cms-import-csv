<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 28.08.2015
 */
use yii\helpers\Html;
use skeeks\cms\modules\admin\widgets\form\ActiveFormUseTab as ActiveForm;

/* @var $this yii\web\View */
/* @var $model \skeeks\cms\importCsv\models\ImportTaskCsv */
/* @var $handler \skeeks\cms\importCsv\handlers\CsvHandler */
?>



<?php $form = ActiveForm::begin([
    'id'                                            => 'sx-import-csv-form',
    'enableAjaxValidation'                          => false,
]); ?>

<? $this->registerJs(<<<JS

(function(sx, $, _)
{
    sx.classes.CsvImport = sx.classes.Component.extend({

        _onDomReady: function()
        {
            var self = this;

            $("[data-form-reload=true]").on('change', function()
            {
                self.update();
            });

            $("#importtaskcsv-file_path").on('change', function()
            {
                self.update();
            });

        },

        update: function()
        {
            _.delay(function()
            {
                var jForm = $("#sx-import-csv-form");
                jForm.append($('<input>', {'type': 'hidden', 'name' : 'sx-not-submit', 'value': 'true'}));
                jForm.submit();
                /*var newForm = jForm.clone();
                newForm.appendTo('body');*/
                //$("[name=_csrf]", newForm).remove();
                //newForm.hide();
                //newForm.submit();
            }, 200);
        }
    });

    sx.CsvImport = new sx.classes.CsvImport();
})(sx, sx.$, sx._);


JS
); ?>

    <?= \skeeks\cms\modules\admin\widgets\BlockTitleWidget::widget(['content' => 'Базовые настройки']); ?>

    <?= $form->field($model, 'file_path')->widget(
        \skeeks\cms\modules\admin\widgets\formInputs\OneImage::className()
    ); ?>

    <?= $form->field($model, 'component')->listBox(array_merge(['' => ' - '], \yii\helpers\ArrayHelper::map(
        \Yii::$app->importCsv->handlers, 'id', 'name'
    )), [
    'size' => 1,
    'data-form-reload' => 'true'
]); ?>


<? if ($handler && $model->isFileExist) : ?>

    <?= \skeeks\cms\modules\admin\widgets\BlockTitleWidget::widget(['content' => 'Настройки импорта']); ?>
    <?= $handler->renderConfigForm($form); ?>



    <?= \skeeks\cms\modules\admin\widgets\BlockTitleWidget::widget(['content' => 'Сохранение задания']); ?>

    <?= $form->field($model, 'name'); ?>
    <?= $form->field($model, 'description')->textarea(['rows' => 5]); ?>
<? endif; ?>

<? if (!$model->isFileExist && $model->file_path) : ?>
    <? \yii\bootstrap\Alert::begin([
        'options' => [
            'class' => 'alert-danger'
        ]
    ]); ?>
        <?= \Yii::t('skeeks/importCsv', 'A csv file path is set incorrectly or the file does not exist in the specified path'); ?>
    <? \yii\bootstrap\Alert::end(); ?>
<? endif; ?>


<?= $form->buttonsStandart($model, ['save', 'close']); ?>
<hr />
<?= \skeeks\cms\importCsv\widgets\ImportCsvWidget::widget([
    'activeForm' => $form
]); ?>
<br /><br />

<?php ActiveForm::end(); ?>
