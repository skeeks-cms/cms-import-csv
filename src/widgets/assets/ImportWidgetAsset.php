<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 31.08.2016
 */
namespace skeeks\cms\importCsv\widgets\assets;
use yii\web\AssetBundle;

/**
 * Class ImportWidgetAsset
 *
 * @package skeeks\cms\importCsv\assets
 */
class ImportWidgetAsset extends AssetBundle
{
    public $sourcePath = '@skeeks/cms/importCsv/widgets/assets/import';

    public $js = [
        'import.js'
    ];

    public $css = [
        'import.css'
    ];

    public $depends = [
        '\skeeks\cms\assets\JsTaskManagerAsset'
    ];


}
