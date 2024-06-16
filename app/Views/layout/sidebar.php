<?php
function generateSidebar($menuItems) {
    $html = '';

    $categories = [
        'Core'      => ['Dashboard'],
        'Data'      => ['Masterdata', 'Layanan'],
        'Sistem'    => ['Pengaturan', 'Akun'],
    ];

    foreach ($categories as $category => $items) {
        $html .= generateCategory($category, $items, $menuItems);
    }

    return $html;
}

function generateCategory($category, $items, $menuItems) {
    $html = '<div class="nav accordion" id="accordionSidenav">';
    $html .= '<div class="sidenav-menu-heading">'.$category.'</div>';

    $filteredItems = array_filter($menuItems, function($menuItem) use ($items) {
        return in_array($menuItem['name'], $items);
    });
    $html .= generateMenuItems($filteredItems);

    $html .= '</div>';

    return $html;
}

function generateMenuItems($menuItems, $parentId = 'accordionSidenav', $isSubmenu = false) {
    $html = '';

    if ($isSubmenu) {
        $html .= '<nav class="sidenav-menu-nested nav">';
    }

    // Get the current URL path
    $currentUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    foreach ($menuItems as $item) {
        $collapseId = 'collapse' . str_replace(' ', '', $item['name']);
        $icon = isset($item['icon']) ? $item['icon'] : '';

        // Check if current URL matches the item URL
        $activeClass = ($currentUrl === '/' . trim($item['url'], '/')) ? 'active' : '';

        if (isset($item['submenu'])) {
            $html .= '<a class="nav-link collapsed '.$activeClass.'" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#'.$collapseId.'" aria-expanded="false" aria-controls="'.$collapseId.'">';
            if ($icon) {
                $html .= '<div class="nav-link-icon"><i data-feather="'.$icon.'"></i></div>';
            }
            $html .= $item['name'];
            $html .= '<div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>';
            $html .= '</a>';
            $html .= '<div class="collapse" id="'.$collapseId.'" data-bs-parent="#'.$parentId.'">';
            $html .= generateMenuItems($item['submenu'], $collapseId, true);
            $html .= '</div>';
        } else {
            $html .= '<a class="nav-link '.$activeClass.'" href="'.$item['url'].'">';
            if ($icon) {
                $html .= '<div class="nav-link-icon"><i data-feather="'.$icon.'"></i></div>';
            }
            $html .= $item['name'];
            $html .= '</a>';
        }
    }

    if ($isSubmenu) {
        $html .= '</nav>';
    }

    return $html;
}

$menuItems = [
    [
        'name' => 'Dashboard',
        'url' => '/',
        'icon' => 'activity',
    ],
    [
        'name' => 'Masterdata',
        'url' => '#',
        'icon' => 'columns',
        'submenu' => [
            ['name' => 'Pegawai', 'url' => 'employees'],
            ['name' => 'Departemen', 'url' => 'user'],
            ['name' => 'Tim', 'url' => 'divisi'],
            ['name' => 'Hari Libur Nasional', 'url' => 'jabatan'],
            ['name' => 'Referensi Jenis', 'url' => 'jenis-type'],
        ],
    ],
    [
        'name' => 'Layanan',
        'url' => '#',
        'icon' => 'grid',
        'submenu' => [
            ['name' => 'Absensi / Presensi', 'url' => 'absen'],
            ['name' => 'Cuti', 'url' => 'cuti'],
            ['name' => 'Izin', 'url' => 'izin'],
            ['name' => 'Lembur', 'url' => 'lembur']
        ],
    ],
    [
        'name' => 'Pengaturan',
        'url' => '#',
        'icon' => 'tool',
        'submenu' => [
            ['name' => 'User', 'url' => 'user'],
            ['name' => 'Role', 'url' => 'role'],
            ['name' => 'Setting Aplikasi', 'url' => 'setting-app'],
        ],
    ],
    [
        'name' => 'Akun',
        'url' => '#',
        'icon' => 'user',
    ],
];

?>

<nav class="sidenav shadow-right sidenav-light">
    <div class="sidenav-menu">
        <?= generateSidebar($menuItems); ?>
    </div>
    <div class="sidenav-footer">
        <div class="sidenav-footer-content">
            <div class="sidenav-footer-subtitle">Logged in as:</div>
            <div class="sidenav-footer-title">Valerie Luna</div>
        </div>
    </div>
</nav>
