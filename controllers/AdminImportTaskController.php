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
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * Class AdminImportController
 *
 * @package skeeks\cms\importCsv\controllers
 */
class AdminImportTaskController extends AdminModelEditorController
{
    public $notSubmitParam = 'sx-not-submit';

    public function init()
    {
        $this->name                 = \Yii::t('skeeks/importCsv', 'Tasks on imports');
        $this->modelShowAttribute   = "id";
        $this->modelClassName       = ImportTaskCsv::className();
    }

    public function actions()
    {
        return ArrayHelper::merge(parent::actions(),
        [
            'create' =>
            [
                'callback'         => [$this, 'create'],
            ],

            'update' =>
            [
                'callback'         => [$this, 'update'],
            ],
        ]);
    }


    public function create()
    {
        $rr = new RequestResponse();

        $model = new ImportTaskCsv();
        $model->loadDefaultValues();

        if ($post = \Yii::$app->request->post())
        {
            $model->load($post);
        }

        $handler = $model->handler;
        if ($handler)
        {
            if ($post = \Yii::$app->request->post())
            {
                $handler->load($post);
            }
        }

        if ($rr->isRequestPjaxPost())
        {
            if (!\Yii::$app->request->post($this->notSubmitParam))
            {
                $model->component_settings = $handler->toArray();
                if ($model->load(\Yii::$app->request->post()) && $handler->load(\Yii::$app->request->post())
                    && $model->validate() && $handler->validate())
                {
                    $model->save();

                    \Yii::$app->getSession()->setFlash('success', \Yii::t('app','Saved'));

                    return $this->redirect(
                        $this->indexUrl
                    );

                } else
                {
                    \Yii::$app->getSession()->setFlash('error', \Yii::t('app','Could not save'));
                }
            }
        }

        return $this->render('_form', [
            'model'     => $model,
            'handler'   => $handler,
        ]);
    }


    public function update()
    {
        $rr = new RequestResponse();

        $model = $this->model;

        if ($post = \Yii::$app->request->post())
        {
            $model->load($post);
        }

        $handler = $model->handler;
        if ($handler)
        {
            if ($post = \Yii::$app->request->post())
            {
                $handler->load($post);
            }
        }

        if ($rr->isRequestPjaxPost())
        {
            if (!\Yii::$app->request->post($this->notSubmitParam))
            {
                if ($rr->isRequestPjaxPost())
                {
                    $model->component_settings = $handler->toArray();

                    if ($model->load(\Yii::$app->request->post()) && $handler->load(\Yii::$app->request->post())
                        && $model->validate() && $handler->validate())
                    {
                        $model->save();

                        \Yii::$app->getSession()->setFlash('success', \Yii::t('app','Saved'));

                        if (\Yii::$app->request->post('submit-btn') == 'apply')
                        {

                        } else
                        {
                            return $this->redirect(
                                $this->indexUrl
                            );
                        }

                        $model->refresh();

                    }
                }
            }
        }

        return $this->render('_form', [
            'model'     => $model,
            'handler'   => $handler,
        ]);
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
