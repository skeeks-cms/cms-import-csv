<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 15.04.2016
 */
namespace skeeks\cms\importCsv\controllers;
use skeeks\cms\helpers\RequestResponse;
use skeeks\cms\importCsv\models\ImportTaskCsv;
use skeeks\cms\importCsvContent\models\ImportTaskModel;
use skeeks\cms\modules\admin\actions\modelEditor\AdminModelEditorAction;
use skeeks\cms\modules\admin\controllers\AdminController;
use skeeks\cms\modules\admin\controllers\AdminModelEditorController;

/**
 * Class AdminImportController
 *
 * @package skeeks\cms\importCsv\controllers
 */
class AdminImportTaskController extends AdminModelEditorController
{
    public function init()
    {
        $this->name                 = \Yii::t('skeeks/importCsv', 'Import');
        $this->modelShowAttribute   = "id";
        $this->modelClassName       = ImportTaskCsv::className();
    }

    /*public function actionIndex()
    {
        $rr         = new RequestResponse();
        $model      = new ImportTaskModel();

        $model->load(\Yii::$app->request->post());

        return $this->render($this->action->id, [
            'model' => $model
        ]);
    }

    /**
     * @return array
    public function actionValidate()
    {
        $rr = new RequestResponse();
        $model = new ImportTaskModel();
        if (\Yii::$app->request->isAjax && \Yii::$app->request->post())
        {
            $model->load(\Yii::$app->request->post());
            return $rr->ajaxValidateForm($model);
        }
    }

    /**
     * @return RequestResponse
    public function actionImportElements()
    {
        $rr = new RequestResponse();
        $model = new ImportTaskModel();
        if (\Yii::$app->request->isAjax && \Yii::$app->request->post())
        {
            /*$model->importFilePath = \Yii::$app->request->post('importfilepath');
            $model->importProducts(\Yii::$app->request->post('rowStart'), \Yii::$app->request->post('rowEnd'))

            $rr->success = true;
            return $rr;
        }
    }*/
}
