<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 15.04.2016
 */
namespace skeeks\cms\importCsv\controllers;
use skeeks\cms\backend\BackendController;
use skeeks\cms\helpers\RequestResponse;
use skeeks\cms\import\models\ImportTask;
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
class AdminImportTaskController extends BackendController
{
    public function init()
    {
        $this->permissionName = 'cmsImport/admin-import-task';
        $this->generateAccessActions = false;
        return parent::init();
    }

    public function actionLoadTask()
    {
        $rr = new RequestResponse();

        $model = new ImportTask();
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
        } else
        {
            $rr->success = false;
            $rr->message = 'Компонент не настроен';
            return $rr;
        }

        $model->validate();
        $handler->validate();

        if (!$model->errors && !$handler->errors && $handler->beforeExecute())
        {
            $rr->success = true;

            $rr->data = [
                'step'          => (int) $handler->step,
                'total'         => (int) $handler->csvTotalRows,
                'totalTask'     => (int) $handler->totalTask,
                'totalSteps'    => (int) $handler->totalSteps,
                'start'         => (int) $handler->startRow,
                'end'           => (int) $handler->endRow,
            ];

        } else
        {
            $rr->success = false;
            $rr->message = 'Проверьте правильность указанных данных';
        }

        return $rr;
    }

    public function actionImportStep()
    {
        $rr = new RequestResponse();

        $start  = \Yii::$app->request->post('start');
        $end    = \Yii::$app->request->post('end');

        $taskData = [];
        parse_str(\Yii::$app->request->post('task'), $taskData);

        $model = new ImportTask();
        $model->loadDefaultValues();
        $model->load($taskData);

        $handler = $model->handler;
        $handler->load($taskData);

        $model->validate();
        $handler->validate();

        if (!$model->errors && !$handler->errors)
        {
            $rows = $handler->getCsvColumnsData($start, $end);
            $results = [];
            $totalSuccess = 0;
            $totalErrors = 0;

            foreach ($rows as $number => $data)
            {
                $result = $handler->import($number, $data);
                if ($result->success)
                {
                    $totalSuccess++;
                } else
                {
                    $totalErrors++;
                }
                $results[$number] = $result;
            }

            $rr->success    = true;

            $rr->data       = [
                'rows'          => $results,
                'totalSuccess'  => $totalSuccess,
                'totalErrors'   => $totalErrors,
            ];

            $rr->message    = 'Задание выполнено';
        } else
        {
            $rr->success = false;
            $rr->message = 'Проверьте правильность указанных данных';
        }


        return $rr;
    }
}
