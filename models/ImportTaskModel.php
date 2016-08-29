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
 * Class ImportTaskModel
 *
 * @package skeeks\cms\importCsvContent\models
 */
class ImportTaskModel extends Model
{
    public $importFilePath = null;

    public function rules()
    {
        return [
            ['importFilePath' , 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'importFilePath' => 'Файл импорта товаров'
        ];
    }
}