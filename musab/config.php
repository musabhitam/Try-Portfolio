<?php
declare(strict_types=1);

return [
    'site' => [
        'title'       => 'Ahmad Mus\'ab Al-Khair | Computer Science Student',
        'description' => 'Computer Science student at UMPSA. CCNA certified, Bronze Medal at FYProCom 2026, seeking an internship.',
        'brand'       => 'Musabhitam',
        'name'        => 'Ahmad Mus\'ab Al-Khair bin Fauzy',
        'firstName'   => 'Mus\'ab',
        'email'       => 'ahmadmusabf@gmail.com',
        'photo'       => 'assets/me.jpeg',
        'lang'        => 'en',
    ],

    'nav' => [
        '#intro'   => 'Intro',
        '#about'   => 'About',
        '#skills'  => 'Skills',
        '#awards'  => 'Awards',
        '#work'    => 'Work',
        '#contact' => 'Contact',
    ],

    'intro' => [
        'heading' => 'Hi, I\'m Mus\'ab.',
        'lede'    => 'Computer Science student at Universiti Malaysia Pahang Al-Sultan Abdullah. I build websites, games and I configure networks.',
    ],

    'about' => [
        'paragraphs' => [
            'I\'m Ahmad Mus\'ab Al-Khair bin Fauzy, a Diploma in Computer Science student at UMPSA. I like learning new skills fast and I work well both on my own and in a team.',
            'Outside class I volunteer at university events, including Hackathon 2025 and Arena Showdown 2025.',
        ],
        'stats' => [
            ['value' => '3.28',   'label' => 'CGPA'],
            ['value' => 'CCNA',   'label' => 'Certified'],
            ['value' => 'Bronze', 'label' => 'FYProCom 2026'],
        ],
    ],

    'skills' => [
        'Programming'       => ['Java', 'C#', 'HTML', 'CSS', 'JavaScript', 'XAMPP'],
        'Networking'        => ['Cisco Packet Tracer', 'IP addressing and subnetting', 'Router and switch setup', 'TCP/IP', 'Troubleshooting', 'Structured cabling'],
        'Design and tools'  => ['Unity', 'Figma', 'UI/UX design', 'Photoshop', 'Premiere Pro', 'After Effects', 'Canva', 'VS Code'],
    ],

    'awards' => [
        'items' => [
            ['title' => 'Bronze Medal, FYProCom', 'meta' => '2026'],
            ['title' => 'Top 12 Final Year Project', 'meta' => '2026'],
            ['title' => 'Cisco CCNA', 'meta' => '2025'],
        ],
        'note' => 'FYProCom is the Final Year Project Competition at UMPSA. The Top 12 place is from PTA 2. The CCNA is from Cisco Networking Academy.',
    ],

    'work' => [
        'items' => [
            ['title' => 'Student Marketplace', 'meta' => 'Final year project'],
            ['title' => 'Office LAN cabling',   'meta' => 'Cat6 · TIA/EIA-568'],
            ['title' => 'Si Bongkok',           'meta' => 'Unity · C#'],
        ],
        'note' => 'Student Marketplace is a website only for university students, with student ID checks, messaging, seller ratings and an admin panel. Si Bongkok: The Forgotten Hero is a 2D adventure platformer with combat, enemy AI and checkpoints.',
    ],

    'contact' => [
        'heading' => 'Let\'s work together',
        'note'    => 'Hopefully successful.',
    ],

    'social' => [
    ['label' => 'GitHub',    'url' => 'https://github.com/musabhitam'],
    ['label' => 'JobStreet', 'url' => 'https://my.jobstreet.com/profiles/YLnxvlnHnv'],
],
];
