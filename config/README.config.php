<?php return [
    'namespace' => 'IocInterop\\Interface\\',
    'directory' => dirname(__DIR__) . '/src',
    'template' => dirname(__DIR__) . '/resources/README.tpl.md',
    'interfaces' => [
        'IocContainer',
        'IocContainerFactory',
        'IocThrowable',
        'IocTypeAliases',
    ],
];
