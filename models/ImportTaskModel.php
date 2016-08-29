<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 08.03.2016
 */
namespace skeeks\cms\importCsvContent\models;
use yii\base\Model;

/**
 * @property string $rootImportFilePath
 *
 * Class ImportTaskModel
 *
 * @package skeeks\cms\importCsvContent\models
 */
class ImportTaskModel extends Model
{
    const CSV_TYPE_FIXED    = 'fixed'; //фиксированная ширина полей
    const CSV_TYPE_DELIMETR = 'delimetr';     //с разделителями - поля разделяются специальным символом

    public $importFilePath = null;
    public $cms_content_id = null;

    public $csv_type = self::CSV_TYPE_DELIMETR; //R|F

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
            ['importFilePath' , 'string'],
            ['importFilePath' , 'required'],

            ['cms_content_id' , 'required'],
            ['cms_content_id' , 'integer'],

            ['csv_type' , 'required'],
            ['csv_type' , 'string'],
            ['csv_type' , 'default', 'value' => self::CSV_TYPE_DELIMETR],
        ];
    }

    public function attributeLabels()
    {
        return [
            'importFilePath'    => \Yii::t('skeeks/importCsvContent', 'The path to the file import CSV'),
            'cms_content_id'    => \Yii::t('skeeks/importCsvContent', 'Content'),
            'csv_type'          => \Yii::t('skeeks/importCsvContent', 'CSV type'),
        ];
    }

    public function getCsvColumns()
    {
        $handle = fopen($this->getRootImportFilePath(), "r");

        while (($data = fgetcsv($handle, 0, ";")) !== FALSE)
        {
            return $data;
        }

        return [];
    }
}