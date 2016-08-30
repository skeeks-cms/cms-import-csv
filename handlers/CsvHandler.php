<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 29.08.2016
 */
namespace skeeks\cms\importCsv\handlers;

use skeeks\cms\importCsv\models\ImportTaskCsv;
use yii\base\Model;

/**
 * Class CsvHandler
 *
 * @package skeeks\cms\importCsv\handlers
 */
abstract class CsvHandler extends Model
{
    const CSV_TYPE_FIXED    = 'fixed';          //фиксированная ширина полей
    const CSV_TYPE_DELIMETR = 'delimetr';       //с разделителями - поля разделяются специальным символом

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $csv_type = self::CSV_TYPE_DELIMETR; //R|F

    /**
     * @var string
     */
    public $name = 'Name';

    /**
     * @var ImportTaskCsv
     */
    public $taskModel = null;

    static public function getCsvTypes()
    {
        return [
            self::CSV_TYPE_DELIMETR => 'с разделителями - поля разделяются специальным символом',
            self::CSV_TYPE_FIXED    => 'фиксированная ширина полей',
        ];
    }


    /**
     * @return bool|string
     */
    public function getRootImportFilePath()
    {
        return \Yii::getAlias('@frontend/web' . $this->importFilePath);
    }

    public function rules()
    {
        return [
            ['csv_type' , 'required'],
            ['csv_type' , 'string'],
            ['csv_type' , 'default', 'value' => self::CSV_TYPE_DELIMETR],
        ];
    }

    public function attributeLabels()
    {
        return [
            'csv_type'          => \Yii::t('skeeks/importCsvContent', 'CSV type'),
        ];
    }

    /**
     * @return array
     */
    public function getCsvColumns()
    {
        $handle = fopen($this->rootImportFilePath, "r");

        while (($data = fgetcsv($handle, 0, ";")) !== FALSE)
        {
            return $data;
        }

        return [];
    }
}
