<?php

return [

    'role' => 'Senior Software Engineer',

    'location' => 'Morocco',

    'headline' => 'Software engineer who sweats the *details*.',

    'intro' => 'I’m Ayoub, a senior engineer with :years years of PHP and Laravel behind me. I’ve led teams, shipped a payment platform, and today I design, build and run my own products. I’m the kind of engineer who will spend an afternoon on a single pixel, because that’s where the difference between good and great lives.',

    'summary' => 'Senior software engineer with :years years of experience building web products with PHP, Laravel and Symfony. Former lead engineer at YouCan, where I spearheaded YouCan Pay. Founder and sole engineer of three live products. Obsessive about details, fluent with AI tools, and used to owning things from the database schema to the DNS record.',

    'stats' => [
        'years' => ['value' => ':years', 'label' => 'years shipping software'],
        'products' => ['value' => '3', 'label' => 'products I own end to end'],
        'deploy' => ['value' => '1', 'label' => 'commit to deploy this site'],
    ],

    'principles' => [
        'details' => [
            'title' => 'Details are the product',
            'text' => 'The gap between good and great is a pile of small things nobody asked for: a focus ring, a number that doesn’t jump, an error message that says what to do next. People feel them even when they can’t name them, so I’ll gladly spend an afternoon on one.',
        ],
        'ownership' => [
            'title' => 'I own what I ship',
            'text' => 'Khlass, eStock and Nuvo are mine from the first sketch to the DNS record: product, design, code, infrastructure. Every feature and every fix carries my name, and I like it that way.',
        ],
        'ai' => [
            'title' => 'AI, with judgement',
            'text' => 'I work with Claude every day, to explore, to write code and to review it. It makes me faster; it doesn’t decide for me. Every line gets read, questioned and often rewritten. In Nuvo, Claude is also a feature: it turns any product page into store-ready content in three languages.',
        ],
        'shipping' => [
            'title' => 'Ship small, ship often',
            'text' => 'Small commits, automated checks, no manual deploys. This site is the proof: a push to main runs the tests, builds the image, renders the PDF résumé from the page itself and goes live.',
        ],
    ],

    'projects' => [
        'khlass' => [
            'tagline' => 'Invoicing and compliance for Moroccan auto-entrepreneurs.',
            'summary' => 'Moroccan freelancers live under hard limits: a yearly turnover ceiling, a flat tax on every dirham collected, and a 30% withholding once a single client pays more than 80,000 MAD in a year. Generic invoicing tools make PDFs. Khlass answers the questions that actually matter: how much room is left this year, which client is about to cross the threshold, and how much tax is due this quarter.',
            'image_alt' => 'The Khlass landing page',
            'built' => [
                'Quotes, invoices and credit notes with gap-free yearly numbering (`FA-2026-0001`), where a number can only move forward.',
                'Live gauges for the annual ceiling and the per-client threshold, computed on cash actually collected and converted to MAD at the payment date.',
                'A free-form A4 document designer: blocks positioned to the millimetre, legal mentions movable but never removable.',
                'Pixel-exact PDFs rendered by headless Chromium, with an automatic fallback when Chromium isn’t available.',
                'Subscriptions by card through YouCan Pay or by bank transfer with manual approval, both through a single activation path.',
                'The whole product, documents included, in French and English.',
            ],
            'details' => [
                'An issued invoice is immutable. Corrections go through a credit note linked to the original, exactly as the law expects.',
                'Ceilings, rates and legal wording live in one config file: a new finance law is a one-line change, not a hunt through the code.',
                'Clients paying from abroad through Deel, Upwork or Payoneer get a yearly total instead of a withholding gauge that would mislead.',
                'Legal content is frozen when a document is issued; branding is applied when it’s rendered. Changing your logo never rewrites history.',
            ],
        ],
        'estock' => [
            'tagline' => 'Sales, purchases and inventory for Moroccan businesses.',
            'summary' => 'eStock is the multi-tenant rebuild of a legacy Symfony and Vue stock application that a distributor still runs every day. Quotes, delivery notes, invoices, purchases, stock and payments in a single tool that works on a phone, built around Moroccan rules: ICE, VAT, stamp duty and amounts in words.',
            'image_alt' => 'The eStock dashboard with revenue, purchases, receivables and the monthly sales chart',
            'built' => [
                'Spaces with yearly dossiers, roles per space and a permission catalogue resolved per dossier.',
                'A stock ledger as the single source of truth: FIFO lots, weighted average cost and transfers between warehouses.',
                'Nine document types sharing one form engine: drag-to-reorder lines, sections, subtotals, and a discount spread across VAT rates with the same algorithm in JavaScript and on the PDF.',
                'An importer that moves a real client’s legacy database into a fresh space, numbering and history included.',
                'Dashboards with server-rendered SVG charts, in colours checked for colour blindness.',
                'A landing page in French, English and Arabic (right to left), with screenshots generated automatically from a demo space.',
            ],
            'details' => [
                'Almost everything can be undone: a validated document goes back to draft, its stock movements are reversed and its number is kept.',
                'Money and quantities are computed with arbitrary-precision decimals, never floats.',
                'Every user action lands in a ClickHouse audit log with automatic rotation, readable as a plain-language history.',
                'A ⌘K global search that applies exactly the same visibility rules as the lists it searches.',
            ],
        ],
        'nuvo' => [
            'tagline' => 'An online store built for cash on delivery across Morocco.',
            'summary' => 'In Morocco, most customers pay on delivery and ask their questions on WhatsApp. Nuvo is a storefront designed around that reality: ordering takes seconds, nothing is paid online, and the owner runs the catalogue, orders and stock from a dedicated back office.',
            'image_alt' => 'The Nuvo storefront home page',
            'built' => [
                'A fast storefront in French, English and Arabic (right to left), with variants, brands, promotions and coupons.',
                'Express checkout guarded by per-IP and per-phone rate limits and Cloudflare Turnstile against fake orders.',
                'Product import with Claude: paste a product page, get store-ready content in three languages with the right images, validated against a JSON schema.',
                'A back office for orders and their status history, stock movements, home page sections, banners and pages.',
                'A responsive image pipeline and Slack notifications for every new order.',
            ],
            'details' => [
                'A visitor’s language is kept even when they follow a relative link from a banner.',
                'Claude’s answer is constrained by a schema, and every failure (refusal, rate limit, invalid key) gets a human error message.',
                'The administration moves to its own domain with a single environment variable.',
            ],
        ],
    ],

    'experience' => [
        'dennenboom' => [
            'location' => 'Ghent, Belgium · Remote',
            'roles' => [
                'senior' => 'Senior Software Engineer',
            ],
            'highlights' => [
                'Build and maintain several client applications with Laravel, Symfony, Vue.js and Alpine.js.',
                'Run MySQL and PostgreSQL databases and keep them fast as data grows.',
                'Containerise development and deployment environments with Docker.',
                'Tune response times across the stack and keep the infrastructure scalable and reliable.',
            ],
        ],
        'youcan' => [
            'location' => 'Rabat · Hybrid',
            'roles' => [
                'lead' => 'Lead Software Engineer',
                'engineer' => 'Software Engineer',
            ],
            'highlights' => [
                'Spearheaded the development of **YouCan Pay**, streamlining payment processing for merchants.',
                'Designed and built features across YouCan’s e-commerce services, with a focus on user experience.',
                'Led engineering initiatives and helped shape the company’s technical direction.',
                'Onboarded and mentored junior developers until they were autonomous.',
            ],
        ],
        'wave' => [
            'location' => 'Témara · On-site',
            'roles' => [
                'lead' => 'Technical Lead',
                'fullstack' => 'Full Stack Web Developer',
            ],
            'highlights' => [
                'Led the [Maline Immobilier](:maline) platform and owned its delivery.',
                'Mentored new developers with guidance, reviews and training.',
                'Built web applications and products for the agency’s clients.',
            ],
        ],
        'vpi' => [
            'location' => 'Kénitra · On-site',
            'roles' => [
                'developer' => 'Software Developer',
            ],
            'highlights' => [
                'Built and maintained the OMAG solutions.',
                'Handled support and troubleshooting, improving the user experience along the way.',
            ],
        ],
        'surnaturel' => [
            'location' => 'Fès · Internship',
            'roles' => [
                'intern' => 'Web Developer Intern',
            ],
            'highlights' => [
                'First professional steps: websites built with PHP, CSS and JavaScript.',
            ],
        ],
    ],

    'packages' => [
        'verdant' => [
            'summary' => 'UI component library for Laravel: more than 70 Blade components',
            'description' => 'More than 70 Blade components for Laravel, built on Alpine.js and Tailwind CSS: forms with translatable fields, rich text and image cropping, dynamic tables with filters and column management, modals, sidebars and calendars.',
        ],
        'pinert' => [
            'summary' => 'Exception reports from every Laravel project, straight to Slack',
            'description' => 'Detailed exception reports sent to Slack the moment they happen, so errors from every project land in one channel.',
        ],
        'findle' => [
            'summary' => 'GDPR-friendly page-view analytics with bot detection',
            'description' => 'GDPR-friendly page-view analytics: hashed visitor identifiers instead of personal data, bot detection and a statistics dashboard.',
        ],
        'harvest' => [
            'summary' => 'SSH deployment automation, inspired by Laravel Envoy',
            'description' => 'SSH deployment automation inspired by Laravel Envoy: scripted scenarios, confirmation prompts, jump hosts and sudo handling.',
        ],
    ],

    'skills' => [
        'backend' => 'Back end',
        'frontend' => 'Front end',
        'data' => 'Data',
        'infrastructure' => 'Infrastructure',
        'quality' => 'Quality & process',
        'ai' => 'AI',
    ],

    'education' => [
        'ntic' => [
            'title' => 'Specialised Technician in Computer Science',
            'school' => 'Specialised Institute of Applied Technology NTIC',
        ],
        'ibn_hazm' => [
            'title' => 'High School Diploma, Mathematics and Engineering Science',
            'school' => 'Ibn Hazm High School',
        ],
    ],

    'languages' => [
        'ar' => ['name' => 'Arabic', 'level' => 'Native'],
        'en' => ['name' => 'English', 'level' => 'Fluent · C2'],
        'fr' => ['name' => 'French', 'level' => 'Advanced · C1'],
    ],

    'interests' => 'Chess, eight-ball and escape games. Anything with a puzzle in it.',

];
