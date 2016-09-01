<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 29.08.2016
 */
namespace skeeks\cms\importCsv\helpers;

use yii\base\Component;

class CsvImportRowResult extends Component
{
    public $success     = true;
    public $message     = '';
    public $html        = '';

    public $data    = [];
}