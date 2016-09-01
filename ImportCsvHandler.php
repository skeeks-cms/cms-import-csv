
<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 29.08.2016
 */
namespace skeeks\cms\importCsv;

use skeeks\cms\base\ConfigFormInterface;
use skeeks\cms\import\ImportHandler;
use skeeks\cms\importCsv\models\ImportTaskCsv;
use skeeks\cms\modules\admin\widgets\formInputs\OneImage;
use yii\base\Model;
use yii\widgets\ActiveForm;

/**
 * @property string $csvDelimetr
 * @property array $csvColumns
 * @property int $csvTotalRows
 * @property int $startRow
 * @property int $endRow
 * @property int $totalTask
 * @property int $totalSteps
 *
 * Class CsvHandler
 *
 * @package skeeks\cms\importCsv\handlers
 */
abstract class ImportCsvHandler extends ImportHandler
{
    const CSV_TYPE_FIXED    = 'fixed';          //фиксированная ширина полей
    const CSV_TYPE_DELIMETR = 'delimetr';       //с разделителями - поля разделяются специальным символом

    const CSV_DELIMETR_TZP = 'TZP';                 //точка с запятой
    const CSV_DELIMETR_ZPT = 'ZPT';                 //запятая
    const CSV_DELIMETR_TAB = 'TAB';                 //табуляция
    const CSV_DELIMETR_SPS = 'SPS';                 //пробел
    const CSV_DELIMETR_OTHER = 'OTHER';             //другой

    const CSV_CHARSET_UTF8           = 'UTF-8';             //другой
    const CSV_CHARSET_WINDOWS1251    = 'windows-1251';             //другой

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
    public $csv_source_charset = self::CSV_CHARSET_UTF8;

    /**
     * @var string
     */
    public $file_path = '';

    /**
     * @var string
     */
    public $csv_delimetr_other = ";";

    public $csv_start_row   = 1;
    public $csv_end_row     = '';
    public $step            = 20;

    /**
     * Соответствие полей
     * @var array
     */
    public $matching = [];

    public function getAvailableFields()
    {
        return ['' => ' - '];
    }


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

    /**
     * Доступные кодировки
     * @return array
     */
    static public function getCsvCharsets()
    {
        return [
            self::CSV_CHARSET_UTF8 => self::CSV_CHARSET_UTF8,
            self::CSV_CHARSET_WINDOWS1251    => self::CSV_CHARSET_WINDOWS1251,
        ];
    }

    /**
     * Типы
     * @return array
     */
    static public function getCsvTypes()
    {
        return [
            self::CSV_TYPE_DELIMETR => 'С разделителями - поля разделяются специальным символом',
            self::CSV_TYPE_FIXED    => 'Фиксированная ширина полей',
        ];
    }

    /**
     * @return array
     */
    static public function getCsvDelimetrTypes()
    {
        return [
            self::CSV_DELIMETR_TZP          => 'Точка с запятой',
            self::CSV_DELIMETR_ZPT          => 'Запятая',
            self::CSV_DELIMETR_TAB          => 'Табуляция',
            self::CSV_DELIMETR_SPS          => 'Пробел',
            self::CSV_DELIMETR_OTHER        => 'Другой',
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
            ['file_path' , 'required'],
            ['file_path' , 'string'],

            ['csv_type' , 'required'],
            ['csv_type' , 'string'],
            ['csv_type' , 'default', 'value' => self::CSV_TYPE_DELIMETR],

            ['csv_delimetr_type' , 'default', 'value' => self::CSV_DELIMETR_TZP],
            ['csv_delimetr_type' , 'required'],
            ['csv_delimetr_type' , 'string'],

            ['csv_source_charset' , 'string'],

            ['csv_start_row' , 'integer'],
            ['csv_end_row' , 'integer'],

            ['step' , 'integer'],
            ['step' , 'required'],

            ['matching' , 'safe'],

            ['csv_delimetr_other' , 'string'],
            ['csv_delimetr_other' , 'required', 'when' => function(self $model)
            {
                return $model->csv_delimetr_type == self::CSV_DELIMETR_OTHER;
            }],
        ];
    }

