document.addEventListener('DOMContentLoaded', function () {
  const reveals = document.querySelectorAll('.reveal-on-scroll');
  const counters = document.querySelectorAll('.js-counter');
  const progressBars = document.querySelectorAll('.js-progress');
  const searchInput = document.querySelector('.js-skills-search');
  const categoryCards = document.querySelectorAll('[data-category-card]');
  const keycaps = document.querySelectorAll('[data-key]');
  const tooltip = document.querySelector('[data-key-tooltip]');
  const timeline = document.querySelector('.journey-timeline');

  const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      entry.target.classList.add('is-visible');
      revealObserver.unobserve(entry.target);
    });
  }, { threshold: 0.15, rootMargin: '0px 0px -6% 0px' });
  reveals.forEach((el) => revealObserver.observe(el));

  const counterObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      const el = entry.target;
      const target = parseInt(el.dataset.target || '0', 10);
      const start = performance.now();
      const duration = 1000;
      const tick = (now) => {
        const p = Math.min((now - start) / duration, 1);
        const eased = 1 - Math.pow(1 - p, 3);
        el.textContent = String(Math.floor(target * eased));
        if (p < 1) requestAnimationFrame(tick);
        else el.textContent = String(target);
      };
      requestAnimationFrame(tick);
      counterObserver.unobserve(el);
    });
  }, { threshold: 0.5 });
  counters.forEach((el) => counterObserver.observe(el));

  const progressObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      const el = entry.target;
      const target = Math.max(0, Math.min(100, parseInt(el.dataset.progress || '0', 10)));
      el.style.width = target + '%';
      progressObserver.unobserve(el);
    });
  }, { threshold: 0.3 });
  progressBars.forEach((el) => progressObserver.observe(el));

  if (searchInput && categoryCards.length) {
    searchInput.addEventListener('input', () => {
      const q = searchInput.value.trim().toLowerCase();
      categoryCards.forEach((card) => {
        const haystack = card.dataset.search || '';
        card.classList.toggle('is-hidden', q.length && !haystack.includes(q));
      });
    });
  }

  if (keycaps.length && tooltip) {
    keycaps.forEach((key) => {
      key.addEventListener('mouseenter', () => {
        tooltip.innerHTML = '<strong>' + (key.dataset.tipTitle || '') + '</strong><em>' + (key.dataset.tipPercent || '0') + '% · ' + (key.dataset.tipProf || '') + '</em><small>' + (key.dataset.tipDesc || '') + '</small>';
        const keyRect = key.getBoundingClientRect();
        const wrapRect = key.parentElement.parentElement.getBoundingClientRect();
        tooltip.style.left = Math.max(8, keyRect.left - wrapRect.left) + 'px';
        tooltip.style.top = Math.max(8, keyRect.top - wrapRect.top - 96) + 'px';
        tooltip.classList.add('is-visible');
      });
      key.addEventListener('mouseleave', () => tooltip.classList.remove('is-visible'));
      key.addEventListener('click', () => {
        key.classList.add('is-pressed');
        setTimeout(() => key.classList.remove('is-pressed'), 160);
      });
    });
  }

  if (timeline) {
    const timeObserver = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        timeline.classList.add('is-visible');
        timeObserver.unobserve(timeline);
      });
    }, { threshold: 0.3 });
    timeObserver.observe(timeline);
  }

  const panelDataEl = document.getElementById('skill-viewer-data');
  const panelRoot = document.querySelector('.js-ball-info-panel');
  const layoutRoot = document.querySelector('.js-balls-layout');

  let panelController = null;
  if (panelDataEl && panelRoot && layoutRoot) {
    try {
      const panelData = JSON.parse(panelDataEl.textContent || '[]');
      if (Array.isArray(panelData) && panelData.length) {
        panelController = initBallInfoPanel(layoutRoot, panelRoot, panelData);
      }
    } catch (error) {
      console.warn('Skill panel parse error', error);
    }
  }

  const stage = document.querySelector('.js-balls-stage');
  if (stage) initBalls(stage, panelController, layoutRoot);
});

