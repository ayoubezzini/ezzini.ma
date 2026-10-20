<?php

return [

    'role' => 'Ingénieur logiciel senior',

    'location' => 'Maroc',

    'headline' => 'Ingénieur logiciel, obsédé par les *détails*.',

    'intro' => 'Je suis Ayoub, ingénieur senior avec :years ans de PHP et de Laravel derrière moi. J’ai dirigé des équipes, livré une plateforme de paiement, et aujourd’hui je conçois, développe et fais tourner mes propres produits. Je suis le genre d’ingénieur à passer un après-midi sur un seul pixel, parce que c’est là que se joue la différence entre bien et excellent.',

    'summary' => 'Ingénieur logiciel senior avec :years ans d’expérience dans la création de produits web avec PHP, Laravel et Symfony. Ancien Lead Software Engineer chez YouCan, où j’ai piloté le développement de YouCan Pay. Fondateur et seul ingénieur de trois produits en production. Obsédé par les détails, à l’aise avec les outils d’IA, habitué à tout prendre en main, du schéma de base de données jusqu’à l’enregistrement DNS.',

    'stats' => [
        'years' => ['value' => ':years', 'label' => 'ans à livrer du logiciel'],
        'products' => ['value' => '3', 'label' => 'produits portés de bout en bout'],
        'deploy' => ['value' => '1', 'label' => 'commit pour déployer ce site'],
    ],

    'principles' => [
        'details' => [
            'title' => 'Les détails font le produit',
            'text' => 'Entre bien et excellent, il y a une pile de petites choses que personne n’a demandées : un contour de focus, un chiffre qui ne saute pas, un message d’erreur qui dit quoi faire ensuite. On les ressent même sans savoir les nommer, alors j’y consacre volontiers un après-midi.',
        ],
        'ownership' => [
            'title' => 'Ce que je livre, je l’assume',
            'text' => 'Khlass, eStock et Nuvo sont à moi, du premier croquis à l’enregistrement DNS : produit, design, code, infrastructure. Chaque fonctionnalité et chaque correctif portent mon nom, et c’est très bien ainsi.',
        ],
        'ai' => [
            'title' => 'L’IA, avec discernement',
            'text' => 'Je travaille avec Claude tous les jours, pour explorer, écrire du code et le relire. Il me rend plus rapide ; il ne décide pas à ma place. Chaque ligne est lue, questionnée et souvent réécrite. Dans Nuvo, Claude est aussi une fonctionnalité : il transforme n’importe quelle fiche produit en contenu prêt à vendre, en trois langues.',
        ],
        'shipping' => [
            'title' => 'Livrer petit, livrer souvent',
            'text' => 'Des commits courts, des vérifications automatiques, aucun déploiement manuel. Ce site en est la preuve : un push sur main lance les tests, construit l’image, génère le CV en PDF à partir de la page elle-même et le met en ligne.',
        ],
    ],

    'projects' => [
        'khlass' => [
            'tagline' => 'Facturation et conformité pour les auto-entrepreneurs marocains.',
            'summary' => 'Les freelances marocains vivent avec des limites strictes : un plafond de chiffre d’affaires annuel, un impôt forfaitaire sur chaque dirham encaissé, et une retenue à la source de 30 % dès qu’un même client dépasse 80 000 MAD dans l’année. Les outils de facturation génériques font des PDF. Khlass répond aux questions qui comptent vraiment : quelle marge reste-t-il cette année ? Quel client va franchir le seuil ? Combien d’impôt est dû ce trimestre ?',
            'image_alt' => 'La page d’accueil de Khlass',
            'built' => [
                'Devis, factures et avoirs avec une numérotation annuelle sans trou (`FA-2026-0001`), où un numéro ne peut qu’avancer.',
                'Des jauges en temps réel pour le plafond annuel et le seuil par client, calculées sur l’argent réellement encaissé et converties en MAD à la date du paiement.',
                'Un éditeur de documents A4 libre : blocs positionnés au millimètre, mentions légales déplaçables mais jamais supprimables.',
                'Des PDF au pixel près, générés par Chromium headless, avec une solution de repli automatique quand Chromium n’est pas disponible.',
                'Des abonnements par carte via YouCan Pay ou par virement validé manuellement, les deux passant par un seul et même chemin d’activation.',
                'Tout le produit, documents compris, en français et en anglais.',
            ],
            'details' => [
                'Une facture émise est immuable. Les corrections passent par un avoir lié à l’original, exactement comme la loi l’attend.',
                'Plafonds, taux et mentions légales vivent dans un seul fichier de configuration : une nouvelle loi de finances, c’est une ligne à changer, pas une chasse dans le code.',
                'Les clients qui paient depuis l’étranger via Deel, Upwork ou Payoneer ont un total annuel au lieu d’une jauge de retenue qui induirait en erreur.',
                'Le contenu légal est figé à l’émission ; l’habillage est appliqué au rendu. Changer de logo ne réécrit jamais l’historique.',
            ],
        ],
        'estock' => [
            'tagline' => 'Ventes, achats et stock pour les entreprises marocaines.',
            'summary' => 'eStock est la refonte multi-tenant d’une application de stock historique en Symfony et Vue, qu’un distributeur utilise encore chaque jour. Devis, bons de livraison, factures, achats, stock et paiements dans un seul outil qui fonctionne sur téléphone, pensé pour les règles marocaines : ICE, TVA, droit de timbre et montants en lettres.',
            'image_alt' => 'Le tableau de bord eStock avec le chiffre d’affaires, les achats, les créances et le graphique des ventes mensuelles',
            'built' => [
                'Des espaces avec des dossiers annuels, des rôles par espace et un catalogue de permissions résolu par dossier.',
                'Un grand livre de stock comme unique source de vérité : lots FIFO, coût moyen pondéré et transferts entre dépôts.',
                'Neuf types de documents partageant un seul moteur de formulaire : lignes réordonnables par glisser-déposer, sections, sous-totaux, et une remise répartie sur les taux de TVA avec le même algorithme en JavaScript et sur le PDF.',
                'Un import qui transfère la base historique d’un vrai client dans un espace neuf, numérotation et historique compris.',
                'Des tableaux de bord aux graphiques SVG rendus côté serveur, dans des couleurs vérifiées pour le daltonisme.',
                'Une page d’accueil en français, anglais et arabe (de droite à gauche), dont les captures sont générées automatiquement depuis un espace de démonstration.',
            ],
            'details' => [
                'Presque tout est annulable : un document validé revient en brouillon, ses mouvements de stock sont inversés et son numéro est conservé.',
                'Montants et quantités sont calculés en décimales à précision arbitraire, jamais en flottants.',
                'Chaque action utilisateur est enregistrée dans un journal d’audit ClickHouse à rotation automatique, lisible comme un historique en langage clair.',
                'Une recherche globale ⌘K qui applique exactement les mêmes règles de visibilité que les listes qu’elle parcourt.',
            ],
        ],
        'nuvo' => [
            'tagline' => 'Une boutique en ligne pensée pour le paiement à la livraison partout au Maroc.',
            'summary' => 'Au Maroc, la plupart des clients paient à la livraison et posent leurs questions sur WhatsApp. Nuvo est une boutique conçue autour de cette réalité : commander prend quelques secondes, rien ne se paie en ligne, et le marchand gère catalogue, commandes et stock depuis un back-office dédié.',
            'image_alt' => 'La page d’accueil de la boutique Nuvo',
            'built' => [
                'Une boutique rapide en français, anglais et arabe (de droite à gauche), avec variantes, marques, promotions et codes promo.',
                'Une commande express protégée par des limites par IP et par téléphone, et par Cloudflare Turnstile contre les fausses commandes.',
                'Import de produits avec Claude : on colle une fiche produit, on obtient un contenu prêt à vendre en trois langues avec les bonnes images, validé par un schéma JSON.',
                'Un back-office pour les commandes et l’historique de leurs statuts, les mouvements de stock, les sections de la page d’accueil, les bannières et les pages.',
                'Une chaîne de traitement d’images responsive et une notification Slack à chaque nouvelle commande.',
            ],
            'details' => [
                'La langue du visiteur est conservée même quand il suit un lien relatif depuis une bannière.',
                'La réponse de Claude est contrainte par un schéma, et chaque échec (refus, limite de débit, clé invalide) a un message d’erreur humain.',
                'L’administration passe sur son propre domaine avec une seule variable d’environnement.',
            ],
        ],
    ],

    'experience' => [
        'dennenboom' => [
            'location' => 'Gand, Belgique · À distance',
            'roles' => [
                'senior' => 'Ingénieur logiciel senior',
            ],
            'highlights' => [
                'Création de quatre des packages Laravel open source de l’entreprise, [Verdant UI](:verdant), [Pinert](:pinert), [Findle](:findle) et [Harvest](:harvest), parmi de nombreux autres packages et services internes.',
                'Rédaction d’articles pour le [blog de l’entreprise](:blog) : PostgreSQL, tests, serveurs MCP et les coulisses de nos packages.',
                'Développement et maintenance de plusieurs applications clientes avec Laravel, Symfony, Vue.js et Alpine.js.',
                'Administration de bases MySQL et PostgreSQL, qui restent rapides à mesure que les données grossissent.',
                'Conteneurisation des environnements de développement et de déploiement avec Docker.',
                'Optimisation des temps de réponse sur toute la stack, pour une infrastructure évolutive et fiable.',
            ],
        ],
        'youcan' => [
            'location' => 'Rabat · Hybride',
            'roles' => [
                'lead' => 'Lead Software Engineer',
                'engineer' => 'Ingénieur logiciel',
            ],
            'highlights' => [
                'Pilotage du développement de **YouCan Pay**, pour simplifier le traitement des paiements des marchands.',
                'Conception et développement de fonctionnalités sur les services e-commerce de YouCan, avec une attention particulière à l’expérience utilisateur.',
                'Conduite d’initiatives techniques et contribution à la direction technique de l’entreprise.',
                'Intégration et mentorat de développeurs juniors jusqu’à leur autonomie.',
            ],
        ],
        'wave' => [
            'location' => 'Témara · Sur site',
            'roles' => [
                'lead' => 'Technical Lead',
                'fullstack' => 'Développeur web full stack',
            ],
            'highlights' => [
                'Direction de la plateforme [Maline Immobilier](:maline) et responsabilité de sa livraison.',
                'Accompagnement des nouveaux développeurs : conseils, revues de code et formation.',
                'Développement d’applications web et de produits pour les clients de l’agence.',
            ],
        ],
        'vpi' => [
            'location' => 'Kénitra · Sur site',
            'roles' => [
                'developer' => 'Développeur logiciel',
            ],
            'highlights' => [
                'Développement et maintenance des solutions OMAG.',
                'Support et résolution d’incidents, avec à la clé une meilleure expérience utilisateur.',
            ],
        ],
        'surnaturel' => [
            'location' => 'Fès · Stage',
            'roles' => [
                'intern' => 'Développeur web stagiaire',
            ],
            'highlights' => [
                'Premiers pas professionnels : sites web en PHP, CSS et JavaScript.',
            ],
        ],
    ],

    'packages' => [
        'verdant' => [
            'summary' => 'Bibliothèque de plus de 70 composants Blade pour Laravel',
            'description' => 'Plus de 70 composants Blade pour Laravel, basés sur Alpine.js et Tailwind CSS : formulaires avec champs traduisibles, texte riche et recadrage d’images, tableaux dynamiques avec filtres et gestion des colonnes, modales, barres latérales et calendriers.',
        ],
        'pinert' => [
            'summary' => 'Les exceptions de tous les projets Laravel, directement sur Slack',
            'description' => 'Des rapports d’exception détaillés envoyés sur Slack dès qu’ils surviennent, pour que les erreurs de tous les projets arrivent dans un seul canal.',
        ],
        'findle' => [
            'summary' => 'Statistiques de pages vues compatibles RGPD, avec détection des robots',
            'description' => 'Des statistiques de pages vues compatibles RGPD : identifiants de visiteurs hachés au lieu de données personnelles, détection des robots et tableau de bord.',
        ],
        'harvest' => [
            'summary' => 'Automatisation des déploiements par SSH, inspirée de Laravel Envoy',
            'description' => 'L’automatisation des déploiements par SSH, inspirée de Laravel Envoy : scénarios scriptés, confirmations, hôtes de rebond et gestion de sudo.',
        ],
    ],

    'skills' => [
        'backend' => 'Back-end',
        'frontend' => 'Front-end',
        'data' => 'Données',
        'infrastructure' => 'Infrastructure',
        'quality' => 'Qualité & méthode',
        'ai' => 'IA',
    ],

    'education' => [
        'ntic' => [
            'title' => 'Technicien spécialisé en informatique',
            'school' => 'Institut spécialisé de technologie appliquée NTIC',
        ],
        'ibn_hazm' => [
            'title' => 'Baccalauréat, sciences mathématiques et sciences de l’ingénieur',
            'school' => 'Lycée Ibn Hazm',
        ],
    ],

    'languages' => [
        'ar' => ['name' => 'Arabe', 'level' => 'Langue maternelle'],
        'en' => ['name' => 'Anglais', 'level' => 'Courant · C2'],
        'fr' => ['name' => 'Français', 'level' => 'Avancé · C1'],
    ],

    'interests' => 'Échecs, billard américain et escape games. Tout ce qui ressemble à une énigme.',

];
