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

$model->load(\Yii::$app->request->get());
$handler = $model->handler;
if ($handler)
{
    $handler->load(\Yii::$app->request->get());
}
?>

<? $this->registerJs(<<<JS

(function(sx, $, _)
{
    sx.classes.CsvImport = sx.classes.Component.extend({

        _init: function()
        {

        },

        _onDomReady: function()
        {
            var self = this;

            $("#importtaskcsv-file_path").on('change', function()
            {
                self.update();
                return false;
            });
            $("#importtaskcsv-component").on('change', function()
            {
                self.update();
                return false;
            });
        },

        update: function()
        {
            _.delay(function()
            {
                var jForm = $("#sx-import-csv-form");
                var newForm = jForm.clone();
                newForm.appendTo('body');
                newForm.attr('method', 'get');
                $("[name=_csrf]", newForm).remove();
                newForm.hide();
                console.log(jForm.serialize());
                console.log(newForm.serialize());
                //newForm.submit();
            }, 200);
        }
    });

    sx.CsvImport = new sx.classes.CsvImport();
})(sx, sx.$, sx._);


JS
); ?>

<?php $form = ActiveForm::begin([
    'id' => 'sx-import-csv-form',
]); ?>

    <?= $form->fieldSet("Общие настройки"); ?>

    <?= $form->field($model, 'file_path')->widget(
        \skeeks\cms\modules\admin\widgets\formInputs\OneImage::className()
    ); ?>

    <?= $form->field($model, 'component')->listBox(\yii\helpers\ArrayHelper::map(
        \Yii::$app->importCsv->handlers, 'id', 'name'
    ), ['size' => 1]); ?>

    <?= $form->field($model, 'name'); ?>
    <?= $form->field($model, 'description')->textarea(['rows' => 5]); ?>

<?= $form->fieldSetEnd(); ?>
<? if ($handler && $model->file_path) : ?>
    <?= $form->fieldSet("Настройки компонента"); ?>
        <?= $handler->renderConfigForm($form); ?>
    <?= $form->fieldSetEnd(); ?>
<? endif; ?>

<?= $form->buttonsCreateOrUpdate($model); ?>
<?php ActiveForm::end(); ?>
