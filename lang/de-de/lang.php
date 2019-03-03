<?php

return [
    'plugin' => [
        'name' => 'JWTAuth',
        'description' => 'JSON Web Token Authentifizierung.',
    ],
    'permissions' => [
        'settings' => 'JWTAuth verwalten',
    ],
    'settings' => [
        'menu_label' => 'JWTAuth',
        'menu_description' => 'JWTAuth konfigurieren',
        'tabs' => [
            'urls' => 'URL Einstellungen',
            'extras' => 'Autorisierungseinstellungen'
        ],
        'fields' => [
            'secret' => [
                'label' => 'JWT Secret',
                'comment' => "Es wird verwendet um Ihren Token zu erstellen. Wird nur für die HS256, HS384 & HS512 Algorithmen verwendet."
            ],
            'keys_public' => [
                'label' => 'Öffentlicher Schlüssel',
                'comment' => 'Pfad oder Ressource zu Ihrem öffentlichen Schlüssel.'
            ],
            'keys_private' => [
                'label' => 'Privater Schlüssel',
                'comment' => 'Pfad oder Ressource zu Ihrem privaten Schlüssel.'
            ],
            'keys_passphrase' => [
                'label' => 'Passwort',
                'comment' => 'Passwort für den privaten Schlüssel.'
            ],
            'ttl' => [
                'label' => 'Lebenszeit (TTL)',
                'comment' => 'Spezifizieren Sie die Lebensdauer des Token (Gültigkeit in Minuten).'
            ],
            'refresh_ttl' => [
                'label' => 'Lebenszeit Aktualisierung (Refresh TTL)',
                'comment' => 'Spezifizieren Sie die Zeit (in Minuten) in der der Token aktualisiert werden kann.'
            ],
            'algo' => [
                'label' => 'Hash Algorithmus',
                'comment' => 'Spezifizieren Sie den Hash Algorithmus, der verwendet wird um den Token zu signieren.'
            ],
            'required_claims' => [
                'label' => 'Benötigte Claims',
                'comment' => 'Spezifizieren Sie den benötigten Claim, der in jedem Token enthalten sein muss.'
            ],
            'persistent_claims' => [
                'label' => 'Persistente Claims',
                'comment' => 'Spezifizieren Sie die Claim Schlüssel, der bestehen bleiben, wenn der Token aktualisiert wird.'
            ],
            'lock_subject' => [
                'label' => 'Betreff sperren',
                'comment' => 'Diese Einstellung hat zur Folge, ob ein `prv` Claim automatisch zum Token hinzugefügt wird.'
            ],
            'leeway' => [
                'label' => 'Spielraum',
                'comment' => 'Diese Eigenschaft gibt den jwt timestamp Claims etwas "Spielraum".'
            ],
            'blacklist_enabled' => [
                'label' => 'Blacklist aktivieren',
                'comment' => 'Um Token ungültig erklären zu können muss die Blacklist aktiviert sein.'
            ],
            'blacklist_grace_period' => [
                'label' => 'Blacklist Frist',
                'comment' => 'Spezifizieren Sie die Frist (in Sekunden) um Fehler mit parallelen Anfragen zu vermeiden.'
            ],
            'decrypt_cookies' => [
                'label' => 'Verschlüsseln der Cookies',
                'comment' => 'Schalten Sie es ab, wenn sie unverschlüsselte Cookies wünschen.'
            ],
            'help_urls' => [
                'title' => 'ZUERST LESEN!',
                'content' => "
                    <p>Abhängig von Ihrer Applikationsstruktur gibt es zwei Möglichkeiten diese URLs zu konfigurieren.</p>

                    <p><strong>Selbe Domain</strong>: In diesem Fall müssen Sie nur die URI informaieren, 
                    und das System berücksichtigt, dass die Base URL die selbe wie die  
                    OctoberCMS Installation.<p>

                    <p><strong>Externe Domain</strong>: Wenn Ihre OctoberCMS - und ihre Front End Applikation 
                    in zwei verschiedenen Domains gehostet wird, muss die volle URL angegeben werden.</p>

                    <p>Ebenfalls ist es wichtig, dass beide URLs den Parameter 
                    <i>{code}</i> haben, der in Folge durch den <i>activation code</i> oder 
                    durch den <i>reset code</i> automatisch ersetzt wird.</p>
                "
            ],
            'activation_url' => [
                'label' => 'Kontoaktivierung',
                'comment' => ''
            ],
            'reset_password_url' => [
                'label' => 'Passwortzurücksetzung',
                'comment' => ''
            ]
        ]
    ]
];
