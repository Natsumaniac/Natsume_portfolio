document.addEventListener("DOMContentLoaded", () => {
  if (!window.gsap || !window.ScrollTrigger) return;

  gsap.registerPlugin(ScrollTrigger);

  const section = document.querySelector('.cert-section');
  const track = section?.querySelector('.cert-track');
  const cards = gsap.utils.toArray('.cert-card, .cert-button-card');

  if (!section || !track || cards.length === 0) return;

  const createAnimation = () => {
    const scrollAmount = Math.max(track.scrollWidth - window.innerWidth, 0);

    gsap.to(track, {
      x: -scrollAmount,
      ease: 'none',
      scrollTrigger: {
        trigger: section,
        start: 'top top',
        end: () => `+=${scrollAmount}`,
        scrub: 1,
        pin: true,
        anticipatePin: 1,
        invalidateOnRefresh: true,
      },
    });

    cards.forEach(( card, index) => {
      const isCenterCard = index % 3 === 1;
      const fromY = isCenterCard ? 0 : 70;
      const toY = isCenterCard ? -20 : -60;
      const fromScale = isCenterCard ? 1 : 0.97;

      gsap.fromTo(card, {
        y: fromY,
        scale: fromScale,
      }, {
        y: toY,
        scale: 1,
        ease: 'none',
        scrollTrigger: {
          trigger: section,
          start: 'top top',
          end: () => `+=${scrollAmount}`,
          scrub: 1,
          invalidateOnRefresh: true,
        },
      });
    });

    ScrollTrigger.create({
      trigger: section,
      start: 'top top',
      end: () => `+=${scrollAmount}`,
      scrub: 1,
      invalidateOnRefresh: true,
      onUpdate: () => {
        const centerX = window.innerWidth / 2;
        let closest = null;
        let minDistance = Infinity;

        cards.forEach(card => {
          const rect = card.getBoundingClientRect();
          const cardCenter = rect.left + rect.width / 2;
          const distance = Math.abs(cardCenter - centerX);
          if (distance < minDistance) {
            minDistance = distance;
            closest = card;
          }
        });

        cards.forEach(card => {
          card.classList.toggle('active-card', card === closest);
        });
      },
    });
  };

  createAnimation();
  ScrollTrigger.refresh();

  window.addEventListener('resize', () => {
    ScrollTrigger.refresh();
  });
});
