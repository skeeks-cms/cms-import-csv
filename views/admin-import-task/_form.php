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

<?= $form->field($model, 'file_path')->widget(
    \skeeks\cms\modules\admin\widgets\formInputs\OneImage::className()
); ?>

<?= $form->fieldSelect($model, 'component', \yii\helpers\ArrayHelper::map(
    \Yii::$app->importCsv->handlers, 'id', 'name'
)); ?>




<?= $form->buttonsCreateOrUpdate($model); ?>
<?php ActiveForm::end(); ?>
