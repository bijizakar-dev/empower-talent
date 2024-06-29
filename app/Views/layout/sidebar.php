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
        $activeClass = '';

        if (isset($item['submenu'])) {
            $submenuActive = false;
            foreach ($item['submenu'] as $subItem) {
                if ($currentUrl === '/' . trim($subItem['url'], '/')) {
                    $submenuActive = true;
                    $activeClass = 'active';
                    break;
                }

                if (isset($subItem['submenu'])) {
                    foreach ($subItem['submenu'] as $subSubItem) {
                        if ($currentUrl === '/' . trim($subSubItem['url'], '/')) {
                            $submenuActive = true;
                            $activeClass = 'active';
                            break 2; // Break out of both loops
                        }
                    }
                }
            }

            $html .= '<a class="nav-link collapsed '.$activeClass.'" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#'.$collapseId.'" aria-expanded="false" aria-controls="'.$collapseId.'">';
            if ($icon) {
                $html .= '<div class="nav-link-icon"><i data-feather="'.$icon.'"></i></div>';
            }
            $html .= $item['name'];
            $html .= '<div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>';
            $html .= '</a>';
            $html .= '<div class="collapse '.($submenuActive ? 'show' : '').'" id="'.$collapseId.'" data-bs-parent="#'.$parentId.'">';
            $html .= generateMenuItems($item['submenu'], $collapseId, true);
            $html .= '</div>';
        } else {
            if ($currentUrl === '/' . trim($item['url'], '/')) {
                $activeClass = 'active';
            }

            $html .= '<a class="nav-link '.$activeClass.'" href="'.base_url().$item['url'].'">';
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
        'url' => '',
        'icon' => 'activity',
    ],
    [
        'name' => 'Masterdata',
        'url' => 'masterdata',
        'icon' => 'columns',
        'submenu' => [
            ['name' => 'Pegawai', 'url' => 'masterdata/employee'],
            ['name' => 'Departemen', 'url' => 'masterdata/department'],
            ['name' => 'Tim', 'url' => 'masterdata/team'],
            ['name' => 'Jadwal Libur', 'url' => 'masterdata/holiday'],
            ['name' => 'Referensi Jenis', 'url' => 'masterdata/ReferenceType'],
            ['name' => 'Status Pegawai', 'url' => 'masterdata/statusEmployee'],
        ],
    ],
    [
        'name' => 'Layanan',
        'url' => '#',
        'icon' => 'grid',
        'submenu' => [
            ['name' => 'Absensi / Presensi', 'url' => 'absen'],
            [
                'name' => 'Izin', 
                'url' => 'service',
                'submenu' => [
                    ['name' => 'Pengajuan Izin', 'url' => 'service/requestPermit'],
                    ['name' => 'List Izin', 'url' => 'service/permit'],
                ]
            ],
            [
                'name' => 'Cuti', 
                'url' => 'service/requestPermit',
                'submenu' => [
                    ['name' => 'Pengajuan Cuti', 'url' => 'service/requestPaidLeave'],
                    ['name' => 'List Cuti', 'url' => 'service/paidLeave'],
                ]
            ],
            ['name' => 'Lembur', 'url' => 'lembur']
        ],
    ],
    [
        'name' => 'Pengaturan',
        'url' => 'sistem',
        'icon' => 'tool',
        'submenu' => [
            ['name' => 'User', 'url' => 'sistem/user'],
            ['name' => 'Role', 'url' => 'sistem/role'],
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
