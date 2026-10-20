<?php

/*
 * Facts that don't change with the language. Every sentence lives in lang/{en,fr}/resume.php,
 * under the same keys, so adding an entry here means adding its copy there (TranslationsTest guards it).
 */

return [

    'name' => 'Ayoub Ezzini',

    'email' => 'ayoub@ezzini.ma',

    'phone' => '+212 623-357087',

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
        'packages' => 'https://dennenboom.be/packages',
        'blog' => 'https://dennenboom.be/blog',
        'verdant' => 'https://github.com/ben-dennenboom/verdant-ui',
        'pinert' => 'https://github.com/ben-dennenboom/pinert',
        'findle' => 'https://github.com/ben-dennenboom/Findle',
        'harvest' => 'https://github.com/ben-dennenboom/harvest',
    ],

    // Screenshots live in public/images/projects/{locale}/{slug}.webp, in the language of the page.
    // Stacks lead with what's distinctive: the home page and the CV only show the first few.
    'projects' => [
        'khlass' => [
            'name' => 'Khlass',
            'url' => 'https://khlass.ma',
            'year' => 2026,
            'accent' => '#0F766E',
            'stack' => ['Laravel 13', 'PHP 8.4', 'Chromium PDF', 'YouCan Pay', 'MySQL', 'Redis', 'Cloudflare R2', 'Turnstile', 'Alpine.js', 'Tailwind CSS', 'Blade', 'Docker'],
        ],
        'estock' => [
            'name' => 'eStock',
            'url' => 'https://estock.ma',
            'year' => 2026,
            'accent' => '#1F4DE0',
            'stack' => ['Laravel 13', 'PHP 8.4', 'ClickHouse', 'MySQL 8.4', 'Redis', 'Pest', 'Cloudflare R2', 'Resend', 'Alpine.js', 'Tailwind CSS', 'Blade', 'Docker'],
        ],
        'nuvo' => [
            'name' => 'Nuvo',
            'url' => 'https://nuvo.ma',
            'year' => 2026,
            'accent' => '#D4541F',
            'stack' => ['Laravel 13', 'PHP 8.4', 'Claude API', 'MySQL', 'Redis', 'Turnstile', 'Alpine.js', 'Tailwind CSS', 'Blade', 'Docker'],
        ],
    ],

    'experience' => [
        'dennenboom' => [
            'company' => 'Dennenboom',
            'url' => 'https://dennenboom.be',
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

    // Dennenboom's open-source Laravel packages that Ayoub created (first commit and most of the work).
    'packages' => [
        'verdant' => [
            'name' => 'Verdant UI',
            'repository' => 'https://github.com/ben-dennenboom/verdant-ui',
            'documentation' => 'https://verdant.dennenboom.be',
            'year' => 2025,
        ],
        'pinert' => [
            'name' => 'Pinert',
            'repository' => 'https://github.com/ben-dennenboom/pinert',
            'documentation' => 'https://dennenboom.be/packages/pinert',
            'year' => 2025,
        ],
        'findle' => [
            'name' => 'Findle',
            'repository' => 'https://github.com/ben-dennenboom/Findle',
            'documentation' => 'https://dennenboom.be/packages/findle',
            'year' => 2025,
        ],
        'harvest' => [
            'name' => 'Harvest',
            'repository' => 'https://github.com/ben-dennenboom/harvest',
            'documentation' => null,
            'year' => 2025,
        ],
    ],

    // Ayoub's articles on the Dennenboom blog, newest first. Titles as published (in English).
    'articles' => [
        ['date' => '2026-10-09', 'title' => 'How to add an MCP server to your platform', 'url' => 'https://dennenboom.be/blog/how-to-add-an-mcp-server-to-your-platform'],
        ['date' => '2025-09-22', 'title' => 'Building Verdant UI: How We Stopped Reinventing Dashboard Wheels', 'url' => 'https://dennenboom.be/blog/building-verdant-ui-how-we-stopped-reinventing-dashboard-wheels'],
        ['date' => '2025-09-22', 'title' => 'Building a GDPR-Friendly Analytics Package: The Tech Behind Findle', 'url' => 'https://dennenboom.be/blog/building-a-gdpr-friendly-analytics-package-the-tech-behind-findle'],
        ['date' => '2025-06-26', 'title' => 'Why Your Code Needs Bodyguards: The Real Value of Testing', 'url' => 'https://dennenboom.be/blog/why-your-code-needs-bodyguards-the-real-value-of-testing'],
        ['date' => '2025-06-25', 'title' => 'Custom Domain Management for Trust Platform: A Technical Deep Dive', 'url' => 'https://dennenboom.be/blog/custom-domain-management-for-trust-platform-a-technical-deep-dive'],
        ['date' => '2025-06-09', 'title' => 'PostgreSQL: The Swiss Army Knife of Databases', 'url' => 'https://dennenboom.be/blog/postgresql-the-swiss-army-knife-of-databases'],
        ['date' => '2025-06-02', 'title' => 'The Hidden Superpowers of PostgreSQL: Geospatial Queries', 'url' => 'https://dennenboom.be/blog/the-hidden-superpowers-of-postgresql-geospatial-queries'],
        ['date' => '2025-05-02', 'title' => 'The Hidden Superpowers of PostgreSQL: Fuzzy Search', 'url' => 'https://dennenboom.be/blog/the-hidden-superpowers-of-postgresql-fuzzy-search'],
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
