<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 15.04.2016
 */
return
[
    'exportImport' =>
    [
        "label"     => \Yii::t('skeeks/importCsv', "Export / Import"),
        "img"       => ['\skeeks\cms\importCsv\assets\ImportCsvAsset', 'icons/export.png'],
        'priority'  => 400,

        'items' =>
        [
            [
                "label"     => \Yii::t('skeeks/importCsv', "Import"),
                "img"       => ['\skeeks\cms\importCsv\assets\ImportCsvAsset', 'icons/export.png'],

                'items' =>
                [
                    [
                        "label"     => \Yii::t('skeeks/importCsv', "Import CSV"),
                        "img"       => ['\skeeks\cms\importCsv\assets\ImportCsvAsset', 'icons/csv.png'],
                        "url"       => ["importCsv/admin-import-task"],
                    ],
                ],
            ],
        ]
    ]
];