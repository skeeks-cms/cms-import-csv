<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 30.08.2016
 */
/* @var $this yii\web\View */
/* @var $widget \skeeks\cms\importCsv\widgets\MatchingInput */

//$dataColumns = $widget->model->getCsvColumnsData();
$widget = $this->context;
$this->registerCss(<<<CSS
.sx-matching-widget
{
    overflow: auto;
}
CSS
);
?>
<? if ($widget->model->getCsvColumnsData()) : ?>
    <? $all = $widget->model->getCsvColumnsData() ;?>
    <? $firstRow = $all[0] ;?>
<?= \yii\helpers\Html::beginTag('div', $widget->options); ?>
    <table class="table table-striped table-bordered sx-table" style="background: white;">
        <thead>
            <tr>
                <th></th>
                <? foreach($firstRow as $key => $value) : ?>
                    <th>
                        <? $name = \yii\helpers\Html::getInputName($widget->model, 'matching'); ?>
                        <? $selected = \yii\helpers\ArrayHelper::getValue((array) $widget->model->matching, $key); ?>
                        <?= \yii\helpers\Html::listBox($name . "[{$key}]", $selected, $widget->model->getAvailableFields(), ['size' => 1, 'class' => 'form-control'])?>
                    </th>
                <? endforeach; ?>
            </tr>
        </thead>
        <? foreach ($widget->model->getCsvColumnsData(0, 3) as $key => $row) : ?>
            <?
                if ($key == 0)
                {
                    $firstRow = $row;
                }
            ?>
            <tr>
                <td>
                    <b><?= $key; ?></b>
                </td>
                <? foreach($row as $value) : ?>
                    <td>
                        <?= \skeeks\cms\helpers\StringHelper::substr(
                            \Yii::$app->formatter->format($value, 'text'), 0, 150
                        ); ?>
                    </td>
                <? endforeach; ?>
            </tr>
        <? endforeach; ?>

        <? if ($widget->model->csvTotalRows > 3) : ?>
            <tr>
                <td colspan="<?= count($firstRow) + 1; ?>">
                    Всего строк в файле: <b><?= $widget->model->csvTotalRows; ?>...</b>
                </td>
            </tr>


            <? $from = $widget->model->csvTotalRows - 3; ?>
            <? foreach ($widget->model->getCsvColumnsData($from, $widget->model->csvTotalRows) as $key => $row) : ?>
                <? $from ++;?>

                <tr>
                    <td>
                        <b><?= $from; ?></b>
                    </td>
                    <? foreach($row as $value) : ?>
                        <td>
                            <?= \skeeks\cms\helpers\StringHelper::substr(
                                \Yii::$app->formatter->format($value, 'text'), 0, 150
                            ); ?>
                        </td>
                    <? endforeach; ?>
                </tr>
            <? endforeach; ?>

        <? endif; ?>

    </table>
<?= \yii\helpers\Html::endTag('div'); ?>
<? endif ;?>
