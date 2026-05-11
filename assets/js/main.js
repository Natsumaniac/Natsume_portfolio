/**
 * Dev Portfolio - Main JS
 */

document.addEventListener('DOMContentLoaded', () => {

    // ─── Smooth scroll for anchor links ─────────────────────────────
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', (e) => {
            const target = document.querySelector(anchor.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // ─── Scroll Reveal Animation ────────────────────────────────────
    const reveals = document.querySelectorAll(
        '.skill, .cert-item, .about-section, .skills-section, .cert-section, .cta-section'
    );

    if (reveals.length && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

        reveals.forEach(el => {
            // Don't apply reveal to sections that should be fully visible
            if (!el.classList.contains('about-section') && 
                !el.classList.contains('skills-section') && 
                !el.classList.contains('cert-section') && 
                !el.classList.contains('cta-section')) {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(el);
            }
        });
    }

    // ─── Skill Bar Animation ────────────────────────────────────────
    const bars = document.querySelectorAll('.skill-progress');
    if (bars.length && 'IntersectionObserver' in window) {
        const barObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const width = entry.target.style.width;
                    entry.target.style.width = '0%';
                    setTimeout(() => {
                        entry.target.style.width = width;
                    }, 200);
                    barObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.3 });

        bars.forEach(bar => {
            barObserver.observe(bar);
        });
    }

    // ─── Parallax Effect for Hero Section ───────────────────────────
    const hero = document.querySelector('.hero');
    if (hero) {
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const heroContent = hero.querySelector('.hero-content');
            if (heroContent) {
                heroContent.style.transform = `translateY(${scrolled * 0.5}px)`;
                heroContent.style.opacity = 1 - scrolled / 600;
            }
        });
    }

    // ─── Add hover effects to interactive elements ─────────────────────
    const interactiveElements = document.querySelectorAll('.btn-premium, .work-item, .cert-item');
    interactiveElements.forEach(el => {
        el.addEventListener('mouseenter', () => {
            el.style.transition = 'all 0.3s ease';
        });
    });

    console.log('Portfolio theme initialized with new design');
});