    public function attributeLabels()
    {
        return [
            'csv_type'                      => \Yii::t('skeeks/importCsv', 'CSV type'),
            'csv_delimetr_type'             => \Yii::t('skeeks/importCsv', 'CSV type separator'),
            'csv_delimetr_other'            => \Yii::t('skeeks/importCsv', 'Another separator'),
            'csv_source_charset'            => \Yii::t('skeeks/importCsv', 'Encoding the source file'),
            'matching'                      => \Yii::t('skeeks/importCsv', 'Preview content and configuration compliance'),
            'csv_start_row'                 => \Yii::t('skeeks/importCsv', 'Start import from line'),
            'csv_end_row'                   => \Yii::t('skeeks/importCsv', 'Finish the import on the line'),
            'step'                          => \Yii::t('skeeks/importCsv', 'Import step'),
            'file_path'                     => \Yii::t('skeeks/importCsv', 'The path to the file import CSV'),
        ];
    }

    /**
     * @return array
     */
    public function getCsvColumnsData($startRow = 0, $endRow = 10)
    {
        $result = [];

        $handle = fopen($this->taskModel->rootFilePath, "r");

        $counter = 0;
        $realCounter = $startRow;

        while (($data = fgetcsv($handle, 0, $this->csvDelimetr)) !== FALSE)
        {
            if ($counter >= $startRow && $counter <= $endRow)
            {
                if (\Yii::$app->charset != $this->csv_source_charset)
                {
                    $encodedData = [];
                    foreach ($data as $row)
                    {
                        $row = iconv($this->csv_source_charset, \Yii::$app->charset, $row);
                        $encodedData[] = $row;
                    }

                    $data = $encodedData;
                }

                $result[$realCounter] = $data;
                $realCounter ++;
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
     * @return int
     */
    public function getCsvTotalRows()
    {
        $counter = 0;

        $handle = fopen($this->taskModel->rootFilePath, "r");

        while (($data = fgetcsv($handle, 0, $this->csvDelimetr)) !== FALSE)
        {
            $counter++;
        }

        return $counter;
    }

    /**
     * @return int
     */
    public function getStartRow()
    {
        if ($this->csv_start_row)
        {
            return (int) $this->csv_start_row;
        }

        return 1;
    }

    /**
     * @return int
     */
    public function getEndRow()
    {
        if ($this->csv_end_row)
        {
            return (int) $this->csv_end_row;
        }

        return $this->csvTotalRows;
    }

    /**
     * @return int
     */
    public function getTotalTask()
    {
        return $this->endRow - $this->startRow;
    }

    /**
     * @return float
     */
    public function getTotalSteps()
    {
        return (int) round($this->totalTask / (int) $this->step) + 1;
    }

    /**
     * @param ActiveForm $form
     */
    public function renderConfigForm(ActiveForm $form)
    {
        echo $form->field($this, 'file_path')->widget([
            OneImage::className()
        ]);

        echo $form->field($this, 'csv_type')->label(false)->radioList(static::getCsvTypes(), ['data-form-reload' => 'true']);

        if ($this->csv_type == static::CSV_TYPE_DELIMETR)
        {
            echo $form->field($this, 'csv_delimetr_type')->label(false)->radioList(static::getCsvDelimetrTypes(), ['data-form-reload' => 'true']);
        }

        echo $form->field($this, 'csv_source_charset')->listBox(static::getCsvCharsets(), ['data-form-reload' => 'true', 'size' => 1]);

        if ($this->csv_delimetr_type == static::CSV_DELIMETR_OTHER)
        {
            echo $form->field($this, 'csv_delimetr_other')->textInput(['size' => 5]);
        }

        echo "<div class='row'><div class='col-md-3'>";
            echo $form->field($this, 'csv_start_row');
        echo "</div><div class='col-md-3'>";
            echo $form->field($this, 'csv_end_row');
        echo "</div><div class='col-md-3'>";
            echo $form->field($this, 'step');
        echo "</div></div>";
    }

    /**
     * @param ActiveForm $form
     */
    public function renderWidget(ActiveForm $form)
    {
        echo 'Not found widget';
    }
}