<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (�����)
 * @date 30.08.2016
 */
namespace skeeks\cms\importCsv\models;

use skeeks\cms\importCsv\handlers\CsvHandler;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%import_task_csv}}".
 *
 * @property integer $id
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $file_path
 * @property string $name
 * @property string $description
 * @property string $component
 * @property string $component_settings
 *
 * @property CsvHandler $handler
 */
class ImportTaskCsv extends \skeeks\cms\models\Core
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%import_task_csv}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['file_path'], 'required'],
            [['component'], 'required'],
            [['description', 'component_settings'], 'string'],
            [['file_path', 'name', 'component'], 'string', 'max' => 255],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => Yii::t('skeeks/importCsv', 'ID'),
            'file_path' => Yii::t('skeeks/importCsv', 'The path to the import file'),
            'name' => Yii::t('skeeks/importCsv', 'Name'),
            'description' => Yii::t('skeeks/importCsv', 'description'),
            'component' => Yii::t('skeeks/importCsv', 'Component'),
            'component_settings' => Yii::t('skeeks/importCsv', 'Component Settings'),
        ]);
    }

    /**
     * @return CsvHandler
     * @throws \skeeks\cms\importCsv\InvalidParamException
     */
    public function getHandler()
    {
        if ($this->component)
        {
            try
            {
                /**
                 * @var $component Component
                 */
                $component = \Yii::$app->importCsv->getHandler($this->component);
                $component->taskModel = $this;
                $component->load($this->component_settings, "");

                return $component;
            } catch (\Exception $e)
            {
                return false;
            }

        }

        return null;
    }
}