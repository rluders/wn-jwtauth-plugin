<?php

return [
    'plugin' => [
        'name' => 'JWTAuth',
        'description' => 'Autenticação com JSON Web Token.',
    ],
    'permissions' => [
        'settings' => 'Gerenciar JWTAuth',
    ],
    'settings' => [
        'menu_label' => 'JWTAuth',
        'menu_description' => 'Configurar o JWTAuth',
        'tabs' => [
            'urls' => 'Definições de URL',
            'extras' => 'Definições de Autenticação'
        ],
        'fields' => [
            'secret' => [
                'label' => 'JWT Segredo',
                'comment' => "It's used to generate your token. Used only for HS256, HS384 & HS512 algorithms."
            ],
            'keys_public' => [
                'label' => 'Chave pública',
                'comment' => 'Caminho ou recurso para a sua chave pública.'
            ],
            'keys_private' => [
                'label' => 'Chave privada key',
                'comment' => 'Caminho ou percurso para a sua chave privada.'
            ],
            'keys_passphrase' => [
                'label' => 'Frase-senha',
                'comment' => 'A frase-senha (passphrase) da chave privada.'
            ],
            'ttl' => [
                'label' => 'Tempo de vida',
                'comment' => 'Especifique o tempo (em minutos) em o token será válido.'
            ],
            'refresh_ttl' => [
                'label' => 'Tempo de vida para ser atualizado',
                'comment' => 'Especifique o tempo (em minutos) em o token poderá ser atualizado.'
            ],
            'algo' => [
                'label' => 'Algorítimo de Hashing',
                'comment' => 'Selecione o algorítimo de hashing que será utilizado para gerar o token.'
            ],
            'required_claims' => [
                'label' => 'Requisitos obrigatórios',
                'comment' => 'Especifique os requisitos que têm de existir em todos os tokens.'
            ],
            'persistent_claims' => [
                'label' => 'Requisitos persistentes',
                'comment' => 'Especifique os requisitos para as chaves a serem persistidas quando o token atualiza.'
            ],
            'lock_subject' => [
                'label' => 'Trancar sujeito',
                'comment' => 'Determina se o requisito `prv` será automaticamente adicionado ao token.'
            ],
            'leeway' => [
                'label' => 'Leeway',
                'comment' => 'Propriedade dá alguma "margem" ao timestamp do jwt.'
            ],
            'blacklist_enabled' => [
                'label' => 'Ativar lista negra',
                'comment' => 'Para invalidar tokens, é preciso ter a lista negra ativa.'
            ],
            'blacklist_grace_period' => [
                'label' => 'Período de carência da lista negra',
                'comment' => 'Defina o período de carência em segundos para evitar erros com pedidos paralelos.'
            ],
            'decrypt_cookies' => [
                'label' => 'Encriptar as cookies',
                'comment' => 'Desativar caso se pretenda cookies desencriptadas.'
            ],
            'help_urls' => [
                'title' => 'LER PRIMEIRO!',
                'content' => "
                    <p>Há duas formas de configurar estes URLs dependendo da estrutura da aplicação.</p>

                    <p><strong>Mesmo domínio</strong>: Neste caso só é preciso informar o URI
                    e o sistema irá considerar que o URL base é o mesmo que o da instalação
                    do OctoberCMS<p>

                    <p><strong>Domínio externo</strong>: Se o OctoberCMS e a aplicação de front-end
                    estão hospedados em domínios diferentes é preciso especificar o URL completo.</p>

                    <p>Também é importante lembrar que ambos os URLs têm de ter o parâmetro 
                    <i>{code}</i> que será substituído pelo <i>activation code</i> ou
                    pelo <i>reset code</i> automaticamente.</p>
                "
            ],
            'activation_url' => [
                'label' => 'Ativação da conta',
                'comment' => ''
            ],
            'reset_password_url' => [
                'label' => 'Redifinição de password',
                'comment' => ''
            ]
        ]
    ]
];
