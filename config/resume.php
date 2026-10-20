<?php

/*
 * Facts that don't change with the language. Every sentence lives in lang/{en,fr}/resume.php,
 * under the same keys, so adding an entry here means adding its copy there (TranslationsTest guards it).
 */

return [

    'name' => 'Ayoub Ezzini',

    'email' => 'ayoub@ezzini.ma',

    'timezone' => 'Africa/Casablanca',

    'career_started_at' => '2017-11-01',

    'repository' => 'https://github.com/ayoubezzini/ezzini.ma',

    'links' => [
        'github' => 'https://github.com/ayoubezzini',
        'linkedin' => 'https://www.linkedin.com/in/ayoubezzini',
    ],

    // Usable in any copy as :key, e.g. "[Maline Immobilier](:maline)".
    'urls' => [
        'maline' => 'https://maline-immobilier.fr',
        'khlass' => 'https://khlass.ma',
        'estock' => 'https://estock.ma',
        'nuvo' => 'https://nuvo.ma',
    ],

    // Screenshots live in public/images/projects/{locale}/{slug}.webp, in the language of the page.
    'projects' => [
        'khlass' => [
            'name' => 'Khlass',
            'url' => 'https://khlass.ma',
            'year' => 2026,
            'accent' => '#0F766E',
            'stack' => ['Laravel 13', 'PHP 8.4', 'Blade', 'Alpine.js', 'Tailwind CSS', 'MySQL', 'Redis', 'Chromium PDF', 'YouCan Pay', 'Cloudflare R2', 'Turnstile', 'Docker'],
        ],
        'estock' => [
            'name' => 'eStock',
            'url' => 'https://estock.ma',
            'year' => 2026,
            'accent' => '#1F4DE0',
            'stack' => ['Laravel 13', 'PHP 8.4', 'Blade', 'Alpine.js', 'Tailwind CSS', 'MySQL 8.4', 'Redis', 'ClickHouse', 'Pest', 'Cloudflare R2', 'Resend', 'Docker'],
        ],
        'nuvo' => [
            'name' => 'Nuvo',
            'url' => 'https://nuvo.ma',
            'year' => 2026,
            'accent' => '#D4541F',
            'stack' => ['Laravel 13', 'PHP 8.4', 'Blade', 'Alpine.js', 'Tailwind CSS', 'Claude API', 'MySQL', 'Redis', 'Turnstile', 'Docker'],
        ],
    ],

    'experience' => [
        'dennenboom' => [
            'company' => 'Dennenboom',
            'url' => null,
            'roles' => [
                'senior' => ['start' => '2024-06', 'end' => null],
            ],
            'stack' => ['Laravel', 'Symfony', 'Vue.js', 'Alpine.js', 'MySQL', 'PostgreSQL', 'Docker'],
        ],
        'youcan' => [
            'company' => 'YouCan',
            'url' => null,
            'roles' => [
                'lead' => ['start' => '2023-08', 'end' => '2024-09'],
                'engineer' => ['start' => '2022-05', 'end' => '2023-08'],
            ],
            'stack' => ['Laravel', 'PHP', 'MySQL', 'Redis', 'Elasticsearch', 'Docker', 'Linux'],
        ],
        'wave' => [
            'company' => 'Wave',
            'url' => null,
            'roles' => [
                'lead' => ['start' => '2021-01', 'end' => '2022-05'],
                'fullstack' => ['start' => '2018-11', 'end' => '2021-01'],
            ],
            'stack' => ['Symfony', 'Laravel', 'PHP', 'JavaScript', 'MySQL', 'Linux'],
        ],
        'vpi' => [
            'company' => 'VPI Info',
            'url' => null,
            'roles' => [
                'developer' => ['start' => '2017-11', 'end' => '2018-11'],
            ],
            'stack' => ['PHP', 'JavaScript', 'CSS3', 'MySQL'],
        ],
        'surnaturel' => [
            'company' => 'Surnaturel Agency',
            'url' => null,
            'roles' => [
                'intern' => ['start' => '2016-09', 'end' => '2016-11'],
            ],
            'stack' => ['PHP', 'CSS3', 'JavaScript'],
        ],
    ],

    'skills' => [
        'backend' => ['PHP', 'Laravel', 'Symfony', 'REST APIs', 'Multi-tenant SaaS', 'Queues', 'SOLID'],
        'frontend' => ['Blade', 'Alpine.js', 'Vue.js', 'React', 'Tailwind CSS', 'HTML5', 'CSS3', 'Accessibility'],
        'data' => ['MySQL', 'MariaDB', 'PostgreSQL', 'Redis', 'ClickHouse', 'MongoDB', 'Elasticsearch', 'ELK'],
        'infrastructure' => ['Docker', 'NGINX', 'Linux', 'Coolify', 'Hetzner', 'Cloudflare', 'GitHub Actions', 'GitLab CI'],
        'quality' => ['Pest', 'PHPUnit', 'Code review', 'Agile / Scrum', 'Jira', 'Confluence'],
        'ai' => ['Claude Code', 'Claude API', 'Prompt design', 'Structured outputs', 'Agentic workflows'],
    ],

    'education' => [
        'ntic' => ['start' => '2015', 'end' => '2017'],
        'ibn_hazm' => ['start' => '2013', 'end' => '2014'],
    ],

    'languages' => ['ar', 'en', 'fr'],

];
