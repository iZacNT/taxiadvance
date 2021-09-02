<?php

return [
    'class' => 'yii\web\UrlManager',
    'hostInfo' => $params['backendHostInfo'],
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        'admin' => 'site/index',
        '<action:about|contact|signup|login>'=>'site/<action>',

        '<controller:[\w\-]+>' => '<controller>/index',
        '<controller:[\w\-]+>/<id:\d+>' => '<controller>/view',
        '<controller:[\w\-]+>/<page:\d+>' => '<controller>/view',
        '<controller:[\w\-]+>/<action:[\w-]+' => '<controller>/<action>',
        '<controller:[\w\-]+>/<id:\d+>/<action:[\w\-]+' => '<controller>/<action>',
        '<controller:[\w\-]+>/<page:\d+>/<action:[\w\-]+' => '<controller>/<action>',
    ],

];