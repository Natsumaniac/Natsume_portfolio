document.addEventListener("DOMContentLoaded", () => {

  const sections = document.querySelectorAll("section");
  const navLinks = document.querySelectorAll(".nav-links a");

  function setActiveLink() {
    let current = "";

    // Check if we're on About page using URL
    const currentPath = window.location.pathname;
    const currentPage = window.location.href;
    
    if (currentPage.includes('/about/') || currentPath.includes('about') || document.body.classList.contains('page-template-about')) {
      current = "about";
    } else {
      sections.forEach(section => {
        const sectionTop = section.offsetTop - 150;
        const sectionHeight = section.offsetHeight;

        if (window.scrollY >= sectionTop && window.scrollY < sectionTop + sectionHeight) {
          current = section.getAttribute("id");
        }
      });
    }

    navLinks.forEach(link => {
      link.classList.remove("active");

      const href = link.getAttribute("href");

      if (href === `#${current}` || href === `/#${current}`) {
        link.classList.add("active");
      }
    });
  }

  // Set active link immediately on page load
  setActiveLink();
  
  // Update on scroll
  window.addEventListener("scroll", setActiveLink);

});