<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
           // 'site/addfriend/<id:\d+>' => 'site/addfriend',
          //  'site/removefriend/<id:\d+>' => 'site/removefriend',
            'site/deleteuser/<id:\d+>' => 'site/deleteuser',
            'site/addfriend/<fid:\d+>/<uid:\d+>' => 'site/addfriend',
            'site/removefriend/<fid:\d+>/<uid:\d+>' => 'site/removefriend',
            'site/privateoffice/<id:\d+>' => 'site/privateoffice', 
           ],
        ],
        
    ],
    'params' => $params,
];
