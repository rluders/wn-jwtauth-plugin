<?php

return [
    'plugin' => [
        'name' => 'JWTAuth',
        'description' => 'Autenticazione con JSON Web Token.',
    ],
    'permissions' => [
        'settings' => 'Impostazioni di JWTAuth',
    ],
    'settings' => [
        'menu_label' => 'JWTAuth',
        'menu_description' => 'Configura JWTAuth',
        'tabs' => [
            'urls' => 'Impostazioni di URL',
            'extras' => 'Impostazioni di Autenticazione'
        ],
        'fields' => [
            'secret' => [
                'label' => 'JWT Segreto',
                'comment' => 'È utilizzato per generare il tuo token. È usato solo per gli algoritmi HS256, HS384 & HS512.'
            ],
            'keys_public' => [
                'label' => 'Chiave Pubblica',
                'comment' => 'Un percorso o una risorsa per la tua chiave pubblica.'
            ],
            'keys_private' => [
                'label' => 'Chiave Privata',
                'comment' => 'Un percorso o una risorsa per la tua chiave privata.'
            ],
            'keys_passphrase' => [
                'label' => "Frase d'Ordine",
                'comment' => "La frase chiave per la tua chiave privata."
            ],
            'ttl' => [
                'label' => 'Tempo di Vita',
                'comment' => 'Specifica per quanto tempo (in minuti) sarà valido il token.'
            ],
            'refresh_ttl' => [
                'label' => 'Aggiornamento del Tempo di Vita',
                'comment' => 'Specifica per quanto tempo (in minuti) potrà essere aggiornato il tempo di vita.'
            ],
            'algo' => [
                'label' => 'Algoritmo di Hashing',
                'comment' => "Specifica l'algoritmo di hashing che sarà utilizzato per firmare il token."
            ],
            'required_claims' => [
                'label' => 'Reclami Necessari',
                'comment' => 'Specifica i reclami necessari che devono esistere in ogni token.'
            ],
            'persistent_claims' => [
                'label' => 'Reclami Persistenti',
                'comment' => "Specifica le chiavi di reclamo che devono persistere durante l'aggiornamento di un token."
            ],
            'lock_subject' => [
                'label' => 'Blocca Argomento',
                'comment' => 'Questo determinerà se un reclamo `prv` verrà automaticamente aggiunto al token.'
            ],
            'leeway' => [
                'label' => 'Margine',
                'comment' => 'Questa proprietà fornisce al jwt timestamp un "margine di manovra".'
            ],
            'blacklist_enabled' => [
                'label' => 'Attiva la Blacklist',
                'comment' => 'Per invalidare i token, è necessario attivare la blacklist.'
            ],
            'blacklist_grace_period' => [
                'label' => 'Periodo di Grazia della Blacklist',
                'comment' => 'Imposta il periodo di tolleranza in secondi per evitare il fallimento della richiesta parallela.'
            ],
            'decrypt_cookies' => [
                'label' => 'Cripta i cookie',
                'comment' => 'Disattivalo se vuoi decrittografare i cookie.'
            ],
            'help_urls' => [
                'title' => 'LEGGERE PER PRIMO!',
                'content' => "
                    <p>Ci sono due modalità per configurare questi URLs, e dipendono dalla struttura dell'applicazione.</p>

                    <p><strong>Stesso dominio</strong>: In questo caso devi solo informare l'URI, 
                    e il sistema considererà che l'URL di base è lo stesso della 
                    installazione OctoberCMS.<p>

                    <p><strong>External domain</strong>: Se il tuo OctoberCMS e la tua applicazione front-end 
                    sono ospitati in due domini diversi allora dovrai specificare l'intero URL.</p>

                    <p>È inoltre importante ricordare che entrambi gli URL devono avere il parametro 
                    <i>{code}</i> che verrà sostituito automaticamente per il <i>codice di attivazione</i> o
                    il <i>codice di reset</i>.</p>
                "
            ],
            'activation_url' => [
                'label' => 'Attiva Account',
                'comment' => ''
            ],
            'reset_password_url' => [
                'label' => 'Reimposta Password',
                'comment' => ''
            ]
        ]
    ]
];
