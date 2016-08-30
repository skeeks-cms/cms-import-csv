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
/* @var $model \skeeks\hosting\models\HostingVps */
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->fieldSet("Основное"); ?>

<?= $form->fieldSelect($model, 'cms_user_id', \yii\helpers\ArrayHelper::map(
    \skeeks\cms\models\CmsUser::find()->active()->all(), 'id', 'displayName'
)); ?>

<?= $form->field($model, 'active_to')->widget(
    \kartik\datecontrol\DateControl::classname(), [
    'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
]); ?>

<? if ($model->isNewRecord) : ?>


    <?= $form->fieldSelect($model, 'hosting_server_id', \yii\helpers\ArrayHelper::map(
        \skeeks\hosting\models\HostingServer::find()->active()->all(), 'id', 'name'
    )); ?>


    <?= $form->fieldSelect($model, 'hosting_vps_template_id', \yii\helpers\ArrayHelper::map(
        \skeeks\hosting\models\HostingVpsTemplate::find()->active()->all(), 'id', function(\skeeks\hosting\models\HostingVpsTemplate $hostingVpsTemplate)
        {
            return $hostingVpsTemplate->name . " ({$hostingVpsTemplate->path})";
        }
    )); ?>


    <?= $form->fieldSelect($model, 'hosting_vps_tariff_id', \yii\helpers\ArrayHelper::map(
        \skeeks\hosting\models\HostingVpsTariff::find()->active()->all(), 'id', 'name'
    )); ?>

<? else : ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'price')->textInput(); ?>

        </div>
        <div class="col-md-4">
            <?= $form->fieldSelect($model, 'currency_code', \yii\helpers\ArrayHelper::map(\skeeks\modules\cms\money\models\Currency::find()->active()->all(), 'code', 'code'));?>
        </div>
    </div>

    <?= $form->field($model, 'max_cpus')->textInput(); ?>
    <?= $form->field($model, 'max_ftps')->textInput(); ?>
    <?= $form->field($model, 'max_sites')->textInput(); ?>
    <?= $form->field($model, 'max_memory')->textInput(); ?>
    <?= $form->field($model, 'max_disk')->textInput(); ?>
    <?= $form->field($model, 'comment')->textarea(['rows' => 20]); ?>
    <?/*= $form->field($model, 'max_diskread')->textInput(); */?><!--
    <?/*= $form->field($model, 'max_diskwrite')->textInput(); */?>
    <?/*= $form->field($model, 'max_netin')->textInput(); */?>
    --><?/*= $form->field($model, 'max_netout')->textInput(); */?>

<? endif; ?>


<?= $form->fieldSetEnd(); ?>

<?= $form->buttonsCreateOrUpdate($model); ?>
<?php ActiveForm::end(); ?>
