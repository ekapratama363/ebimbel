<?php

return [
    'actions' => [
        'view' => 'Lihat',
        'create' => 'Tambah',
        'edit' => 'Ubah',
        'delete' => 'Hapus',
        'export' => 'Ekspor',
        'confirm' => 'Konfirmasi',
        'publish' => 'Terbitkan',
    ],

    'modules' => [
        'akademik' => [
            'label' => 'Akademik',
            'route' => 'akademik',
            'icon' => 'bi-journal-bookmark',
            'actions' => ['view', 'create', 'edit', 'delete', 'export', 'publish'],
        ],
        'kesiswaan' => [
            'label' => 'Kesiswaan',
            'route' => 'kesiswaan',
            'icon' => 'bi-people',
            'actions' => ['view', 'create', 'edit', 'delete', 'export'],
        ],
        'keuangan' => [
            'label' => 'Keuangan',
            'route' => 'keuangan',
            'icon' => 'bi-cash-coin',
            'actions' => ['view', 'create', 'edit', 'delete', 'export', 'confirm'],
        ],
        'kepegawaian' => [
            'label' => 'Kepegawaian',
            'route' => 'kepegawaian',
            'icon' => 'bi-person-badge',
            'actions' => ['view', 'create', 'edit', 'delete', 'export'],
        ],
        'konten-landing' => [
            'label' => 'Konten landing',
            'route' => 'konten-landing',
            'icon' => 'bi-window-desktop',
            'actions' => ['view', 'create', 'edit', 'delete'],
        ],
        'pengaturan' => [
            'label' => 'Pengaturan situs',
            'route' => 'pengaturan',
            'icon' => 'bi-gear',
            'actions' => ['view', 'edit'],
        ],
        'akses' => [
            'label' => 'Role & pengguna',
            'route' => 'akses.roles',
            'icon' => 'bi-shield-lock',
            'actions' => ['view', 'create', 'edit', 'delete'],
        ],
    ],
];
