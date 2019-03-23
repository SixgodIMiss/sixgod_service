<?php

// 记得同级目录下建一个 database.php

return [
    'mysql' => [
        'default' => [
            'type' => 'mysql',
            'host' => '127.0.0.1',
            'username' => '',
            'password' => '',
            'port' => 3306,
            'dbname'   => ''
        ],
        'second' => []
    ],

    'es' => [
        'default' => 'http://usename:password@ip:port'  // 做集群的话要设一个统一IP入口
    ],

    'memcached' => [
        'default' => [
            [ 'host' => '', 'port' => 11211 ],
            [ 'host' => '', 'port' => '' ]
        ]
    ],

    'redis' => [
        'default' => [
            'host' => '',
            'port' => 6379,
            'auth' => ''
        ]
    ]
];