(function () {
  const canUseParticles = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  if (!canUseParticles || reduceMotion) return;

  const MAX_PARTICLES = 220;
  const colors = ['#ff9d00', '#ffb84d', '#ffcc66', 'rgba(255,165,0,0.5)'];
  const field = document.createElement('div');
  const canvas = document.createElement('canvas');
  const context = canvas.getContext('2d', { alpha: true });

  if (!context) return;

  field.className = 'cursor-energy-field cursor-energy-field--particles';
  field.setAttribute('aria-hidden', 'true');
  canvas.className = 'cursor-particle-canvas';
  field.appendChild(canvas);

  const particles = [];
  let width = 0;
  let height = 0;
  let pixelRatio = 1;
  let animationFrame = null;
  let lastX = window.innerWidth * 0.5;
  let lastY = window.innerHeight * 0.5;
  let lastTimestamp = 0;

  function random(min, max) {
    return min + Math.random() * (max - min);
  }

  function clamp(value, min, max) {
    return Math.max(min, Math.min(max, value));
  }

  function parseColor(color) {
    if (color.startsWith('#')) {
      const hex = color.slice(1);
      const shorthand = hex.length === 3;
      const r = parseInt(shorthand ? hex[0] + hex[0] : hex.slice(0, 2), 16);
      const g = parseInt(shorthand ? hex[1] + hex[1] : hex.slice(2, 4), 16);
      const b = parseInt(shorthand ? hex[2] + hex[2] : hex.slice(4, 6), 16);
      return { r, g, b, a: 1 };
    }

    const rgbaMatch = color.match(/rgba\((\d+),(\d+),(\d+),(\d*\.?\d+)\)/);
    if (rgbaMatch) {
      return {
        r: parseInt(rgbaMatch[1], 10),
        g: parseInt(rgbaMatch[2], 10),
        b: parseInt(rgbaMatch[3], 10),
        a: parseFloat(rgbaMatch[4])
      };
    }

    const rgbMatch = color.match(/rgb\((\d+),(\d+),(\d+)\)/);
    if (rgbMatch) {
      return { r: parseInt(rgbMatch[1], 10), g: parseInt(rgbMatch[2], 10), b: parseInt(rgbMatch[3], 10), a: 1 };
    }

    return { r: 255, g: 157, b: 0, a: 1 };
  }

  function resizeCanvas() {
    pixelRatio = Math.min(window.devicePixelRatio || 1, 2);
    width = window.innerWidth;
    height = window.innerHeight;

    canvas.width = Math.ceil(width * pixelRatio);
    canvas.height = Math.ceil(height * pixelRatio);
    canvas.style.width = `${width}px`;
    canvas.style.height = `${height}px`;
    context.setTransform(pixelRatio, 0, 0, pixelRatio, 0, 0);
  }

  function createParticle(x, y, movement) {
    const color = colors[Math.floor(random(0, colors.length))];
    const angle = random(0, Math.PI * 2);
    const speed = clamp(movement * 0.02 + random(0.3, 1.1), 0.3, 2.8);
    const baseRadius = random(1.2, 3.2);

    return {
      x,
      y,
      vx: Math.cos(angle) * speed + random(-0.3, 0.3),
      vy: Math.sin(angle) * speed + random(-0.3, 0.3) - 0.15,
      radius: baseRadius,
      baseRadius,
      alpha: random(0.55, 0.94),
      life: 0,
      ttl: random(900, 1700),
      color,
      glow: Math.random() > 0.75,
      pulsePhase: random(0, Math.PI * 2)
    };
  }

  function spawnParticles(x, y, movement) {
    const count = clamp(Math.round(movement * 0.085) + 1, 1, 10);

    for (let i = 0; i < count; i++) {
      particles.push(createParticle(x + random(-8, 8), y + random(-8, 8), movement));
    }

    if (particles.length > MAX_PARTICLES) {
      particles.splice(0, particles.length - MAX_PARTICLES);
    }
  }

  function updateParticle(particle, delta) {
    const progress = particle.life / particle.ttl;
    particle.life += delta;
    particle.alpha *= 0.992;
    particle.radius = particle.baseRadius * (1 - progress * 0.55);
    particle.x += particle.vx * delta * 0.045 + Math.sin(particle.life * 0.018 + particle.pulsePhase) * 0.08;
    particle.y += particle.vy * delta * 0.045 + Math.cos(particle.life * 0.015 + particle.pulsePhase) * 0.05;
    particle.vx *= 0.98;
    particle.vy *= 0.98;

    return particle.life < particle.ttl && particle.alpha > 0.04 && particle.radius > 0.35;
  }

  function drawParticle(particle) {
    const progress = particle.life / particle.ttl;
    const radius = Math.max(0.8, particle.radius);
    const color = parseColor(particle.color);
    const alpha = clamp(particle.alpha * (1 - progress * 0.85), 0, 1);
    const gradient = context.createRadialGradient(particle.x, particle.y, 0, particle.x, particle.y, radius * (particle.glow ? 4.2 : 3.4));

    gradient.addColorStop(0, `rgba(${color.r}, ${color.g}, ${color.b}, ${alpha})`);
    gradient.addColorStop(0.22, `rgba(${color.r}, ${color.g}, ${color.b}, ${alpha * 0.36})`);
    gradient.addColorStop(1, `rgba(${color.r}, ${color.g}, ${color.b}, 0)`);

    context.fillStyle = gradient;
    context.beginPath();
    context.arc(particle.x, particle.y, radius, 0, Math.PI * 2);
    context.fill();

    if (particle.glow && Math.random() > 0.88) {
      context.fillStyle = `rgba(255, 255, 255, ${alpha * 0.4})`;
      context.beginPath();
      context.arc(particle.x + random(-2, 2), particle.y + random(-2, 2), radius * 0.5, 0, Math.PI * 2);
      context.fill();
    }
  }

  function render(timestamp) {
    if (!lastTimestamp) lastTimestamp = timestamp;
    const delta = Math.min(timestamp - lastTimestamp, 33);
    lastTimestamp = timestamp;

    context.clearRect(0, 0, width, height);
    context.globalCompositeOperation = 'lighter';

    let writeIndex = 0;
    for (let i = 0; i < particles.length; i++) {
      const particle = particles[i];
      if (updateParticle(particle, delta)) {
        drawParticle(particle);
        particles[writeIndex++] = particle;
      }
    }

    particles.length = writeIndex;
    animationFrame = requestAnimationFrame(render);
  }

  function handlePointerMove(event) {
    const x = event.clientX;
    const y = event.clientY;
    const dx = x - lastX;
    const dy = y - lastY;
    const movement = Math.hypot(dx, dy);

    if (movement > 1) {
      spawnParticles(x, y, movement);
    }

    lastX = x;
    lastY = y;
  }

  function setup() {
    resizeCanvas();
    document.body.appendChild(field);
    animationFrame = requestAnimationFrame(render);
  }

  window.addEventListener('pointermove', handlePointerMove, { passive: true });
  window.addEventListener('resize', resizeCanvas, { passive: true });

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', setup, { once: true });
  } else {
    setup();
  }

  window.addEventListener('pagehide', () => {
    if (animationFrame) cancelAnimationFrame(animationFrame);
  }, { once: true });
})();
