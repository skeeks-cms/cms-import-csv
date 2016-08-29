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
        "label"     => \Yii::t('skeeks/importCsvContent', "Export / Import"),
        "img"       => ['\skeeks\cms\importCsvContent\assets\ImportCsvContentAsset', 'icons/export.png'],
        'priority'  => 400,

        'items' =>
        [
            [
                "label"     => \Yii::t('skeeks/importCsvContent', "Import"),
                "img"       => ['\skeeks\cms\importCsvContent\assets\ImportCsvContentAsset', 'icons/export.png'],

                'items' =>
                [
                    [
                        "label"     => \Yii::t('skeeks/importCsvContent', "Import CSV content items"),
                        "img"       => ['\skeeks\cms\importCsvContent\assets\ImportCsvContentAsset', 'icons/csv.png'],
                        "url"   => ["importCsvContent/admin-import"],
                    ],
                ],
            ],
        ]
    ]
];