function initBallInfoPanel(layoutRoot, panelRoot, data) {
  const heroPercent = panelRoot.querySelector('.ball-info-panel__hero-percent');
  const title = panelRoot.querySelector('.ball-info-panel__title');
  const badge = panelRoot.querySelector('.ball-info-panel__badge');
  const experience = panelRoot.querySelector('.ball-info-panel__experience');
  const category = panelRoot.querySelector('.ball-info-panel__category');
  const description = panelRoot.querySelector('.ball-info-panel__description');
  const tagsWrap = panelRoot.querySelector('.ball-info-panel__tags');
  const closeBtn = panelRoot.querySelector('.js-ball-info-close');

  const byId = new Map();
  data.forEach((item) => byId.set(parseInt(item.id, 10), item));
  const listeners = [];
  let activeId = null;
  let progressFrame = null;

  function formatCategoryList(skill) {
    const names = Array.isArray(skill.category_names) && skill.category_names.length
      ? skill.category_names
      : Array.isArray(skill.categories) ? skill.categories : [];

    if (!names.length) return 'Uncategorized';

    return names
      .map((name) => String(name).replace(/-/g, ' ').replace(/\b\w/g, (letter) => letter.toUpperCase()))
      .join(', ');
  }

  function stopProgressAnimation() {
    if (progressFrame) cancelAnimationFrame(progressFrame);
    progressFrame = null;
  }

  function resetProgress() {
    stopProgressAnimation();
    panelRoot.style.setProperty('--skill-progress', '0');
    if (heroPercent) heroPercent.textContent = '0%';
  }

  function animateProgress(target) {
    stopProgressAnimation();
    const clamped = Math.max(0, Math.min(100, parseInt(target || 0, 10)));
    const start = performance.now();
    const duration = 900;

    const tick = (now) => {
      const progress = Math.min((now - start) / duration, 1);
      const eased = 1 - Math.pow(1 - progress, 3);
      const value = Math.round(clamped * eased);

      panelRoot.style.setProperty('--skill-progress', String(value));
      if (heroPercent) heroPercent.textContent = `${value}%`;

      if (progress < 1) {
        progressFrame = requestAnimationFrame(tick);
      } else {
        progressFrame = null;
      }
    };

    panelRoot.style.setProperty('--skill-progress', '0');
    if (heroPercent) heroPercent.textContent = '0%';
    progressFrame = requestAnimationFrame(tick);
  }

  function updatePanelTheme(skill) {
    const accent = skill.color || '#ff7a00';
    panelRoot.style.setProperty('--panel-accent', accent);
    panelRoot.style.setProperty('--panel-accent-soft', `${accent}2a`);
    layoutRoot.style.setProperty('--active-skill-accent', accent);
    layoutRoot.classList.add('has-info');
  }

  closeBtn?.addEventListener('click', () => {
    activeId = null;
    layoutRoot.classList.remove('has-info');
    resetProgress();
    listeners.forEach((fn) => fn(null));
  });

  function render(skill) {
    const accent = skill.color || '#ff7a00';
    updatePanelTheme(skill);

    title.textContent = skill.title || 'Skill';
    badge.textContent = skill.proficiency || skill.level || 'Proficient';
    experience.textContent = skill.experience || 'Experience not specified';
    category.textContent = formatCategoryList(skill);
    description.textContent = skill.description || 'Core professional skill used in project development.';

    tagsWrap.innerHTML = '';
    const tags = (skill.tags && skill.tags.length ? skill.tags : skill.categories || []).slice(0, 12);
    tags.forEach((tag) => {
      const chip = document.createElement('span');
      chip.textContent = tag;
      chip.style.borderColor = `${accent}55`;
      chip.style.background = `${accent}1c`;
      tagsWrap.appendChild(chip);
    });

    animateProgress(skill.percentage || 0);
  }

  return {
    setActiveById(skillId) {
      const normalized = parseInt(skillId, 10);
      if (!byId.has(normalized)) return;
      activeId = normalized;
      render(byId.get(normalized));
      layoutRoot.classList.add('has-info');
      listeners.forEach((fn) => fn(activeId));
    },
    clear() {
      activeId = null;
      layoutRoot.classList.remove('has-info');
      resetProgress();
      listeners.forEach((fn) => fn(null));
    },
    onChange(fn) {
      if (typeof fn === 'function') listeners.push(fn);
    },
    getActiveId() {
      return activeId;
    }
  };
}

