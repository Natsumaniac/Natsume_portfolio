document.addEventListener("DOMContentLoaded", () => {
  if (window.gsap && window.ScrollTrigger) {
    gsap.registerPlugin(ScrollTrigger);

    const section = document.querySelector('.cert-section');
    const track = section?.querySelector('.cert-track');
    const cards = gsap.utils.toArray('.cert-card, .cert-button-card');

    if (section && track && cards.length > 0) {
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

        cards.forEach((card, index) => {
          const isCenterCard = index % 3 === 1;
          const fromY = isCenterCard ? 0 : 70;
          const toY = isCenterCard ? -20 : -60;
          const fromScale = isCenterCard ? 1 : 0.97;

          gsap.fromTo(
            card,
            { y: fromY, scale: fromScale },
            {
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
            }
          );
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

            cards.forEach((card) => {
              const rect = card.getBoundingClientRect();
              const cardCenter = rect.left + rect.width / 2;
              const distance = Math.abs(cardCenter - centerX);
              if (distance < minDistance) {
                minDistance = distance;
                closest = card;
              }
            });

            cards.forEach((card) => {
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
    }
  }

  const orbitSection = document.querySelector('.certificate-vault-orbit');
  if (orbitSection) {
    initCertificateOrbit(orbitSection);
  }
});

function initCertificateOrbit(section) {
  const cards = Array.from(section.querySelectorAll('[data-orbit-card]'));
  const detailSection = document.querySelector('#certificate-details');
  const detailImage = detailSection?.querySelector('[data-orbit-detail-image]');
  const detailTitle = detailSection?.querySelector('[data-orbit-detail-title]');
  const detailIssuer = detailSection?.querySelector('[data-orbit-detail-issuer]');
  const detailDate = detailSection?.querySelector('[data-orbit-detail-date]');
  const detailCredential = detailSection?.querySelector('[data-orbit-detail-credential]');
  const detailDescription = detailSection?.querySelector('[data-orbit-detail-description]');
  const detailSkills = detailSection?.querySelector('[data-orbit-detail-skills]');
  const detailUrl = detailSection?.querySelector('[data-orbit-detail-url]');
  const track = section.querySelector('[data-certificate-track]');

  if (!track || cards.length === 0) {
    return;
  }

  const marqueeState = {
    x: 0,
    speed: 0.055,
    loopWidth: 0,
  };
  let paused = false;
  let activeCard = null;
  let hoveredCard = null;

  function formatText(text, fallback) {
    return text && text.trim() ? text.trim() : fallback;
  }

  function updateDetails(card) {
    if (!card) return;

    const imageUrl = card.dataset.image || '';
    if (detailImage) {
      detailImage.src = imageUrl;
      detailImage.alt = card.dataset.imageAlt || card.dataset.title || 'Certificate image';
      detailImage.style.display = imageUrl ? '' : 'none';
    }
    if (detailTitle) detailTitle.textContent = formatText(card.dataset.title, 'Selected Credential');
    if (detailIssuer) detailIssuer.textContent = formatText(card.dataset.issuer, 'Issuing organization unavailable');
    if (detailDate) detailDate.textContent = formatText(card.dataset.date, 'Date unavailable');
    if (detailCredential) detailCredential.textContent = formatText(card.dataset.credential, 'Not specified');
    if (detailDescription) detailDescription.textContent = formatText(card.dataset.details, 'Certificate details unavailable.');
    if (detailSkills) detailSkills.textContent = formatText(card.dataset.skills, 'Certificate metadata is recorded here.');
    if (detailUrl) {
      const rawUrl = (card.dataset.url || '').trim();
      if (rawUrl) {
        detailUrl.href = rawUrl;
        detailUrl.textContent = rawUrl;
        detailUrl.style.display = '';
      } else {
        detailUrl.removeAttribute('href');
        detailUrl.textContent = 'No verification URL available';
        detailUrl.style.display = 'inline';
      }
    }
  }

  function selectCard(card) {
    if (activeCard === card) return;
    cards.forEach((item) => item.classList.toggle('active-card', item === card));
    activeCard = card;
    updateDetails(card);
  }

  function openDetailsPanel() {
    if (!detailSection) return;
    detailSection.classList.add('is-open');
    detailSection.setAttribute('aria-hidden', 'false');
  }

  function closeDetailsPanel() {
    if (!detailSection) return;
    detailSection.classList.remove('is-open');
    detailSection.setAttribute('aria-hidden', 'true');
    paused = false;
  }

  cards.forEach((card, index) => {
    card.addEventListener('pointerenter', () => {
      hoveredCard = card;
      paused = true;
    });

    card.addEventListener('pointerleave', () => {
      if (hoveredCard === card) {
        hoveredCard = null;
      }
      paused = false;
    });

    card.addEventListener('click', (event) => {
      event.preventDefault();
      selectCard(card);
      openDetailsPanel();
      detailSection?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });

  const closeButton = detailSection?.querySelector('[data-orbit-details-close]');
  closeButton?.addEventListener('click', () => {
    closeDetailsPanel();
    section?.scrollIntoView({ behavior: 'smooth', block: 'start' });
  });

  function measure() {
    marqueeState.loopWidth = track.scrollWidth / 2;
    if (!Number.isFinite(marqueeState.loopWidth) || marqueeState.loopWidth <= 0) {
      marqueeState.loopWidth = 1;
    }
    marqueeState.x = marqueeState.x % marqueeState.loopWidth;
  }

  measure();

  let lastFrame = performance.now();

  function animateMarquee(now = performance.now()) {
    const delta = Math.min(34, now - lastFrame);
    lastFrame = now;

    if (!paused) {
      marqueeState.x -= marqueeState.speed * delta;
      if (marqueeState.x <= -marqueeState.loopWidth) {
        marqueeState.x += marqueeState.loopWidth;
      }
    }

    track.style.transform = `translate3d(${marqueeState.x}px, 0, 0)`;

    requestAnimationFrame(animateMarquee);
  }

  window.addEventListener('resize', () => {
    measure();
  });

  selectCard(cards[0]);
  animateMarquee();
}
