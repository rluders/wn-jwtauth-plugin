<?php

return [
    'plugin' => [
        'name' => 'JWTAuth',
        'description' => 'Autentizace JSON Web Token.',
    ],
    'permissions' => [
        'settings' => 'Nastavení JWTAuth',
    ],
    'settings' => [
        'menu_label' => 'JWTAuth',
        'menu_description' => 'Nastavení JWTAuth',
        'tabs' => [
            'urls' => 'Nastavení URL',
            'extras' => 'Nastavení autorizace'
        ],
        'fields' => [
            'secret' => [
                'label' => 'JWT Secret',
                'comment' => "Je použit pro generování vašeho tokenu. Používá se jen pro algoritmy HS256, HS384 a HS512."
            ],
            'keys_public' => [
                'label' => 'Veřejný klíč',
                'comment' => 'Cesta nebo resource k veřejnému klíči.'
            ],
            'keys_private' => [
                'label' => 'Privátní klíč',
                'comment' => 'Cesta nebo resource k privátnímu klíči.'
            ],
            'keys_passphrase' => [
                'label' => 'Heslo',
                'comment' => 'Heslo k privátnímu klíči.'
            ],
            'ttl' => [
                'label' => 'Time to live',
                'comment' => 'Zadejte dobu (v minutách) po kterou bude token aktivní.'
            ],
            'refresh_ttl' => [
                'label' => 'Obnovení time to live',
                'comment' => 'Zadejte dobu (v minutách) po kterou bude možné token obnovit.'
            ],
            'algo' => [
                'label' => 'Hash algoritmus',
                'comment' => 'Vyberte algoritmus, který bude použit pro podepsání tokenu.'
            ],
            'required_claims' => [
                'label' => 'Povinné Claimy',
                'comment' => 'Zadejte, které claimy musí existovat v každém tokenu.'
            ],
            'persistent_claims' => [
                'label' => 'Persistentní Claimy',
                'comment' => 'Zadejte které claimy přežíjí obnovení tokenu.'
            ],
            'lock_subject' => [
                'label' => 'Zamknout subject',
                'comment' => 'Toto určuje, má-li být k tokenu automaticky přidán claim `prv`.'
            ],
            'leeway' => [
                'label' => 'Leeway',
                'comment' => 'Toto přidá k jwt timestamp claimům určitý "leeway".'
            ],
            'blacklist_enabled' => [
                'label' => 'Povolit Blacklist',
                'comment' => 'Abyste mohli tokeny invalidovat, musíte zapnout blacklist.'
            ],
            'blacklist_grace_period' => [
                'label' => 'Grace Period Blacklistu',
                'comment' => 'Nastavuje grace period (v sekundách), aby se zabránilo selhání paralelních requestů.'
            ],
            'decrypt_cookies' => [
                'label' => 'Zašifrovat cookies',
                'comment' => 'Vypněte, pokud chcete dekryptovat cookies.'
            ],
            'help_urls' => [
                'title' => 'DŮLEŽITÉ!',
                'content' => "
                    <p>Existují dva způsoby, jak nastavit tyto URL. A to, který použít, zaleží na struktuře vaší aplikace.</p>

                    <p><strong>Same domain</strong>: V tomto případě musíte nastavit URI 
                    a systém bude automaticky překpokládat základní URL stejnou s 
                    instalací OctoberCMS.<p>

                    <p><strong>External domain</strong>: Pokud jsou OctoberCMS a front-endová aplikace 
                    hostovány na různých doménách, musíte uvést kompletní URL.</p>

                    <p>Dále je důležité, že obě URL musí mít parametr 
                    <i>{code}</i>, který bude automaticky nahrazen za <i>aktivační kód</i> nebo 
                    <i>resetovací kód</i>.</p>
                "
            ],
            'activation_url' => [
                'label' => 'Aktivace účtu',
                'comment' => ''
            ],
            'reset_password_url' => [
                'label' => 'Reset hesla',
                'comment' => ''
            ]
        ]
    ]
];
