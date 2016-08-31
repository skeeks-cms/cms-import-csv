<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 30.08.2016
 */
namespace skeeks\cms\importCsv\widgets;

use skeeks\cms\importCsv\models\ImportTaskCsv;
use skeeks\cms\importCsv\widgets\assets\ImportWidgetAsset;
use skeeks\cms\importCsvContent\CsvContentHandler;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\InputWidget;

/**
 * @property CsvContentHandler $model
 *
 * Class MatchingInput
 *
 * @package skeeks\cms\import\widgets
 */
class ImportCsvWidget extends Widget
{
    public $clientOptions = [];
    public $options = [];

    public $showButton = true;
    public $buttonOptions = [];

    /**
     * @var ImportTaskCsv
     */
    public $modelTask = null;

    public function init()
    {
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }

        $this->clientOptions = ArrayHelper::merge([
            'backend' => \skeeks\cms\helpers\UrlHelper::construct(['/importCsv/admin-import-task/import'])->enableAdmin()->toString(),
            'id' => $this->options['id'],
            'task' => ArrayHelper::merge($this->modelTask->toArray(), [
                'totalRows' => $this->modelTask->handler->csvTotalRows
            ])
        ], $this->clientOptions);


        Html::addCssClass($this->options, 'sx-import-widget');
        Html::addCssClass($this->buttonOptions, 'sx-start-btn btn btn-primary btn-lg');

        parent::init();

    }

    public function run()
    {
        try
        {
            ImportWidgetAsset::register($this->view);

            echo $this->render('import');

        } catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }

}