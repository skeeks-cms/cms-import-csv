<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 15.04.2016
 */
namespace skeeks\cms\importCsvContent\controllers;
use skeeks\cms\helpers\RequestResponse;
use skeeks\cms\importCsvContent\models\ImportTaskModel;
use skeeks\cms\modules\admin\controllers\AdminController;

/**
 * Class AdminSearchPhraseController
 * @package skeeks\cms\controllers
 */
class AdminImportController extends AdminController
{
    public function actionIndex()
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
     */
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
     */
    public function actionImportElements()
    {
        $rr = new RequestResponse();
        $model = new ImportTaskModel();
        if (\Yii::$app->request->isAjax && \Yii::$app->request->post())
        {
            /*$model->importFilePath = \Yii::$app->request->post('importfilepath');
            $model->importProducts(\Yii::$app->request->post('rowStart'), \Yii::$app->request->post('rowEnd'));*/

            $rr->success = true;
            return $rr;
        }
    }
}
