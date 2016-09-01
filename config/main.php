<?php
return [

    'components' =>
    [
        'i18n' => [
            'translations' =>
            [
                'skeeks/importCsv' => [
                    'class'             => 'yii\i18n\PhpMessageSource',
                    'basePath'          => '@skeeks/cms/importCsv/messages',
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
            'class'         => 'skeeks\cms\importCsv\ImportCsvModule',
        ]
    ]
];