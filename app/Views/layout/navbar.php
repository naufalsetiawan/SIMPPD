<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 ">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start rtl:justify-end">
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 ">
                    <span class="sr-only">Open sidebar</span>
                </button>
                <a href="" class="flex ms-2 md:me-24 items-center">
                    <img src="/img/LOGO KOMDIGI-VERTICAL 2.png" class="h-8 me-3" alt="" />
                    <div class="flex flex-col justify-start">
                        <span class="text-base font-semibold whitespace-nowrap">ESPJ</span>
                        <span class="text-sm text-gray-500 ">Balai Monitor SFR Kelas I Semarang</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</nav>

<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-[4.5rem] transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0" aria-label="Sidebar">
    <div class="h-full pb-4  bg-white flex flex-col justify-between">
        <div class="w-full">
            <?php if (session()->get('current_role') != 0): ?>
                <!-- Module Switch -->
                <div class="">
                    <div class="grid grid-cols-2">
                        <label for="penugasan" class="cursor-pointer px-4 flex flex-col justify-center items-center py-2">
                            <input type="radio" id="penugasan" name="module" value="penugasan" class="hidden peer"
                                <?php if (session()->get('selected_module') === 'penugasan') echo 'checked'; ?>
                                onclick="window.location.href='/penugasan/dashboard'">
                            <i class="fa-solid fa-file peer-checked:text-blue-500 font-semibold text-lg peer-hover:text-blue-500"></i>
                            <span class="text-gray-900 peer-checked:text-blue-500 font-semibold peer-hover:text-blue-500">ST</span>
                        </label>

                        <label for="espj" class="cursor-pointer px-4 flex flex-col justify-center items-center py-2">
                            <input type="radio" id="espj" name="module" value="espj" class="hidden peer"
                                <?php if (session()->get('selected_module') === 'espj') echo 'checked'; ?>
                                onclick="window.location.href='/espj/dashboard'">
                            <i class="fa-solid fa-plane peer-checked:text-blue-500 font-semibold text-lg peer-hover:text-blue-500"></i>
                            <span class="text-gray-900 peer-checked:text-blue-500 font-semibold peer-hover:text-blue-500">E-SPJ</span>
                        </label>

                    </div>
                    <div class="border border-gray-300 mx-2"></div>
                </div>


                <!-- Navigation (Penugasan) -->
                <ul id="penugasan-nav" class="font-medium overflow-y-auto">
                    <li>
                        <a href="/penugasan/dashboard" class="flex items-center w-full p-2 px-4 text-gray-900 transition duration-75 group  
                    <?= (current_url() == site_url('/penugasan/dashboard')) ? 'bg-blue-500 text-white' : 'hover:bg-blue-500' ?>">
                            <i class=" fa-solid fa-chart-line transition duration-75 group-hover:text-white"></i>
                            <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap group-hover:text-white">Dashboard</span>
                        </a>
                    </li>
                    <?php if (session()->get('current_role') == 5): ?>
                        <li>
                            <a href="/penugasan/tugas" class="flex items-center w-full p-2 px-4 text-gray-900 transition duration-75 group  
                    <?= (current_url() == site_url('/penugasan/tugas')) ? 'bg-blue-500 text-white' : 'hover:bg-blue-500' ?>">
                                <i class="fa-solid fa-bars-progress transition duration-75 group-hover:text-white"></i>
                                <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap group-hover:text-white">Daftar Penugasan</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if ((session()->get('current_role') == 4) ||  (session()->get('current_role') == 8)): ?>
                        <li>
                            <a href="/penugasan/pengajuan_surat_tugas" class="flex items-center w-full p-2 px-4 text-gray-900 transition duration-75 group  
                                 <?= (current_url() == site_url('/penugasan/pengajuan_surat_tugas')) ? 'bg-blue-500 text-white' : 'hover:bg-blue-500' ?>">
                                <i class="fa-solid fa-bars-progress transition duration-75 group-hover:text-white"></i>
                                <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap group-hover:text-white">Pengajuan Surat Tugas</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (session()->get('current_role') == 9): ?>
                        <li>
                            <a href="/penugasan/rekap" class="flex items-center w-full p-2 px-4 text-gray-900 transition duration-75 group  
                                 <?= (current_url() == site_url('/penugasan/rekap')) ? 'bg-blue-500 text-white' : 'hover:bg-blue-500' ?>">
                                <i class="fa-solid fa-bars-progress transition duration-75 group-hover:text-white"></i>
                                <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap group-hover:text-white">Rekap</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>

                <!-- Navigation (ESPJ) -->
                <ul id="espj-nav" class="font-medium overflow-y-auto hidden">
                </ul>

                <!-- ADMIN -->
            <?php elseif (session()->get('current_role') == 0): ?>
                <ul class="font-medium overflow-y-auto">
                    <li>
                        <a href="/admin/dashboard" class="flex items-center w-full p-2 px-4 text-gray-900 transition duration-75 group hover:bg-blue-500  
                        <?= (current_url() == site_url('/admin/dashboard')) ? 'bg-blue-500 text-white' : 'hover:bg-blue-500' ?>">
                            <i class="fa-solid fa-tasks transition duration-75 group-hover:text-white"></i>
                            <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap group-hover:text-white">Dashboard Admin</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/tim_kerja" class="flex items-center w-full p-2 px-4 text-gray-900 transition duration-75 group hover:bg-blue-500
                         <?= (current_url() == site_url('/admin/tim_kerja')) ? 'bg-blue-500 text-white' : 'hover:bg-blue-500' ?>">
                            <i class="fa-solid fa-file-alt transition duration-75 group-hover:text-white"></i>
                            <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap group-hover:text-white">Daftar Tim Kerja</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/pegawai" class="flex items-center w-full p-2 px-4 text-gray-900 transition duration-75 group hover:bg-blue-500
                         <?= (current_url() == site_url('/admin/pegawai')) ? 'bg-blue-500 text-white' : 'hover:bg-blue-500' ?>">
                            <i class="fa-solid fa-users transition duration-75 group-hover:text-white"></i>
                            <span class="ms-3 group-hover:text-white">Daftar Pegawai</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/users" class="flex items-center w-full p-2 px-4 text-gray-900 transition duration-75 group hover:bg-blue-500
                         <?= (current_url() == site_url('/admin/users')) ? 'bg-blue-500 text-white' : 'hover:bg-blue-500' ?>">
                            <i class="fa-solid fa-user transition duration-75 group-hover:text-white"></i>
                            <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap group-hover:text-white">Daftar Pengguna</span>
                        </a>
                    </li>
                    <li>
                        <?php
                        $currentUrl = current_url();
                        ?>
                    <li>
                        <button onclick="toggleSubmenu('tugasSubmenu')" class="flex items-center w-full p-2 px-4 text-gray-900 transition duration-75 group hover:bg-blue-500">
                            <i class="fa-solid fa-briefcase transition duration-75 group-hover:text-white"></i>
                            <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap group-hover:text-white">Data Master</span>
                            <i class="fa-solid fa-chevron-down ml-auto transition duration-75 group-hover:text-white"></i>
                        </button>
                        <ul id="tugasSubmenu" class="<?= in_array($currentUrl, [
                                                            site_url('/admin/landasan_surat'),
                                                            site_url('/admin/kota'),
                                                            site_url('/admin/petugas'),
                                                            site_url('/admin/kop_surat')
                                                        ]) ? 'block' : 'hidden' ?> pl-6">
                            <li>
                                <a href="/admin/kota" class="flex items-center w-full p-2 px-4 text-gray-900 transition duration-75 group hover:bg-blue-500
                                    <?= (current_url() == site_url('/admin/kota')) ? 'bg-blue-500 text-white' : 'hover:bg-blue-500' ?>">
                                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap group-hover:text-white">Daftar Kota</span>
                                </a>
                            </li>
                            <li>
                                <a href="/admin/landasan_surat" class="flex items-center w-full p-2 px-4 text-gray-900 transition duration-75 group hover:bg-blue-500
                                <?= (current_url() == site_url('/admin/landasan_surat')) ? 'bg-blue-500 text-white' : 'hover:bg-blue-500' ?>">
                                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap group-hover:text-white">Landasan</span>
                                </a>
                            </li>
                            <li>
                                <a href="/admin/petugas" class="flex items-center w-full p-2 px-4 text-gray-900 transition duration-75 group hover:bg-blue-500
                                    <?= (current_url() == site_url('/admin/petugas')) ? 'bg-blue-500 text-white' : 'hover:bg-blue-500' ?>">
                                    <span class="ms-3 group-hover:text-white">Petugas</span>
                                </a>
                            </li>
                            <li>
                                <a href="/admin/kop_surat" class="flex items-center w-full p-2 px-4 text-gray-900 transition duration-75 group hover:bg-blue-500
                                <?= (current_url() == site_url('/admin/kop_surat')) ? 'bg-blue-500 text-white' : 'hover:bg-blue-500' ?>">
                                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap group-hover:text-white">Kop Surat</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="/admin/penugasan/tugas" class="flex items-center w-full p-2 px-4 text-gray-900 transition duration-75 group hover:bg-blue-500
                         <?= (current_url() == site_url('/admin/penugasan/tugas')) ? 'bg-blue-500 text-white' : 'hover:bg-blue-500' ?>">
                            <i class="fa-solid fa-briefcase transition duration-75 group-hover:text-white"></i>
                            <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap group-hover:text-white">Daftar Penugasan</span>
                        </a>
                    </li>
                </ul>
            <?php endif; ?>
        </div>

        <ul class="font-medium">
            <!-- User Card -->
            <li class="p-4 border-b-2 border-t-2">
                <div class="flex items-center py-0">
                    <span class="font-bold text-gray-700">
                        Hi, <?= esc(explode(' ', session()->get('nama') ?? 'User')[0]) ?>
                    </span>

                </div>
                <div class="flex flex-col gap-y-2">
                    <span class="text-xs text-gray-700"> Anda Masuk Sebagai </span>
                    <form id="roleForm" method="POST" action="/user/switch_role">
                        <?php
                        $roleNames = session()->get('role_names');
                        $roles = session()->get('roles');
                        $currentRole = session()->get('current_role');
                        ?>
                        <select id="switch_role" name="switch_role" class="py-1 px-2 w-full text-sm font-normal text-gray-700 border border-gray-200 rounded-sm">
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= esc($role); ?>" <?= ($currentRole == $role) ? 'selected' : '' ?>>
                                    <?= esc($roleNames[$role] ?? ''); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>
            </li>
            <li>
                <a href="/logout" class="flex items-center w-full p-2 px-4 text-base text-gray-900 transition duration-75 group hover:bg-red-500">
                    <i class="fa-solid fa-arrow-right-from-bracket text-red-500 h-full transition duration-75 group-hover:text-white "></i>
                    <span class="flex-1 ms-3 text-left text-red-500 rtl:text-right whitespace-nowrap group-hover:text-white">Sign Out</span>
                </a>
            </li>
        </ul>
    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the radio buttons
        const penugasanRadio = document.getElementById('penugasan');
        const espjRadio = document.getElementById('espj');

        // Get the navigation lists
        const penugasanNav = document.getElementById('penugasan-nav');
        const espjNav = document.getElementById('espj-nav');

        // Function to toggle the visibility of the navigation
        function toggleNavigation() {
            if (penugasanRadio.checked) {
                penugasanNav.classList.remove('hidden');
                espjNav.classList.add('hidden');
            } else if (espjRadio.checked) {
                penugasanNav.classList.add('hidden');
                espjNav.classList.remove('hidden');
            }
        }

        // Initially call the toggle function to set the correct navigation on load
        toggleNavigation();

        // Add event listeners to the radio buttons to switch between modules
        penugasanRadio.addEventListener('change', toggleNavigation);
        espjRadio.addEventListener('change', toggleNavigation);
    });

    document.getElementById('switch_role').addEventListener('change', function() {
        // Submit the form when a new role is selected
        document.getElementById('roleForm').submit();
    });

    function toggleSubmenu(id) {
        let submenu = document.getElementById(id);
        submenu.classList.toggle("hidden");
    }
</script>