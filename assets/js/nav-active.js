document.addEventListener('DOMContentLoaded', () => {
  const sections = document.querySelectorAll('section[id]');
  const navLinks = document.querySelectorAll('.nav-links a');

  const normalizePath = (path) => {
    return path.replace(/\/+$/, '') || '/';
  };

  const currentPath = normalizePath(window.location.pathname);
  const currentHash = window.location.hash.replace('#', '');

  function getLinkInfo(link) {
    const href = link.getAttribute('href');
    if (!href) return null;

    try {
      const url = new URL(href, window.location.origin);
      return {
        path: normalizePath(url.pathname),
        hash: url.hash.replace('#', ''),
      };
    } catch (error) {
      return null;
    }
  }

  function setActiveLink() {
    let activeId = currentHash || null;
    let hasPathMatch = false;

    if (!activeId && currentPath === '/') {
      sections.forEach((section) => {
        const rect = section.getBoundingClientRect();
        if (rect.top <= 150 && rect.bottom > 150) {
          activeId = section.id;
        }
      });
    }

    navLinks.forEach((link) => {
      link.classList.remove('active');
      const info = getLinkInfo(link);
      if (!info) return;

      if (info.hash && info.hash === activeId) {
        link.classList.add('active');
        return;
      }

      if (currentPath !== '/' && info.path === currentPath) {
        link.classList.add('active');
        hasPathMatch = true;
        return;
      }

      if (currentPath !== '/' && info.path !== '/' && currentPath.startsWith(info.path)) {
        link.classList.add('active');
        hasPathMatch = true;
        return;
      }

      if (!hasPathMatch && currentPath === '/' && info.hash && info.hash === activeId) {
        link.classList.add('active');
      }
    });
  }

  setActiveLink();

  if (window.location.pathname === '/' || window.location.pathname === '') {
    window.addEventListener('scroll', setActiveLink);
  }
});