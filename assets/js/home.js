/**
 * Portfolio Scroll Animations
 * Premium cinematic animations for each section
 * Only animates inner content, NOT section containers
 * Uses Intersection Observer for viewport-based triggers
 */

document.addEventListener('DOMContentLoaded', function() {
  
  // Only initialize if GSAP is available
  if (typeof gsap === 'undefined') {
    console.warn('GSAP not loaded');
    return;
  }

  // Track section visibility state so animations replay on re-entry
  const visibilityMap = new WeakMap();

  // Intersection Observer configuration
  const observerOptions = {
    root: null,
    rootMargin: '0px',
    threshold: 0.12 // Trigger when roughly 12% of content is visible
  };

  // Create Intersection Observer
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      const target = entry.target;
      const sectionId = target.id;
      const isVisible = entry.isIntersecting && entry.intersectionRatio >= 0.12;
      const wasVisible = visibilityMap.get(target) || false;

      if (isVisible && !wasVisible) {
        visibilityMap.set(target, true);
        animateSection(sectionId);
      } else if (!isVisible && wasVisible) {
        visibilityMap.set(target, false);
        resetSection(sectionId);
      }
    });
  }, observerOptions);

  function animateSection(sectionId) {
    switch(sectionId) {
      case 'hero':
        animateHeroSection();
        break;
      case 'about':
        animateAboutSection();
        break;
      case 'works':
        animateWorksSection();
        break;
      case 'skills':
        animateSkillsSection();
        break;
      case 'certificates':
        animateCertificatesSection();
        break;
      case 'cta':
        animateCtaSection();
        break;
      case 'about-hero':
        animateAboutHeroSection();
        break;
      case 'about-intro':
        animateAboutIntroSection();
        break;
      case 'identity':
        animateIdentitySection();
        break;
      case 'drives':
        animateDrivesSection();
        break;
      case 'beyond':
        animateBeyondSection();
        break;
      case 'quote':
        animateQuoteSection();
        break;
      case 'works-archive':
        animateWorksArchivePage();
        break;
      case 'single-work-page':
        animateSingleWorkPage();
        break;
    }
  }

  function resetSection(sectionId) {
    switch(sectionId) {
      case 'hero':
        resetHeroSection();
        break;
      case 'about':
        resetAboutSection();
        break;
      case 'works':
        resetWorksSection();
        break;
      case 'skills':
        resetSkillsSection();
        break;
      case 'certificates':
        resetCertificatesSection();
        break;
      case 'cta':
        resetCtaSection();
        break;
      case 'about-hero':
        resetAboutHeroSection();
        break;
      case 'about-intro':
        resetAboutIntroSection();
        break;
      case 'identity':
        resetIdentitySection();
        break;
      case 'drives':
        resetDrivesSection();
        break;
      case 'beyond':
        resetBeyondSection();
        break;
      case 'quote':
        resetQuoteSection();
        break;
      case 'works-archive':
        resetWorksArchivePage();
        break;
      case 'single-work-page':
        resetSingleWorkPage();
        break;
    }
  }

  // ────────────────────────────────────────
  // HERO SECTION ANIMATION
  // ────────────────────────────────────────
  function animateHeroSection() {
    const heroContent = document.querySelector('.hero-content');
    if (!heroContent) return;

    const h1 = heroContent.querySelector('h1');
    const nameLine = heroContent.querySelector('.name-line');
    const description = heroContent.querySelector('p');
    const buttons = heroContent.querySelectorAll('.hero-buttons .btn-premium');

    // Ensure elements exist and are visible by default
    if (h1) gsap.set(h1, { opacity: 0, y: 30 });
    if (nameLine) gsap.set(nameLine, { opacity: 0, y: 30 });
    if (description) gsap.set(description, { opacity: 0, y: 30 });
    gsap.set(buttons, { opacity: 0, y: 30 });

    // Animate hero content
    const heroTimeline = gsap.timeline({
      defaults: { duration: 0.8, ease: 'power2.out' }
    });

    if (h1) heroTimeline.to(h1, { opacity: 1, y: 0 }, 0);
    if (nameLine) heroTimeline.to(nameLine, { opacity: 1, y: 0 }, 0.15);
    if (description) heroTimeline.to(description, { opacity: 1, y: 0 }, 0.3);
    heroTimeline.to(buttons, { opacity: 1, y: 0, stagger: 0.12 }, 0.55);
  }

  function resetHeroSection() {
    const heroContent = document.querySelector('.hero-content');
    if (!heroContent) return;

    const h1 = heroContent.querySelector('h1');
    const nameLine = heroContent.querySelector('.name-line');
    const description = heroContent.querySelector('p');
    const buttons = heroContent.querySelectorAll('.hero-buttons .btn-premium');

    gsap.killTweensOf([h1, nameLine, description, ...buttons]);

    if (h1) gsap.set(h1, { opacity: 0, y: 30 });
    if (nameLine) gsap.set(nameLine, { opacity: 0, y: 30 });
    if (description) gsap.set(description, { opacity: 0, y: 30 });
    gsap.set(buttons, { opacity: 0, y: 30 });
  }

  // ────────────────────────────────────────
  // ABOUT SECTION ANIMATION
  // ────────────────────────────────────────
  function animateAboutSection() {
    const aboutSection = document.querySelector('.about-section');
    if (!aboutSection) return;

    const aboutImage = aboutSection.querySelector('.about-image');
    const aboutTitle = aboutSection.querySelector('.about-title');
    const aboutText = aboutSection.querySelector('.about-text');
    const aboutButtons = aboutSection.querySelector('.about-buttons');

    // Ensure about-section container stays visible
    gsap.set(aboutSection, { opacity: 1, visibility: 'visible' });

    // Set initial state for content elements to animate
    if (aboutImage) gsap.set(aboutImage, { opacity: 0, y: 40, filter: 'blur(8px)' });
    if (aboutTitle) gsap.set(aboutTitle, { opacity: 0, y: 40, filter: 'blur(8px)' });
    if (aboutText) gsap.set(aboutText, { opacity: 0, y: 40, filter: 'blur(8px)' });
    if (aboutButtons) gsap.set(aboutButtons, { opacity: 0, y: 40, filter: 'blur(8px)' });

    const aboutTimeline = gsap.timeline({
      defaults: { duration: 0.9, ease: 'power2.out' }
    });

    if (aboutImage) aboutTimeline.to(aboutImage, { opacity: 1, y: 0, filter: 'blur(0px)' }, 0);
    if (aboutTitle) aboutTimeline.to(aboutTitle, { opacity: 1, y: 0, filter: 'blur(0px)' }, 0.15);
    if (aboutText) aboutTimeline.to(aboutText, { opacity: 1, y: 0, filter: 'blur(0px)' }, 0.25);
    if (aboutButtons) aboutTimeline.to(aboutButtons, { opacity: 1, y: 0, filter: 'blur(0px)' }, 0.4);
  }

  function resetAboutSection() {
    const aboutSection = document.querySelector('.about-section');
    if (!aboutSection) return;

    const aboutImage = aboutSection.querySelector('.about-image');
    const aboutTitle = aboutSection.querySelector('.about-title');
    const aboutText = aboutSection.querySelector('.about-text');
    const aboutButtons = aboutSection.querySelector('.about-buttons');

    gsap.killTweensOf([aboutImage, aboutTitle, aboutText, aboutButtons]);

    if (aboutImage) gsap.set(aboutImage, { opacity: 0, y: 40, filter: 'blur(8px)' });
    if (aboutTitle) gsap.set(aboutTitle, { opacity: 0, y: 40, filter: 'blur(8px)' });
    if (aboutText) gsap.set(aboutText, { opacity: 0, y: 40, filter: 'blur(8px)' });
    if (aboutButtons) gsap.set(aboutButtons, { opacity: 0, y: 40, filter: 'blur(8px)' });
  }

  function animateAboutHeroSection() {
    const heroSection = document.querySelector('.about-hero');
    if (!heroSection) return;

    const leftBox = heroSection.querySelector('.left-content .content-box');
    const rightBox = heroSection.querySelector('.right-content .content-box');

    if (leftBox) gsap.set(leftBox, { opacity: 0, y: 40, filter: 'blur(8px)' });
    if (rightBox) gsap.set(rightBox, { opacity: 0, y: 40, filter: 'blur(8px)' });

    const heroTimeline = gsap.timeline({ defaults: { duration: 0.85, ease: 'power2.out' } });
    if (leftBox) heroTimeline.to(leftBox, { opacity: 1, y: 0, filter: 'blur(0px)' }, 0);
    if (rightBox) heroTimeline.to(rightBox, { opacity: 1, y: 0, filter: 'blur(0px)' }, 0.15);
  }

  function resetAboutHeroSection() {
    const heroSection = document.querySelector('.about-hero');
    if (!heroSection) return;

    const leftBox = heroSection.querySelector('.left-content .content-box');
    const rightBox = heroSection.querySelector('.right-content .content-box');

    gsap.killTweensOf([leftBox, rightBox]);

    if (leftBox) gsap.set(leftBox, { opacity: 0, y: 40, filter: 'blur(8px)' });
    if (rightBox) gsap.set(rightBox, { opacity: 0, y: 40, filter: 'blur(8px)' });
  }

  function animateAboutIntroSection() {
    const introSection = document.querySelector('.about-intro-section');
    if (!introSection) return;

    const header = introSection.querySelector('.about-intro-header');
    const leftPanel = introSection.querySelector('.about-intro-left');
    const rightPanel = introSection.querySelector('.about-intro-right');

    if (header) gsap.set(header, { opacity: 0, y: 30, filter: 'blur(6px)' });
    if (leftPanel) gsap.set(leftPanel, { opacity: 0, y: 40, filter: 'blur(8px)' });
    if (rightPanel) gsap.set(rightPanel, { opacity: 0, y: 40, filter: 'blur(8px)' });

    const introTimeline = gsap.timeline({ defaults: { duration: 0.8, ease: 'power2.out' } });
    if (header) introTimeline.to(header, { opacity: 1, y: 0, filter: 'blur(0px)' }, 0);
    if (leftPanel) introTimeline.to(leftPanel, { opacity: 1, y: 0, filter: 'blur(0px)' }, 0.18);
    if (rightPanel) introTimeline.to(rightPanel, { opacity: 1, y: 0, filter: 'blur(0px)' }, 0.32);
  }

  function resetAboutIntroSection() {
    const introSection = document.querySelector('.about-intro-section');
    if (!introSection) return;

    const header = introSection.querySelector('.about-intro-header');
    const leftPanel = introSection.querySelector('.about-intro-left');
    const rightPanel = introSection.querySelector('.about-intro-right');

    gsap.killTweensOf([header, leftPanel, rightPanel]);

    if (header) gsap.set(header, { opacity: 0, y: 30, filter: 'blur(6px)' });
    if (leftPanel) gsap.set(leftPanel, { opacity: 0, y: 40, filter: 'blur(8px)' });
    if (rightPanel) gsap.set(rightPanel, { opacity: 0, y: 40, filter: 'blur(8px)' });
  }

  function animateIdentitySection() {
    const identitySection = document.querySelector('.identity-section');
    if (!identitySection) return;

    const overlay = identitySection.querySelector('.identity-overlay');
    const leftCards = identitySection.querySelectorAll('.identity-left .identity-card');
    const rightCards = identitySection.querySelectorAll('.identity-right .identity-card');

    if (overlay) gsap.set(overlay, { opacity: 0, scale: 1.02 });
    if (leftCards.length) gsap.set(leftCards, { opacity: 0, y: 40, filter: 'blur(8px)' });
    if (rightCards.length) gsap.set(rightCards, { opacity: 0, y: 40, filter: 'blur(8px)' });

    const identityTimeline = gsap.timeline({ defaults: { duration: 0.85, ease: 'power2.out' } });
    if (overlay) identityTimeline.to(overlay, { opacity: 1, scale: 1, ease: 'none' }, 0);
    identityTimeline.to(leftCards, { opacity: 1, y: 0, filter: 'blur(0px)', stagger: 0.08 }, 0.1);
    identityTimeline.to(rightCards, { opacity: 1, y: 0, filter: 'blur(0px)', stagger: 0.08 }, 0.18);
  }

  function resetIdentitySection() {
    const identitySection = document.querySelector('.identity-section');
    if (!identitySection) return;

    const overlay = identitySection.querySelector('.identity-overlay');
    const leftCards = identitySection.querySelectorAll('.identity-left .identity-card');
    const rightCards = identitySection.querySelectorAll('.identity-right .identity-card');

    gsap.killTweensOf([overlay, ...leftCards, ...rightCards]);

    if (overlay) gsap.set(overlay, { opacity: 0, scale: 1.02 });
    if (leftCards.length) gsap.set(leftCards, { opacity: 0, y: 40, filter: 'blur(8px)' });
    if (rightCards.length) gsap.set(rightCards, { opacity: 0, y: 40, filter: 'blur(8px)' });
  }

  function animateDrivesSection() {
    const drivesSection = document.querySelector('.drives-section');
    if (!drivesSection) return;

    const title = drivesSection.querySelector('.drives-title');
    const cards = drivesSection.querySelectorAll('.drive-card');

    if (title) gsap.set(title, { opacity: 0, y: 30, filter: 'blur(6px)' });
    if (cards.length) gsap.set(cards, { opacity: 0, y: 40, filter: 'blur(8px)' });

    const drivesTimeline = gsap.timeline({ defaults: { duration: 0.8, ease: 'power2.out' } });
    if (title) drivesTimeline.to(title, { opacity: 1, y: 0, filter: 'blur(0px)' }, 0);
    drivesTimeline.to(cards, { opacity: 1, y: 0, filter: 'blur(0px)', stagger: 0.08 }, 0.2);
  }

  function resetDrivesSection() {
    const drivesSection = document.querySelector('.drives-section');
    if (!drivesSection) return;

    const title = drivesSection.querySelector('.drives-title');
    const cards = drivesSection.querySelectorAll('.drive-card');

    gsap.killTweensOf([title, ...cards]);

    if (title) gsap.set(title, { opacity: 0, y: 30, filter: 'blur(6px)' });
    if (cards.length) gsap.set(cards, { opacity: 0, y: 40, filter: 'blur(8px)' });
  }

  function animateBeyondSection() {
    const beyondSection = document.querySelector('.beyond-section');
    if (!beyondSection) return;

    const title = beyondSection.querySelector('.beyond-title');
    const leftPanel = beyondSection.querySelector('.beyond-left');
    const cards = beyondSection.querySelectorAll('.beyond-card');

    if (title) gsap.set(title, { opacity: 0, y: 30, filter: 'blur(6px)' });
    if (leftPanel) gsap.set(leftPanel, { opacity: 0, y: 40, filter: 'blur(8px)' });
    if (cards.length) gsap.set(cards, { opacity: 0, y: 40, filter: 'blur(8px)' });

    const beyondTimeline = gsap.timeline({ defaults: { duration: 0.85, ease: 'power2.out' } });
    if (title) beyondTimeline.to(title, { opacity: 1, y: 0, filter: 'blur(0px)' }, 0);
    if (leftPanel) beyondTimeline.to(leftPanel, { opacity: 1, y: 0, filter: 'blur(0px)' }, 0.18);
    beyondTimeline.to(cards, { opacity: 1, y: 0, filter: 'blur(0px)', stagger: 0.06 }, 0.28);
  }

  function resetBeyondSection() {
    const beyondSection = document.querySelector('.beyond-section');
    if (!beyondSection) return;

    const title = beyondSection.querySelector('.beyond-title');
    const leftPanel = beyondSection.querySelector('.beyond-left');
    const cards = beyondSection.querySelectorAll('.beyond-card');

    gsap.killTweensOf([title, leftPanel, ...cards]);

    if (title) gsap.set(title, { opacity: 0, y: 30, filter: 'blur(6px)' });
    if (leftPanel) gsap.set(leftPanel, { opacity: 0, y: 40, filter: 'blur(8px)' });
    if (cards.length) gsap.set(cards, { opacity: 0, y: 40, filter: 'blur(8px)' });
  }

  function animateQuoteSection() {
    const quoteSection = document.querySelector('.quote-section');
    if (!quoteSection) return;

    const quoteBox = quoteSection.querySelector('.quote-container');
    const quoteText = quoteSection.querySelector('.quote-text');
    const author = quoteSection.querySelector('.quote-author');

    if (quoteBox) gsap.set(quoteBox, { opacity: 0, y: 40, filter: 'blur(8px)' });
    if (quoteText) gsap.set(quoteText, { opacity: 0, y: 40, filter: 'blur(8px)' });
    if (author) gsap.set(author, { opacity: 0, y: 40, filter: 'blur(8px)' });

    const quoteTimeline = gsap.timeline({ defaults: { duration: 0.85, ease: 'power2.out' } });
    if (quoteBox) quoteTimeline.to(quoteBox, { opacity: 1, y: 0, filter: 'blur(0px)' }, 0);
    if (quoteText) quoteTimeline.to(quoteText, { opacity: 1, y: 0, filter: 'blur(0px)' }, 0.15);
    if (author) quoteTimeline.to(author, { opacity: 1, y: 0, filter: 'blur(0px)' }, 0.25);
  }

  function resetQuoteSection() {
    const quoteSection = document.querySelector('.quote-section');
    if (!quoteSection) return;

    const quoteBox = quoteSection.querySelector('.quote-container');
    const quoteText = quoteSection.querySelector('.quote-text');
    const author = quoteSection.querySelector('.quote-author');

    gsap.killTweensOf([quoteBox, quoteText, author]);

    if (quoteBox) gsap.set(quoteBox, { opacity: 0, y: 40, filter: 'blur(8px)' });
    if (quoteText) gsap.set(quoteText, { opacity: 0, y: 40, filter: 'blur(8px)' });
    if (author) gsap.set(author, { opacity: 0, y: 40, filter: 'blur(8px)' });
  }

  // ────────────────────────────────────────
  // WORKS SECTION ANIMATION (MOST IMPORTANT)
  // ────────────────────────────────────────
  function animateWorksSection() {
    const worksSection = document.querySelector('.works-section');
    if (!worksSection) return;

    // Ensure section container is visible
    gsap.set(worksSection, { opacity: 1, visibility: 'visible' });

    const title = worksSection.querySelector('.works-title');
    const workItems = worksSection.querySelectorAll('.work-item');
    const navButtons = worksSection.querySelectorAll('.nav-btn');
    const worksBtn = worksSection.querySelector('.works-btn');

    if (workItems.length === 0) return;

    // Capture class-driven final styles from work.js before intro animation.
    const finalStyles = Array.from(workItems).map((item) => {
      const computed = window.getComputedStyle(item);
      return {
        transform: computed.transform,
        opacity: computed.opacity,
        filter: computed.filter,
        zIndex: computed.zIndex
      };
    });

    // Set initial state - cards start stacked at center
    gsap.set(workItems, {
      opacity: 0,
      scale: 0.7,
      rotation: () => gsap.utils.random(-8, 8),
      x: 0,
      y: 0,
      filter: 'blur(0px)',
      zIndex: 6
    });

    if (title) gsap.set(title, { opacity: 0, y: -40 });
    gsap.set(navButtons, { opacity: 0 });
    if (worksBtn) gsap.set(worksBtn, { opacity: 0 });

    const worksTimeline = gsap.timeline({
      onComplete: () => {
        // Return control to carousel class logic and keep cards visible.
        gsap.set(workItems, { clearProps: 'transform,opacity,filter,zIndex' });
      }
    });

    // Animate title
    if (title) {
      worksTimeline.to(title, { 
        opacity: 1, 
        y: 0, 
        duration: 0.7, 
        ease: 'power2.out' 
      }, 0);
    }

    // Animate cards: deal/shuffle effect
    const cardAnimations = Array.from(workItems).map((item, index) => {
      const delay = index * 0.1;
      const finalState = finalStyles[index];

      return {
        targets: item,
        opacity: Number(finalState.opacity),
        transform: finalState.transform,
        filter: finalState.filter,
        zIndex: Number(finalState.zIndex) || 1,
        duration: 0.9,
        delay: delay + 0.2,
        ease: 'power3.out'
      };
    });

    // Add all card animations to timeline
    cardAnimations.forEach(anim => {
      worksTimeline.to(anim.targets, {
        opacity: anim.opacity,
        transform: anim.transform,
        filter: anim.filter,
        zIndex: anim.zIndex,
        duration: anim.duration,
        ease: anim.ease
      }, anim.delay);
    });

    // Animate nav buttons
    worksTimeline.to(navButtons, { 
      opacity: 1, 
      duration: 0.6, 
      ease: 'power2.out' 
    }, 1.5);

    // Animate works button
    if (worksBtn) {
      worksTimeline.to(worksBtn, { 
        opacity: 1, 
        duration: 0.6, 
        ease: 'power2.out' 
      }, 1.5);
    }
  }

  function resetWorksSection() {
    const worksSection = document.querySelector('.works-section');
    if (!worksSection) return;

    const title = worksSection.querySelector('.works-title');
    const workItems = worksSection.querySelectorAll('.work-item');
    const navButtons = worksSection.querySelectorAll('.nav-btn');
    const worksBtn = worksSection.querySelector('.works-btn');

    gsap.killTweensOf([title, ...workItems, ...navButtons, worksBtn]);

    gsap.set(workItems, {
      opacity: 0,
      scale: 0.7,
      rotation: 0,
      x: 0,
      y: 0,
      filter: 'blur(0px)',
      zIndex: 6
    });

    if (title) gsap.set(title, { opacity: 0, y: -40 });
    gsap.set(navButtons, { opacity: 0 });
    if (worksBtn) gsap.set(worksBtn, { opacity: 0 });
  }

  // ────────────────────────────────────────
  // SKILLS SECTION ANIMATION
  // ────────────────────────────────────────
  function animateSkillsSection() {
    const skillsSection = document.querySelector('.skills-section');
    if (!skillsSection) return;

    // Ensure section container is visible
    gsap.set(skillsSection, { opacity: 1, visibility: 'visible' });

    const skillsTitle = skillsSection.querySelector('.skills-title');
    const skillsImage = skillsSection.querySelector('.skills-image');
    const skillBars = skillsSection.querySelectorAll('.skill-progress');
    const skillsBtn = skillsSection.querySelector('.skills-btn');

    // Store each bar's final width before resetting to 0%.
    const skillTargets = Array.from(skillBars).map((bar) => {
      return bar.dataset.width || bar.style.width || '0%';
    });

    // Set initial state for animatable elements
    if (skillsTitle) gsap.set(skillsTitle, { opacity: 0, y: -40 });
    if (skillsImage) gsap.set(skillsImage, { opacity: 0, x: -50, filter: 'blur(8px)' });
    gsap.set(skillBars, { width: '0%' });
    if (skillsBtn) gsap.set(skillsBtn, { opacity: 0, scale: 0.9 });

    const skillsTimeline = gsap.timeline({});

    // Animate title and image
    if (skillsTitle) {
      skillsTimeline.to(skillsTitle, { 
        opacity: 1, 
        y: 0, 
        duration: 0.7, 
        ease: 'power2.out' 
      }, 0);
    }

    if (skillsImage) {
      skillsTimeline.to(skillsImage, { 
        opacity: 1, 
        x: 0, 
        filter: 'blur(0px)',
        duration: 0.8, 
        ease: 'power2.out' 
      }, 0.2);
    }

    // Animate progress bars one by one
    skillBars.forEach((bar, index) => {
      const finalWidth = skillTargets[index];
      skillsTimeline.to(bar, {
        width: finalWidth,
        duration: 1.2,
        ease: 'power3.inOut'
      }, 0.6 + index * 0.1);
    });

    // Animate button
    if (skillsBtn) {
      skillsTimeline.to(skillsBtn, { 
        opacity: 1, 
        scale: 1,
        duration: 0.6, 
        ease: 'back.out' 
      }, 1.8);
    }
  }

  function resetSkillsSection() {
    const skillsSection = document.querySelector('.skills-section');
    if (!skillsSection) return;

    const skillsTitle = skillsSection.querySelector('.skills-title');
    const skillsImage = skillsSection.querySelector('.skills-image');
    const skillBars = skillsSection.querySelectorAll('.skill-progress');
    const skillsBtn = skillsSection.querySelector('.skills-btn');

    gsap.killTweensOf([skillsTitle, skillsImage, ...skillBars, skillsBtn]);

    if (skillsTitle) gsap.set(skillsTitle, { opacity: 0, y: -40 });
    if (skillsImage) gsap.set(skillsImage, { opacity: 0, x: -50, filter: 'blur(8px)' });
    gsap.set(skillBars, { width: '0%' });
    if (skillsBtn) gsap.set(skillsBtn, { opacity: 0, scale: 0.9 });
  }

  // ────────────────────────────────────────
  // CERTIFICATES SECTION ANIMATION
  // ────────────────────────────────────────
  function animateCertificatesSection() {
    const certSection = document.querySelector('.cert-section');
    if (!certSection) return;

    // Ensure section container is visible
    gsap.set(certSection, { opacity: 1, visibility: 'visible' });

    const certTitle = certSection.querySelector('.cert-title');
    const certCards = certSection.querySelectorAll('.cert-card');
    const certButtonCard = certSection.querySelector('.cert-button-card');

    // Set initial state for animatable elements
    if (certTitle) gsap.set(certTitle, { opacity: 0, y: -40 });
    gsap.set(certCards, { opacity: 0, y: 40, filter: 'blur(6px)' });
    if (certButtonCard) gsap.set(certButtonCard, { opacity: 0, y: 40 });

    const certTimeline = gsap.timeline({});

    // Animate title
    if (certTitle) {
      certTimeline.to(certTitle, { 
        opacity: 1, 
        y: 0, 
        duration: 0.7, 
        ease: 'power2.out' 
      }, 0);
    }

    // Animate certificate cards with stagger
    certTimeline.to(certCards, {
      opacity: 1,
      y: 0,
      filter: 'blur(0px)',
      duration: 0.8,
      stagger: 0.08,
      ease: 'power2.out'
    }, 0.3);

    // Animate button card
    if (certButtonCard) {
      certTimeline.to(certButtonCard, { 
        opacity: 1, 
        y: 0, 
        duration: 0.8, 
        ease: 'power2.out' 
      }, 0.5);
    }
  }

  function resetCertificatesSection() {
    const certSection = document.querySelector('.cert-section');
    if (!certSection) return;

    const certTitle = certSection.querySelector('.cert-title');
    const certCards = certSection.querySelectorAll('.cert-card');
    const certButtonCard = certSection.querySelector('.cert-button-card');

    gsap.killTweensOf([certTitle, ...certCards, certButtonCard]);

    if (certTitle) gsap.set(certTitle, { opacity: 0, y: -40 });
    gsap.set(certCards, { opacity: 0, y: 40, filter: 'blur(6px)' });
    if (certButtonCard) gsap.set(certButtonCard, { opacity: 0, y: 40 });
  }

  // ────────────────────────────────────────
  // CTA SECTION ANIMATION
  // ────────────────────────────────────────
  function animateCtaSection() {
    const ctaSection = document.querySelector('.cta-section');
    if (!ctaSection) return;

    // Ensure section container is visible
    gsap.set(ctaSection, { opacity: 1, visibility: 'visible' });

    const ctaHead = ctaSection.querySelector('.cta-head');
    const ctaContent = ctaSection.querySelector('.cta-content');
    const ctaTitle = ctaContent ? ctaContent.querySelector('h2') : null;
    const ctaDescription = ctaContent ? ctaContent.querySelector('p') : null;
    const ctaButton = ctaContent ? ctaContent.querySelector('.cta-btn') : null;

    // Set initial state for animatable elements
    if (ctaHead) gsap.set(ctaHead, { opacity: 0, scale: 0.8, filter: 'blur(10px)' });
    if (ctaTitle) gsap.set(ctaTitle, { opacity: 0, y: 40, filter: 'blur(8px)' });
    if (ctaDescription) gsap.set(ctaDescription, { opacity: 0, y: 40, filter: 'blur(8px)' });
    if (ctaButton) gsap.set(ctaButton, { opacity: 0, scale: 0.9 });

    const ctaTimeline = gsap.timeline({});

    // Animate floating head with subtle glow
    if (ctaHead) {
      ctaTimeline.to(ctaHead, { 
        opacity: 1, 
        scale: 1, 
        filter: 'blur(0px)',
        duration: 0.9, 
        ease: 'power2.out' 
      }, 0);
    }

    // Animate title
    if (ctaTitle) {
      ctaTimeline.to(ctaTitle, { 
        opacity: 1, 
        y: 0, 
        filter: 'blur(0px)',
        duration: 0.8, 
        ease: 'power2.out' 
      }, 0.2);
    }

    // Animate description
    if (ctaDescription) {
      ctaTimeline.to(ctaDescription, { 
        opacity: 1, 
        y: 0, 
        filter: 'blur(0px)',
        duration: 0.8, 
        ease: 'power2.out' 
      }, 0.35);
    }

    // Animate button with glow effect
    if (ctaButton) {
      ctaTimeline.to(ctaButton, { 
        opacity: 1, 
        scale: 1,
        duration: 0.7, 
        ease: 'back.out' 
      }, 0.5);
    }
  }

  function resetCtaSection() {
    const ctaSection = document.querySelector('.cta-section');
    if (!ctaSection) return;

    const ctaHead = ctaSection.querySelector('.cta-head');
    const ctaContent = ctaSection.querySelector('.cta-content');
    const ctaTitle = ctaContent ? ctaContent.querySelector('h2') : null;
    const ctaDescription = ctaContent ? ctaContent.querySelector('p') : null;
    const ctaButton = ctaContent ? ctaContent.querySelector('.cta-btn') : null;

    gsap.killTweensOf([ctaHead, ctaTitle, ctaDescription, ctaButton]);

    if (ctaHead) gsap.set(ctaHead, { opacity: 0, scale: 0.8, filter: 'blur(10px)' });
    if (ctaTitle) gsap.set(ctaTitle, { opacity: 0, y: 40, filter: 'blur(8px)' });
    if (ctaDescription) gsap.set(ctaDescription, { opacity: 0, y: 40, filter: 'blur(8px)' });
    if (ctaButton) gsap.set(ctaButton, { opacity: 0, scale: 0.9 });
  }

  function animateWorksArchivePage() {
    const archiveSection = document.querySelector('.works-archive');
    if (!archiveSection) return;

    const topbar = archiveSection.querySelector('.works-topbar');
    const cards = archiveSection.querySelectorAll('.work-card');
    const pagination = archiveSection.querySelector('.works-pagination');
    const emptyState = archiveSection.querySelector('.works-empty');

    if (topbar) gsap.set(topbar, { opacity: 0, y: 28, filter: 'blur(6px)' });
    gsap.set(cards, { opacity: 0, y: 28, filter: 'blur(6px)' });
    if (pagination) gsap.set(pagination, { opacity: 0, y: 24 });
    if (emptyState) gsap.set(emptyState, { opacity: 0, y: 24 });

    const archiveTimeline = gsap.timeline({ defaults: { duration: 0.75, ease: 'power2.out' } });

    if (topbar) archiveTimeline.to(topbar, { opacity: 1, y: 0, filter: 'blur(0px)' }, 0);
    archiveTimeline.to(cards, { opacity: 1, y: 0, filter: 'blur(0px)', stagger: 0.06 }, 0.15);
    if (pagination) archiveTimeline.to(pagination, { opacity: 1, y: 0 }, 0.35);
    if (emptyState) archiveTimeline.to(emptyState, { opacity: 1, y: 0 }, 0.25);
  }

  function resetWorksArchivePage() {
    const archiveSection = document.querySelector('.works-archive');
    if (!archiveSection) return;

    const topbar = archiveSection.querySelector('.works-topbar');
    const cards = archiveSection.querySelectorAll('.work-card');
    const pagination = archiveSection.querySelector('.works-pagination');
    const emptyState = archiveSection.querySelector('.works-empty');

    gsap.killTweensOf([topbar, ...cards, pagination, emptyState]);

    if (topbar) gsap.set(topbar, { opacity: 0, y: 28, filter: 'blur(6px)' });
    gsap.set(cards, { opacity: 0, y: 28, filter: 'blur(6px)' });
    if (pagination) gsap.set(pagination, { opacity: 0, y: 24 });
    if (emptyState) gsap.set(emptyState, { opacity: 0, y: 24 });
  }

  function animateSingleWorkPage() {
    const singlePage = document.querySelector('.single-work-page');
    if (!singlePage) return;

    const topbar = singlePage.querySelector('.single-work-topbar');
    const mainCard = singlePage.querySelector('.single-work__primary-card');
    const sections = singlePage.querySelectorAll('.single-work__section');
    const sidebarPanel = singlePage.querySelector('.single-work__sidebar .sidebar-panel');

    if (topbar) gsap.set(topbar, { opacity: 0, y: 24, filter: 'blur(6px)' });
    if (mainCard) gsap.set(mainCard, { opacity: 0, y: 30, filter: 'blur(6px)' });
    gsap.set(sections, { opacity: 0, y: 28, filter: 'blur(6px)' });
    if (sidebarPanel) gsap.set(sidebarPanel, { opacity: 0, y: 30, filter: 'blur(6px)' });

    const singleTimeline = gsap.timeline({ defaults: { duration: 0.8, ease: 'power2.out' } });

    if (topbar) singleTimeline.to(topbar, { opacity: 1, y: 0, filter: 'blur(0px)' }, 0);
    if (mainCard) singleTimeline.to(mainCard, { opacity: 1, y: 0, filter: 'blur(0px)' }, 0.08);
    singleTimeline.to(sections, { opacity: 1, y: 0, filter: 'blur(0px)', stagger: 0.12 }, 0.2);
    if (sidebarPanel) singleTimeline.to(sidebarPanel, { opacity: 1, y: 0, filter: 'blur(0px)' }, 0.25);
  }

  function resetSingleWorkPage() {
    const singlePage = document.querySelector('.single-work-page');
    if (!singlePage) return;

    const topbar = singlePage.querySelector('.single-work-topbar');
    const mainCard = singlePage.querySelector('.single-work__primary-card');
    const sections = singlePage.querySelectorAll('.single-work__section');
    const sidebarPanel = singlePage.querySelector('.single-work__sidebar .sidebar-panel');

    gsap.killTweensOf([topbar, mainCard, ...sections, sidebarPanel]);

    if (topbar) gsap.set(topbar, { opacity: 0, y: 24, filter: 'blur(6px)' });
    if (mainCard) gsap.set(mainCard, { opacity: 0, y: 30, filter: 'blur(6px)' });
    gsap.set(sections, { opacity: 0, y: 28, filter: 'blur(6px)' });
    if (sidebarPanel) gsap.set(sidebarPanel, { opacity: 0, y: 30, filter: 'blur(6px)' });
  }

  // ────────────────────────────────────────
  // INITIALIZE ALL ANIMATIONS
  // ────────────────────────────────────────
  function initAnimations() {
    // Get all sections
    const heroSection = document.querySelector('.hero');
    const aboutSection = document.querySelector('.about-section');
    const worksSection = document.querySelector('.works-section');
    const skillsSection = document.querySelector('.skills-section');
    const certSection = document.querySelector('.cert-section');
    const ctaSection = document.querySelector('.cta-section');
    const aboutHeroSection = document.querySelector('.about-hero');
    const aboutIntroSection = document.querySelector('.about-intro-section');
    const identitySection = document.querySelector('.identity-section');
    const drivesSection = document.querySelector('.drives-section');
    const beyondSection = document.querySelector('.beyond-section');
    const quoteSection = document.querySelector('.quote-section');
    const worksArchivePage = document.querySelector('.works-archive');
    const singleWorkPage = document.querySelector('.single-work-page');

    // Reset each section to its hidden initial state first so the first reveal is controlled
    if (heroSection) resetHeroSection();
    if (aboutSection) resetAboutSection();
    if (worksSection) resetWorksSection();
    if (skillsSection) resetSkillsSection();
    if (certSection) resetCertificatesSection();
    if (ctaSection) resetCtaSection();
    if (aboutHeroSection) resetAboutHeroSection();
    if (aboutIntroSection) resetAboutIntroSection();
    if (identitySection) resetIdentitySection();
    if (drivesSection) resetDrivesSection();
    if (beyondSection) resetBeyondSection();
    if (quoteSection) resetQuoteSection();
    if (worksArchivePage) resetWorksArchivePage();
    if (singleWorkPage) resetSingleWorkPage();

    // Observe each section
    if (heroSection) observer.observe(heroSection);
    if (aboutSection) observer.observe(aboutSection);
    if (worksSection) observer.observe(worksSection);
    if (skillsSection) observer.observe(skillsSection);
    if (certSection) observer.observe(certSection);
    if (ctaSection) observer.observe(ctaSection);
    if (aboutHeroSection) observer.observe(aboutHeroSection);
    if (aboutIntroSection) observer.observe(aboutIntroSection);
    if (identitySection) observer.observe(identitySection);
    if (drivesSection) observer.observe(drivesSection);
    if (beyondSection) observer.observe(beyondSection);
    if (quoteSection) observer.observe(quoteSection);
    if (worksArchivePage) observer.observe(worksArchivePage);
    if (singleWorkPage) observer.observe(singleWorkPage);
  }

  // Initialize when page loads
  initAnimations();

  // Handle window resize
  let resizeTimer;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
      // Intersection Observer automatically handles resize
    }, 250);
  });
});
