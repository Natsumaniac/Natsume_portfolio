document.addEventListener('DOMContentLoaded', function () {
  const reveals = document.querySelectorAll('.reveal-on-scroll');
  const counters = document.querySelectorAll('.js-counter');
  const progressBars = document.querySelectorAll('.js-progress');
  const searchInput = document.querySelector('.js-skills-search');
  const categoryCards = document.querySelectorAll('[data-category-card]');
  const developerKeyboard = document.querySelector('.js-developer-keyboard');
  const tooltip = developerKeyboard?.querySelector('[data-key-tooltip]') || null;
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

  if (developerKeyboard && tooltip) initDeveloperKeyboard(developerKeyboard, tooltip);
  initTechNetworkClusters();
  initDeveloperWorkspace();
  initSkillTreeTooltips();

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

function escapeTooltipHtml(value) {
  return String(value)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}

function positionFloatingTooltip(tooltip, target) {
  const rect = target.getBoundingClientRect();
  const tipRect = tooltip.getBoundingClientRect();
  const margin = 12;
  let left = rect.left + rect.width / 2 - tipRect.width / 2;
  let top = rect.top - tipRect.height - 14;

  left = Math.max(margin, Math.min(window.innerWidth - tipRect.width - margin, left));

  if (top < margin) {
    top = rect.bottom + 14;
  }

  top = Math.max(margin, Math.min(window.innerHeight - tipRect.height - margin, top));
  tooltip.style.left = `${left}px`;
  tooltip.style.top = `${top}px`;
}

function initTechNetworkClusters() {
  const clusters = document.querySelectorAll('.tech-cluster');
  if (!clusters.length) return;

  clusters.forEach((cluster) => {
    const nodes = cluster.querySelectorAll('[data-network-node]');
    const lines = cluster.querySelectorAll('[data-network-line]');

    nodes.forEach((node) => {
      const index = node.dataset.networkNode;
      const line = cluster.querySelector(`[data-network-line="${index}"]`);

      node.addEventListener('pointerenter', () => {
        nodes.forEach((item) => item.classList.toggle('is-active', item === node));
        lines.forEach((item) => item.classList.toggle('is-active', item === line));
      });

      node.addEventListener('pointerleave', () => {
        nodes.forEach((item) => item.classList.remove('is-active'));
        lines.forEach((item) => item.classList.remove('is-active'));
      });
    });
  });
}

function initDeveloperWorkspace() {
  const tools = document.querySelectorAll('[data-tool-item]');
  const spotlight = document.querySelector('.js-workspace-spotlight');
  const tooltip = document.querySelector('.js-workspace-tooltip');
  if (!tools.length || !tooltip) return;

  const defaultText = spotlight?.textContent || '';

  tools.forEach((tool) => {
    tool.addEventListener('pointerenter', () => {
      const title = tool.dataset.toolTitle || 'Tool';
      const proficiency = tool.dataset.toolProf || 'Workspace utility';

      tools.forEach((item) => item.classList.toggle('is-active', item === tool));
      if (spotlight) spotlight.textContent = `${title} · ${proficiency}`;
      tooltip.innerHTML = `<strong>${escapeTooltipHtml(title)}</strong><span>${escapeTooltipHtml(proficiency)}</span>`;
      tooltip.classList.add('is-visible');
      requestAnimationFrame(() => positionFloatingTooltip(tooltip, tool));
    });

    tool.addEventListener('pointermove', () => positionFloatingTooltip(tooltip, tool));

    tool.addEventListener('pointerleave', () => {
      tool.classList.remove('is-active');
      tooltip.classList.remove('is-visible');
      if (spotlight) spotlight.textContent = defaultText;
    });
  });
}

function initSkillTreeTooltips() {
  const nodes = document.querySelectorAll('[data-tree-node]');
  const tooltip = document.querySelector('.js-skill-tree-tooltip');
  if (!nodes.length || !tooltip) return;

  nodes.forEach((node) => {
    node.addEventListener('pointerenter', () => {
      const title = node.dataset.treeTitle || 'Skill';
      const year = node.dataset.treeYear || '';
      const desc = node.dataset.treeDesc || 'Unlocked milestone';

      nodes.forEach((item) => item.classList.toggle('is-active', item === node));
      tooltip.innerHTML = `<strong>${escapeTooltipHtml(title)}</strong><span>${escapeTooltipHtml(year)}</span><p>${escapeTooltipHtml(desc)}</p>`;
      tooltip.classList.add('is-visible');
      requestAnimationFrame(() => positionFloatingTooltip(tooltip, node));
    });

    node.addEventListener('pointermove', () => positionFloatingTooltip(tooltip, node));

    node.addEventListener('pointerleave', () => {
      node.classList.remove('is-active');
      tooltip.classList.remove('is-visible');
    });
  });
}

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

function initDeveloperKeyboard(keyboardRoot, tooltip) {
  const keys = Array.from(keyboardRoot.querySelectorAll('[data-key]'));
  if (!keys.length) return;

  const supportsHover = window.matchMedia('(hover: hover)').matches && window.matchMedia('(pointer: fine)').matches;
  const shell = keyboardRoot.querySelector('.keyboard-shell') || keyboardRoot;
  const displayOutput = keyboardRoot.querySelector('.js-keyboard-display');
  const portfolioAccent = getComputedStyle(shell).getPropertyValue('--keyboard-accent').trim() || '#ff7a00';
  let activeTouchKey = null;
  let hideTimeout = null;
  let waveTimeout = null;
  let waveTimers = [];
  let currentCommand = '';
  let terminalText = '';
  let typeTimer = null;
  let typeToken = 0;
  const terminalPrompt = displayOutput?.dataset.prompt || 'visitor@natsume:~$ ';

  function clamp(value, min, max) {
    return Math.max(min, Math.min(max, value));
  }

  function clearTooltipTimer() {
    if (hideTimeout) window.clearTimeout(hideTimeout);
    hideTimeout = null;
  }

  function escapeHtml(value) {
    return String(value)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  function hideTooltip(immediate = false) {
    clearTooltipTimer();
    tooltip.classList.remove('is-visible');
    tooltip.classList.remove('is-below');
    if (immediate) {
      tooltip.style.left = '';
      tooltip.style.top = '';
    }
    activeTouchKey = null;
  }

  function buildTooltipContent(key) {
    const title = key.dataset.skillTitle || key.dataset.keyLabel || '';
    const percent = parseInt(key.dataset.skillPercent || '0', 10);
    const proficiency = key.dataset.skillProf || '';
    const experience = key.dataset.skillExp || '';
    const safePercent = clamp(Number.isFinite(percent) ? percent : 0, 0, 100);

    tooltip.style.setProperty('--scan-progress', safePercent);

    tooltip.innerHTML = `
      <div class="scan-card" aria-label="Skill scan card">
        <span class="scan-card__eyebrow">SKILL SCAN</span>
        <strong>${escapeHtml(title).toUpperCase()}</strong>
        <div class="scan-card__dial" aria-hidden="true">
          <span>${safePercent}%</span>
        </div>
        <em>${escapeHtml(proficiency || 'Level')}</em>
        <small>${escapeHtml(experience || 'Experience')}</small>
      </div>
    `;
  }

  function positionTooltip(key) {
    const keyRect = key.getBoundingClientRect();
    const tipRect = tooltip.getBoundingClientRect();
    const margin = 12;
    const viewportWidth = window.innerWidth;
    const viewportHeight = window.innerHeight;

    let left = keyRect.left + (keyRect.width / 2) - (tipRect.width / 2);
    left = clamp(left, margin, viewportWidth - tipRect.width - margin);

    let top = keyRect.top - tipRect.height - 14;
    let below = false;
    if (top < margin) {
      top = keyRect.bottom + 14;
      below = true;
    }
    if (top + tipRect.height > viewportHeight - margin) {
      top = viewportHeight - tipRect.height - margin;
      below = true;
    }
    top = clamp(top, margin, viewportHeight - tipRect.height - margin);

    tooltip.style.left = `${left}px`;
    tooltip.style.top = `${top}px`;
    tooltip.classList.toggle('is-below', below);
  }

  function showTooltip(key) {
    if (!key.dataset.skillId) return;
    clearTooltipTimer();
    buildTooltipContent(key);
    tooltip.classList.add('is-visible');
    requestAnimationFrame(() => positionTooltip(key));
  }

  function setKeyboardDisplay(value) {
    if (!displayOutput) return;
    terminalText = value;
    displayOutput.textContent = terminalText;
  }

  function stopTyping() {
    if (typeTimer) window.clearTimeout(typeTimer);
    typeTimer = null;
    typeToken++;
  }

  function typeFullDisplay(value, speed = 15) {
    if (!displayOutput) return;

    stopTyping();
    const token = typeToken;
    let index = 0;

    function tick() {
      if (token !== typeToken) return;
      setKeyboardDisplay(value.slice(0, index));
      index++;

      if (index <= value.length) {
        const character = value.charAt(index - 2);
        const delay = character === '\n' ? speed * 6 : speed + Math.random() * 10;
        typeTimer = window.setTimeout(tick, delay);
      } else {
        typeTimer = null;
      }
    }

    tick();
  }

  function typeCommandAppend(value) {
    if (!displayOutput || !value) return;

    stopTyping();
    const token = typeToken;
    let index = 0;

    function tick() {
      if (token !== typeToken) return;
      currentCommand += value.charAt(index);
      setKeyboardDisplay(`${terminalPrompt}${currentCommand}`);
      index++;

      if (index < value.length) {
        typeTimer = window.setTimeout(tick, 18 + Math.random() * 12);
      } else {
        typeTimer = null;
      }
    }

    tick();
  }

  function getKeyOutputValue(key) {
    const label = key.dataset.keyLabel || '';

    if (key.dataset.skillTitle) return key.dataset.skillTitle;
    if (label === 'Space') return ' ';
    if (label === 'Enter') return '\n';
    if (label === 'Tab') return '  ';
    if (label === 'Backspace') return null;
    if (['Shift', 'Caps Lock', 'Ctrl', 'Alt', 'Fn', 'Win', 'Menu', 'Num Lock', 'Scroll Lock', 'Print Screen', 'Pause Break'].includes(label)) {
      return `[${label}]`;
    }

    return label;
  }

  function updateKeyboardDisplay(key) {
    const label = key.dataset.keyLabel || '';
    const outputValue = getKeyOutputValue(key);

    if (key.dataset.skillTitle) {
      const title = key.dataset.skillTitle || 'Skill';
      const experience = key.dataset.skillExp || 'Experience not specified';
      const proficiency = key.dataset.skillProf || 'Proficient';
      const percent = key.dataset.skillPercent || '0';
      const description = key.dataset.skillDesc || 'Developer skill';
      const summary = description.split(/[.!?]/).filter(Boolean)[0] || description;
      const response = `${terminalPrompt}${title}\n\nLoading skill data...\n${summary}\nExperience: ${experience}\nProficiency: ${proficiency}\nSkill Level: ${percent}%`;

      currentCommand = title;
      typeFullDisplay(response, 13);
      return;
    }

    if (label === 'Backspace') {
      stopTyping();
      currentCommand = currentCommand.slice(0, -1);
      setKeyboardDisplay(`${terminalPrompt}${currentCommand}`);
    } else if (outputValue !== null) {
      if (label === 'Enter') {
        stopTyping();
        setKeyboardDisplay(`${terminalPrompt}${currentCommand}\n${terminalPrompt}`);
        currentCommand = '';
      } else {
        typeCommandAppend(outputValue);
      }
    }
  }

  function triggerWave(originKey) {
    const originRect = originKey.getBoundingClientRect();
    const originX = originRect.left + originRect.width / 2;
    const originY = originRect.top + originRect.height / 2;
    const waveSpeed = 3.6;

    waveTimers.forEach((timer) => window.clearTimeout(timer));
    waveTimers = [];

    keys.forEach((key) => {
      const rect = key.getBoundingClientRect();
      const centerX = rect.left + rect.width / 2;
      const centerY = rect.top + rect.height / 2;
      const distance = Math.hypot(centerX - originX, centerY - originY);
      const delay = Math.round(distance * waveSpeed);

      key.style.setProperty('--wave-delay', `${delay}ms`);
      key.classList.remove('is-waving');
      void key.offsetWidth;
      key.classList.add('is-waving');
      waveTimers.push(window.setTimeout(() => key.classList.add('is-lit'), delay + 140));
    });

    shell.classList.add('is-rippling');
    shell.classList.add('has-ambient-light');
    if (waveTimeout) window.clearTimeout(waveTimeout);
    const furthestDelay = Math.max(...keys.map((key) => parseInt(key.style.getPropertyValue('--wave-delay') || '0', 10)));
    waveTimeout = window.setTimeout(() => {
      keys.forEach((key) => {
        key.classList.remove('is-waving', 'is-pressed');
        key.style.removeProperty('--wave-delay');
      });
      shell.classList.remove('is-rippling');
      waveTimeout = null;
    }, furthestDelay + 1300);
  }

  function handleKeyActivation(key, event) {
    event.preventDefault();
    if (!supportsHover && key.dataset.skillId) {
      activeTouchKey = key;
    }

    keys.forEach((item) => {
      if (item !== key) item.classList.remove('is-active');
    });

    key.classList.add('is-pressed');
    key.classList.add('is-lit', 'is-active');

    updateKeyboardDisplay(key);
    triggerWave(key);

    window.setTimeout(() => {
      key.classList.remove('is-pressed');
    }, 240);

    if (!supportsHover && key.dataset.skillId) {
      showTooltip(key);
    }
  }

  keys.forEach((key) => {
    key.style.setProperty('--skill-accent', portfolioAccent);

    if (supportsHover && key.dataset.skillId) {
      key.addEventListener('pointerenter', () => showTooltip(key));
      key.addEventListener('pointermove', () => positionTooltip(key));
      key.addEventListener('pointerleave', () => {
        if (activeTouchKey !== key) hideTooltip();
      });
    }

    key.addEventListener('click', (event) => {
      handleKeyActivation(key, event);
    });
  });

  setKeyboardDisplay(terminalPrompt);

  document.addEventListener('pointerdown', (event) => {
    if (!keyboardRoot.contains(event.target)) {
      hideTooltip(true);
      return;
    }

    if (!supportsHover) {
      const tappedKey = event.target.closest('[data-key]');
      if (!tappedKey || !tappedKey.dataset.skillId) {
        hideTooltip(true);
      }
    }
  });

  window.addEventListener('resize', () => {
    if (tooltip.classList.contains('is-visible') && activeTouchKey) {
      positionTooltip(activeTouchKey);
    }
  });
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
