<?php

use kartik\datecontrol\Module;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'CPbtCDZI1Bdz3ubBlaaZ3Sw98y-bBx8l',
//            'baseUrl' => ''
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
//            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
//            'loginUrl' => ['/', 'autologin' => true]
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
//            'showScriptName' => false,
//            'rules' => [
//                '' => 'site/index',
//                '<controller:\w+>/<action:\w+>/' => '<controller>/<action>',
//            ],

        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'locale' => 'pl',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'nullDisplay' => '',
        ],
    ],
    'modules' => [
        'gridview' => [
            'class' => '\kartik\grid\Module',
        ],
        'dynagrid' => [
            'class' => '\kartik\dynagrid\Module',
            // other module settings
        ],
        'datecontrol' => [
            'class' => 'kartik\datecontrol\Module',
            'displaySettings' => [
                Module::FORMAT_DATE => 'php:d.m.Y',
            ],
            'saveSettings' => [
                Module::FORMAT_DATE => 'php:Y-m-d',
            ],
            'autoWidget' => true,
            'autoWidgetSettings' => [
                Module::FORMAT_DATE => [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'todayHighlight' => true,
                        //'todayBtn' => true
                    ]
                ],
            ],
        ]
    ],
    'container' => [
        'definitions' => [
            yii\widgets\LinkPager::class => [
                'firstPageLabel' => '<<',
                'lastPageLabel' => '>>',
                'nextPageLabel' => false,
                'prevPageLabel' => false,
                'activePageCssClass' => 'active-page',
                'pageCssClass' => 'pagination',
                'maxButtonCount' => 10
            ]
        ]
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
