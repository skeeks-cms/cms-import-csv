<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 29.08.2016
 */
namespace skeeks\cms\importCsv\handlers;

use skeeks\cms\base\ConfigFormInterface;
use skeeks\cms\importCsv\models\ImportTaskCsv;
use yii\base\Model;
use yii\widgets\ActiveForm;

/**
 * @property string $csvDelimetr
 * @property array $csvColumns
 *
 * Class CsvHandler
 *
 * @package skeeks\cms\importCsv\handlers
 */
abstract class CsvHandler extends Model implements ConfigFormInterface
{
    const CSV_TYPE_FIXED    = 'fixed';          //фиксированная ширина полей
    const CSV_TYPE_DELIMETR = 'delimetr';       //с разделителями - поля разделяются специальным символом

    const CSV_DELIMETR_TZP = 'TZP';                 //точка с запятой
    const CSV_DELIMETR_ZPT = 'ZPT';                 //запятая
    const CSV_DELIMETR_TAB = 'TAB';                 //табуляция
    const CSV_DELIMETR_SPS = 'SPS';                 //пробел
    const CSV_DELIMETR_OTHER = 'OTHER';             //другой

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $csv_type = self::CSV_TYPE_DELIMETR;

    /**
     * @var string
     */
    public $csv_delimetr_type = self::CSV_DELIMETR_TZP;

    /**
     * @var string
     */
    public $csv_delimetr_other = "";


    /**
     * @var string
     */
    public $name = 'Name';

    /**
     * @var string
     */
    public $description = '';

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
     * @return array
     */
    static public function getCsvDelimetrTypes()
    {
        return [
            self::CSV_DELIMETR_TZP          => 'точка с запятой',
            self::CSV_DELIMETR_ZPT          => 'запятая',
            self::CSV_DELIMETR_TAB          => 'табуляция',
            self::CSV_DELIMETR_SPS          => 'пробел',
            self::CSV_DELIMETR_OTHER        => 'другой',
        ];
    }

    /**
     * @return string
     */
    public function getCsvDelimetr()
    {
        $delimetr = ';';
        if ($this->csv_delimetr_type == static::CSV_DELIMETR_TZP)
        {
            $delimetr = ';';
        } else if ($this->csv_delimetr_type == static::CSV_DELIMETR_ZPT)
        {
            $delimetr = ',';
        } else if ($this->csv_delimetr_type == static::CSV_DELIMETR_TAB)
        {
            $delimetr = '     ';
        } else if ($this->csv_delimetr_type == static::CSV_DELIMETR_SPS)
        {
            $delimetr = ' ';
        } else if ($this->csv_delimetr_type == static::CSV_DELIMETR_OTHER)
        {
            $delimetr = $this->csv_delimetr_other;
        }

        return $delimetr;
    }

    public function rules()
    {
        return [
            ['csv_type' , 'required'],
            ['csv_type' , 'string'],
            ['csv_type' , 'default', 'value' => self::CSV_TYPE_DELIMETR],

            ['csv_delimetr_type' , 'default', 'value' => self::CSV_DELIMETR_TZP],
            ['csv_delimetr_type' , 'required'],
            ['csv_delimetr_type' , 'string'],
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
        $handle = fopen($this->taskModel->rootFilePath, "r");

        while (($data = fgetcsv($handle, 0, $this->csvDelimetr)) !== FALSE)
        {
            return $data;
        }

        return [];
    }
    /**
     * @return array
     */
    public function getCsvColumnsData($startRow = 0, $endRow = 10)
    {
        $result = [];

        $handle = fopen($this->taskModel->rootFilePath, "r");

        $counter = 0;

        while (($data = fgetcsv($handle, 0, $this->csvDelimetr)) !== FALSE)
        {
            if ($counter >= $startRow && $counter <= $endRow)
            {
                $result[] = $data;
            }

            if ($counter > $endRow)
            {
                break;
            }

            $counter ++;
        }

        return $result;
    }

    /**
     * @param ActiveForm $form
     */
    public function renderConfigForm(ActiveForm $form)
    {
        echo $form->field($this, 'csv_type')->radioList(static::getCsvTypes());
        echo $form->field($this, 'csv_delimetr_type')->radioList(static::getCsvDelimetrTypes());
    }
}
