<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 30.08.2016
 */
namespace skeeks\cms\importCsv\widgets;

use skeeks\cms\importCsvContent\CsvContentHandler;
use yii\helpers\Html;
use yii\widgets\InputWidget;

/**
 * @property CsvContentHandler $model
 *
 * Class MatchingInput
 *
 * @package skeeks\cms\import\widgets
 */
class MatchingInput extends InputWidget
{
    public $columns = [];

    public function init()
    {
        if (!$this->model)
        {
            throw new \InvalidArgumentException;
        }

        parent::init();

        Html::addCssClass($this->options, 'sx-matching-widget');
        Html::removeCssClass($this->options, ['form-control']);
    }

    public function run()
    {
        try
        {
            echo $this->render('matching');

        } catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }

}