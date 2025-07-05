document.addEventListener('DOMContentLoaded', () => {
    const dropdownItems = document.querySelectorAll('.sidebar-item.dropdown');

    dropdownItems.forEach((item) => {
      const toggle = item.querySelector('[data-toggle="dropdown"]');
      const dropdownMenu = item.querySelector('.dropdown-menu');

      if (toggle && dropdownMenu) {
        toggle.addEventListener('click', (e) => {
          e.preventDefault();

          // Tutup dropdown lainnya
          document.querySelectorAll('.dropdown-menu').forEach((menu) => {
            if (menu !== dropdownMenu) {
              menu.style.display = 'none';
            }
          });

          // Tampilkan atau sembunyikan dropdown saat ini
          dropdownMenu.style.display =
            dropdownMenu.style.display === 'block' ? 'none' : 'block';
        });
      }
    });

    // Tutup dropdown jika klik di luar dropdown
    document.addEventListener('click', (e) => {
      if (!e.target.closest('.sidebar-item.dropdown')) {
        document.querySelectorAll('.dropdown-menu').forEach((menu) => {
          menu.style.display = 'none';
        });
      }
    });
  });