function initBalls(stage, panelController, layoutRoot) {
  if (!window.Matter) return;

  const { Engine, Runner, Bodies, Composite, Mouse, MouseConstraint, Body, Events } = Matter;

  const BALL_MAX_SPEED = 20;
  const RELEASE_MAX_SPEED = 17;
  const WALL_THICKNESS = 90;
  const SCROLL_FORCE = 0.00115;
  const SCROLL_SIDE_FORCE = 0.00024;
  const SCROLL_COOLDOWN = 60;

  const engine = Engine.create({
    enableSleeping: true,
    gravity: { x: 0, y: 1.05, scale: 0.001 }
  });

  const world = engine.world;
  const runner = Runner.create();

  let stageWidth = stage.clientWidth;
  let stageHeight = stage.clientHeight;
  let bounds = [];
  const items = [];
  let lastScrollY = window.scrollY || window.pageYOffset || 0;
  let lastScrollImpulseAt = 0;

  function createBounds(width, height) {
    const options = {
      isStatic: true,
      restitution: 0.22,
      friction: 0.8,
      frictionStatic: 0.9,
      render: { visible: false }
    };

    return [
      Bodies.rectangle(width / 2, height + WALL_THICKNESS / 2, width + WALL_THICKNESS * 2, WALL_THICKNESS, options),
      Bodies.rectangle(width / 2, -WALL_THICKNESS / 2, width + WALL_THICKNESS * 2, WALL_THICKNESS, options),
      Bodies.rectangle(-WALL_THICKNESS / 2, height / 2, WALL_THICKNESS, height + WALL_THICKNESS * 2, options),
      Bodies.rectangle(width + WALL_THICKNESS / 2, height / 2, WALL_THICKNESS, height + WALL_THICKNESS * 2, options)
    ];
  }

  bounds = createBounds(stageWidth, stageHeight);
  Composite.add(world, bounds);

  const balls = Array.from(stage.querySelectorAll('[data-ball]'));
  if (!balls.length) return;

  balls.forEach((el, index) => {
    const visualRadius = Math.max(20, el.offsetWidth / 2);
    const pickRadius = visualRadius + 10;
    const columns = Math.max(3, Math.ceil(Math.sqrt(balls.length)));
    const col = index % columns;
    const row = Math.floor(index / columns);
    const startX = 80 + col * ((stageWidth - 160) / Math.max(1, columns - 1));
    const startY = -120 - row * (visualRadius * 2.4);

    const body = Bodies.circle(startX, startY, pickRadius, {
      restitution: 0.56,
      friction: 0.015,
      frictionAir: 0.032,
      density: 0.0012,
      sleepThreshold: 45
    });

    const skillId = parseInt(el.dataset.skillId || '0', 10);
    const skillColor = el.dataset.skillColor || '#ff7a00';
    el.style.setProperty('--ball-accent', skillColor);

    items.push({ el, body, radius: pickRadius, visualRadius, skillId });
    Composite.add(world, body);
  });

  const mouse = Mouse.create(stage);
  mouse.pixelRatio = window.devicePixelRatio || 1;

  const mouseConstraint = MouseConstraint.create(engine, {
    mouse,
    constraint: {
      stiffness: 0.48,
      damping: 0.03,
      angularStiffness: 0,
      render: { visible: false }
    }
  });
  Composite.add(world, mouseConstraint);

  Events.on(mouseConstraint, 'startdrag', (event) => {
    if (!event.body) return;
    const dragged = items.find((item) => item.body === event.body);
    if (dragged) dragged.el.classList.add('is-dragging');
  });

  Events.on(mouseConstraint, 'enddrag', (event) => {
    if (event.body) {
      const dragged = items.find((item) => item.body === event.body);
      if (dragged) dragged.el.classList.remove('is-dragging');

      const vx = event.body.velocity.x;
      const vy = event.body.velocity.y;
      const speed = Math.hypot(vx, vy);
      if (speed > RELEASE_MAX_SPEED) {
        const factor = RELEASE_MAX_SPEED / speed;
        Body.setVelocity(event.body, { x: vx * factor, y: vy * factor });
      }
    }
  });

  function setActiveBallById(skillId) {
    items.forEach((item) => {
      item.el.classList.toggle('is-selected', item.skillId === skillId);
    });
  }

  function selectSkillById(skillId) {
    if (!skillId || !panelController) return;
    panelController.setActiveById(skillId);
  }

  items.forEach((item) => {
    item.el.addEventListener('click', () => selectSkillById(item.skillId));
  });

  panelController?.onChange((skillId) => {
    setActiveBallById(skillId);
  });

  function clampBodiesInside() {
    items.forEach((item) => {
      const body = item.body;
      const radius = item.radius;

      if (!Number.isFinite(body.position.x) || !Number.isFinite(body.position.y)) {
        Body.setPosition(body, { x: stageWidth / 2, y: stageHeight / 2 });
        Body.setVelocity(body, { x: 0, y: 0 });
        return;
      }

      let x = body.position.x;
      let y = body.position.y;
      let vx = body.velocity.x;
      let vy = body.velocity.y;

      const minX = radius;
      const maxX = stageWidth - radius;
      const minY = radius;
      const maxY = stageHeight - radius;

      if (x < minX) { x = minX; vx = Math.abs(vx) * 0.45; }
      else if (x > maxX) { x = maxX; vx = -Math.abs(vx) * 0.45; }

      if (y < minY) { y = minY; vy = Math.abs(vy) * 0.45; }
      else if (y > maxY) { y = maxY; vy = -Math.abs(vy) * 0.45; }

      const speed = Math.hypot(vx, vy);
      if (speed > BALL_MAX_SPEED) {
        const factor = BALL_MAX_SPEED / speed;
        vx *= factor;
        vy *= factor;
      }

      if (x !== body.position.x || y !== body.position.y) Body.setPosition(body, { x, y });
      if (vx !== body.velocity.x || vy !== body.velocity.y) Body.setVelocity(body, { x: vx, y: vy });
    });
  }

  Events.on(engine, 'afterUpdate', clampBodiesInside);

  function wakeBodies() {
    items.forEach((item) => {
      item.body.isSleeping = false;
    });
  }

  function handleScrollShake() {
    const now = performance.now();
    const currentY = window.scrollY || window.pageYOffset || 0;
    const delta = currentY - lastScrollY;
    lastScrollY = currentY;

    if (Math.abs(delta) < 2) return;
    if (now - lastScrollImpulseAt < SCROLL_COOLDOWN) return;

    lastScrollImpulseAt = now;
    const direction = delta > 0 ? -1 : 1;
    const magnitude = Math.min(2.6, Math.max(0.8, Math.abs(delta) / 26));

    wakeBodies();
    items.forEach((item) => {
      const jitter = (Math.random() - 0.5) * 2;
      const side = (Math.random() - 0.5) * 2;
      const forceY = direction * SCROLL_FORCE * magnitude * (0.7 + Math.random() * 0.65) + jitter * 0.00008;
      const forceX = side * SCROLL_SIDE_FORCE * magnitude;
      Body.applyForce(item.body, item.body.position, { x: forceX, y: forceY });
      item.body.torque += side * 0.000028 * magnitude;
    });
  }

  window.addEventListener('scroll', handleScrollShake, { passive: true });

  Runner.run(runner, engine);

  function render() {
    items.forEach((item) => {
      const displayX = item.body.position.x - item.visualRadius;
      const displayY = item.body.position.y - item.visualRadius;
      item.el.style.transform = `translate(${displayX}px, ${displayY}px)`;
    });
    requestAnimationFrame(render);
  }
  requestAnimationFrame(render);

  let previousWidth = stageWidth;
  const resizeObserver = new ResizeObserver(() => {
    const nextWidth = stage.clientWidth;
    const nextHeight = stage.clientHeight;
    if (!nextWidth || !nextHeight) return;

    const widthDelta = nextWidth - previousWidth;
    previousWidth = nextWidth;

    stageWidth = nextWidth;
    stageHeight = nextHeight;

    Composite.remove(world, bounds);
    bounds = createBounds(stageWidth, stageHeight);
    Composite.add(world, bounds);

    if (Math.abs(widthDelta) > 2) {
      wakeBodies();
      const squeezeDirection = widthDelta < 0 ? 1 : -1;
      const squeezeForce = Math.min(0.0009, Math.max(0.00025, Math.abs(widthDelta) / 140000));

      items.forEach((item) => {
        const horizontalBias = item.body.position.x < stageWidth / 2 ? 1 : -1;
        Body.applyForce(item.body, item.body.position, {
          x: horizontalBias * squeezeDirection * squeezeForce,
          y: -squeezeForce * 0.9
        });
      });
    }

    clampBodiesInside();
  });

  resizeObserver.observe(stage);
}
