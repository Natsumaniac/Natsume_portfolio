(function () {
  const canUseTrail = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  if (!canUseTrail || reduceMotion) return;

  const PARTICLE_COUNT = 9;
  const PULSE_COUNT = 7;
  const PULSE_DISTANCE = 44;
  const IDLE_FADE_DELAY = 120;

  const field = document.createElement('div');
  field.className = 'cursor-energy-field';
  field.setAttribute('aria-hidden', 'true');

  const particles = [];
  const pulses = [];
  const pointer = { x: window.innerWidth / 2, y: window.innerHeight / 2 };
  let lastPointer = { ...pointer };
  let lastPulse = { ...pointer };
  let lastMoveAt = 0;
  let hasPointer = false;
  let pulseIndex = 0;
  let rafId = null;

  function createParticle(index) {
    const element = document.createElement('span');
    const size = 5 + (index % 5) * 1.45;
    const blur = index % 3 === 0 ? 0.7 : 0.25;

    element.className = 'cursor-energy-particle';
    element.style.setProperty('--particle-size', `${size}px`);
    element.style.setProperty('--particle-blur', `${blur}px`);
    field.appendChild(element);

    return {
      element,
      x: pointer.x,
      y: pointer.y,
      size,
      opacity: 0,
      drift: (index - PARTICLE_COUNT / 2) * 0.18,
      ease: 0.16 - Math.min(index * 0.009, 0.075)
    };
  }

  function createPulse() {
    const element = document.createElement('span');
    element.className = 'cursor-energy-pulse';
    field.appendChild(element);

    return {
      element,
      x: pointer.x,
      y: pointer.y,
      age: 1,
      size: 30
    };
  }

  function setup() {
    for (let index = 0; index < PARTICLE_COUNT; index++) {
      particles.push(createParticle(index));
    }

    for (let index = 0; index < PULSE_COUNT; index++) {
      pulses.push(createPulse());
    }

    document.body.appendChild(field);
    rafId = requestAnimationFrame(animate);
  }

  function emitPulse(x, y, speed) {
    const pulse = pulses[pulseIndex];
    pulseIndex = (pulseIndex + 1) % pulses.length;

    pulse.x = x;
    pulse.y = y;
    pulse.age = 0;
    pulse.size = Math.min(48, 28 + speed * 0.18);
    pulse.element.style.setProperty('--pulse-size', `${pulse.size}px`);
  }

  function handlePointerMove(event) {
    const dx = event.clientX - pointer.x;
    const dy = event.clientY - pointer.y;
    const travel = Math.hypot(event.clientX - lastPulse.x, event.clientY - lastPulse.y);
    const speed = Math.hypot(dx, dy);

    pointer.x = event.clientX;
    pointer.y = event.clientY;
    lastMoveAt = performance.now();
    hasPointer = true;

    if (travel > PULSE_DISTANCE) {
      emitPulse(pointer.x, pointer.y, speed);
      lastPulse = { ...pointer };
    }

    if (!rafId) {
      rafId = requestAnimationFrame(animate);
    }
  }

  function animate(now) {
    const idleAmount = hasPointer ? Math.min(1, Math.max(0, (now - lastMoveAt - IDLE_FADE_DELAY) / 520)) : 1;
    const velocityX = pointer.x - lastPointer.x;
    const velocityY = pointer.y - lastPointer.y;
    const speed = Math.min(90, Math.hypot(velocityX, velocityY));
    let anchorX = pointer.x;
    let anchorY = pointer.y;

    particles.forEach((particle, index) => {
      const offset = index * 2.8;
      const targetX = anchorX - velocityX * (0.06 + index * 0.012) + Math.sin(now * 0.004 + index) * particle.drift;
      const targetY = anchorY - velocityY * (0.06 + index * 0.012) + Math.cos(now * 0.003 + index) * particle.drift;

      particle.x += (targetX - particle.x) * particle.ease;
      particle.y += (targetY - particle.y) * particle.ease;
      particle.opacity += ((hasPointer ? 0.74 - index * 0.055 : 0) * (1 - idleAmount) - particle.opacity) * 0.12;

      const scale = 0.76 + (speed / 180) + index * 0.018;
      particle.element.style.opacity = particle.opacity.toFixed(3);
      particle.element.style.transform = `translate3d(${particle.x - particle.size / 2 - offset * 0.03}px, ${particle.y - particle.size / 2}px, 0) scale(${scale})`;

      anchorX = particle.x;
      anchorY = particle.y;
    });

    pulses.forEach((pulse) => {
      if (pulse.age >= 1) {
        pulse.element.style.opacity = '0';
        return;
      }

      pulse.age = Math.min(1, pulse.age + 0.024);
      const eased = 1 - Math.pow(1 - pulse.age, 3);
      const opacity = (1 - eased) * 0.38;
      const scale = 0.45 + eased * 1.45;

      pulse.element.style.opacity = opacity.toFixed(3);
      pulse.element.style.transform = `translate3d(${pulse.x - pulse.size / 2}px, ${pulse.y - pulse.size / 2}px, 0) scale(${scale})`;
    });

    lastPointer = { ...pointer };

    const hasVisibleParticles = particles.some((particle) => particle.opacity > 0.01);
    const hasActivePulses = pulses.some((pulse) => pulse.age < 1);

    if (hasPointer || hasVisibleParticles || hasActivePulses) {
      rafId = requestAnimationFrame(animate);
    } else {
      rafId = null;
    }
  }

  window.addEventListener('pointermove', handlePointerMove, { passive: true });
  window.addEventListener('pointerleave', () => {
    hasPointer = false;
  });
  window.addEventListener('blur', () => {
    hasPointer = false;
  });

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', setup, { once: true });
  } else {
    setup();
  }
})();
