<?php
return [

    'components' =>
    [
        'importCsvContent' => [
            'class'     => 'skeeks\cms\importCsvContent\ImportCsvContentComponent',
        ],

        'i18n' => [
            'translations' =>
            [
                'skeeks/importCsvContent' => [
                    'class'             => 'yii\i18n\PhpMessageSource',
                    'basePath'          => '@skeeks/cms/importCsvContent/messages',
                    'fileMap' => [
                        'skeeks/importCsvContent' => 'main.php',
                    ],
                ]
            ]
        ]
    ],

    'modules' =>
    [
        'importCsvContent' => [
            'class'         => 'skeeks\cms\importCsvContent\ImportCsvContentModule',
        ]
    ]
];