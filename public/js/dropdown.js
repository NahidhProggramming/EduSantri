
document.addEventListener('DOMContentLoaded', () => {
    // Cari semua elemen dropdown dengan class .dropdown
    const dropdownMenus = document.querySelectorAll('.nav-item.dropdown');

    dropdownMenus.forEach((menu) => {
      // Cari elemen toggle dan dropdown-menu di dalam dropdown
      const toggle = menu.querySelector('[data-toggle="dropdown"]');
      const dropdownMenu = menu.querySelector('.dropdown-menu');

      // Pastikan elemen ada
      if (toggle && dropdownMenu) {
        // Tambahkan event listener untuk toggle
        toggle.addEventListener('click', (e) => {
          e.preventDefault();

          // Toggle class aktif pada dropdown-menu
          if (dropdownMenu.style.display === 'block') {
            dropdownMenu.style.display = 'none';
          } else {
            // Sembunyikan semua dropdown-menu lainnya sebelum menampilkan yang baru
            document.querySelectorAll('.dropdown-menu').forEach((menu) => {
              menu.style.display = 'none';
            });

            dropdownMenu.style.display = 'block';
          }
        });
      }
    });

    // Klik di luar dropdown untuk menutup semua dropdown
    document.addEventListener('click', (e) => {
      if (!e.target.closest('.dropdown')) {
        document.querySelectorAll('.dropdown-menu').forEach((menu) => {
          menu.style.display = 'none';
        });
      }
    });
  });
