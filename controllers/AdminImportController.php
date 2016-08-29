<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 15.04.2016
 */
namespace skeeks\cms\importCsvContent\controllers;
use skeeks\cms\modules\admin\controllers\AdminController;

/**
 * Class AdminSearchPhraseController
 * @package skeeks\cms\controllers
 */
class AdminImportController extends AdminController
{
    public function actionIndex()
    {
        $rr = new RequestResponse();
        $model = new \common\models\ImportStockSaleModel();

        if (\Yii::$app->request->isAjax && \Yii::$app->request->post())
        {
            $model->load(\Yii::$app->request->post());

            $rr->success = true;
            $rr->message = "Импорт завершен успешно";




            $rr->data = [
                'countRows' => $model->countRows(),
                'resultImportTree' => $model->importTree()
            ];

            return $rr;
        }

        return $this->render($this->action->id, [
            'model' => $model
        ]);
    }
}
