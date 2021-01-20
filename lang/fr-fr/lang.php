<?php

return [
    'plugin' => [
        'name' => "JWTAuth",
        'description' => "Authentification par Token Web JSON.",
    ],
    'permissions' => [
        'settings' => "Gestion de JWTAuth",
    ],
    'settings' => [
        'menu_label' => "JWTAuth",
        'menu_description' => "Configuration de JWTAuth",
        'tabs' => [
            'urls' => "Options d'URL",
            'extras' => "Options d'autorisations"
        ],
        'fields' => [
            'secret' => [
                'label' => "JWT Secret",
                'comment' => "Utilisé pour générer votre token. Utilisé uniquement pour les algorithmes HS256, HS384 et HS512."
            ],
            'keys_public' => [
                'label' => "Clé Publique",
                'comment' => "Chemin ou ressource de la clé publique."
            ],
            'keys_private' => [
                'label' => "Clé Privée",
                'comment' => "Chemin ou ressource de la clé privée."
            ],
            'keys_passphrase' => [
                'label' => "Passphrase",
                'comment' => "Passphrase de la clé privée."
            ],
            'ttl' => [
                'label' => "Temps d'expiration",
                'comment' => "Spécifie la durée de validité du token (en minutes)."
            ],
            'refresh_ttl' => [
                'label' => "Refresh time to live",
                'comment' => "Specifie la durée pendant laquelle le token peut être rafraichi (en minutes)."
            ],
            'algo' => [
                'label' => "Alorithme de Hachage",
                'comment' => "Specifie l'algorithme de hachage utilisé pour signer le token."
            ],
            'required_claims' => [
                'label' => "Revendications requises",
                'comment' => "Spécifie les revendications (claims) requises qui doivent exister dans tous les tokens."
            ],
            'persistent_claims' => [
                'label' => "Revendications persistantes",
                'comment' => "Spécifie les clés de revendication (claim keys) qui doivent persister lors du rafraichissement d'un token."
            ],
            'lock_subject' => [
                'label' => "Sujet vérouillé",
                'comment' => "Définit si une revendication 'prv' est automatiquement ajoutée au token."
            ],
            'leeway' => [
                'label' => "Marge",
                'comment' => "Cette propriété donne une certaine 'marge' à l'horodatage JWT."
            ],
            'blacklist_enabled' => [
                'label' => "Blacklist activée",
                'comment' => "Afin de pouvoir invalider des tokens, vous devez activer la blacklist."
            ],
            'blacklist_grace_period' => [
                'label' => "Période de grâce de la Blacklist",
                'comment' => "Définit une période de grâce en secondes, afin d'empécher les échecs de requêtes parallèles."
            ],
            'decrypt_cookies' => [
                'label' => "Chiffrement des cookies",
                'comment' => "A désactiver si vous voulez déchiffrer les cookies."
            ],
            'help_urls' => [
                'title' => "A LIRE D'ABORD !",
                'content' => "
                    <p>Il y a deux façons de configurer ces URLs qui dépendent de la structure de votre application.</p>

                    <p><strong>Domaine identique : </strong>Dans ce cas, vous devez juste indiquer l'URI, 
                    et le système considèrera que l'URL de base est la même que celle de votre 
                    installation OctoberCMS.<p>

                    <p><strong>Domaine Externe : </strong>Si votre OctoberCMS et votre application front-end 
                    sont hébergées sur differents domaines vous devez spécifier l'URL complète.</p>

                    <p>Il est aussi important de noter que les deux URLs doivent avoir le paramètre  
                    <i>{code}</i> qui sera automatiquement remplacé par le <i>code d'activation</i> ou 
                    le <i>code de réinitialisation</i>.</p>
                "
            ],
            'activation_url' => [
                'label' => "Activation du compte",
                'comment' => ""
            ],
            'reset_password_url' => [
                'label' => "Réinitialisation du Password",
                'comment' => ""
            ]
        ]
    ]
];
