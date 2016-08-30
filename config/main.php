<?php
return [

    'components' =>
    [
        'importCsv' => [
            'class'     => 'skeeks\cms\importCsv\ImportCsvComponent',
        ],

        'i18n' => [
            'translations' =>
            [
                'skeeks/importCsv' => [
                    'class'             => 'yii\i18n\PhpMessageSource',
                    'basePath'          => '@skeeks/cms/importCsvContent/messages',
                    'fileMap' => [
                        'skeeks/importCsv' => 'main.php',
                    ],
                ]
            ]
        ]
    ],

    'modules' =>
    [
        'importCsv' => [
            'class'         => 'skeeks\cms\importCsvContent\ImportCsvModule',
        ]
    ]
];