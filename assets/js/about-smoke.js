document.addEventListener("DOMContentLoaded", () => {
  const hero = document.querySelector(".about-hero");
  const smokeRoot = document.querySelector(".mouth-smoke");

  if (!hero || !smokeRoot) return;

  const puffCount = 32;

  for (let i = 0; i < puffCount; i++) {
    const puff = document.createElement("span");
    puff.className = "smoke-puff";
    smokeRoot.appendChild(puff);
  }

  const puffs = Array.from(smokeRoot.querySelectorAll(".smoke-puff"));

  const randomBetween = (min, max) => Math.random() * (max - min) + min;

  const pickProfile = () => {
    const roll = Math.random();

    if (roll < 0.38) return "core";
    if (roll < 0.8) return "mid";
    return "haze";
  };

  const resetPuff = (puff, baseDelay = 0) => {
    const profile = pickProfile();

    const config = {
      core: {
        size: [34, 64],
        drift: [-32, 32],
        curve: [-20, 20],
        rise: [120, 180],
        duration: [6.4, 9.2],
        endScale: [1.32, 1.92],
        midScale: [0.98, 1.2],
        startOffset: [-12, 12],
        blur: [1.8, 3.2],
        opacityStart: [0.3, 0.45],
        opacityMid: [0.16, 0.28]
      },
      mid: {
        size: [52, 88],
        drift: [-54, 54],
        curve: [-30, 30],
        rise: [150, 230],
        duration: [7.6, 11.6],
        endScale: [1.52, 2.3],
        midScale: [1.08, 1.34],
        startOffset: [-18, 18],
        blur: [2.8, 4.8],
        opacityStart: [0.2, 0.34],
        opacityMid: [0.1, 0.2]
      },
      haze: {
        size: [70, 122],
        drift: [-74, 74],
        curve: [-42, 42],
        rise: [190, 270],
        duration: [9.4, 13.8],
        endScale: [1.74, 2.7],
        midScale: [1.18, 1.48],
        startOffset: [-24, 24],
        blur: [4.4, 7.2],
        opacityStart: [0.1, 0.2],
        opacityMid: [0.05, 0.12]
      }
    }[profile];

    puff.className = `smoke-puff smoke-puff--${profile}`;

    const size = randomBetween(config.size[0], config.size[1]);
    const drift = randomBetween(config.drift[0], config.drift[1]).toFixed(2);
    const driftMid = randomBetween(config.drift[0] * 0.65, config.drift[1] * 0.65).toFixed(2);
    const curve = randomBetween(config.curve[0], config.curve[1]).toFixed(2);
    const rise = randomBetween(config.rise[0], config.rise[1]).toFixed(2);
    const duration = randomBetween(config.duration[0], config.duration[1]).toFixed(2);
    const delay = (baseDelay + randomBetween(0, 1.3)).toFixed(2);
    const endScale = randomBetween(config.endScale[0], config.endScale[1]).toFixed(2);
    const midScale = randomBetween(config.midScale[0], config.midScale[1]).toFixed(2);
    const startOffset = randomBetween(config.startOffset[0], config.startOffset[1]).toFixed(2);
    const blur = randomBetween(config.blur[0], config.blur[1]).toFixed(2);
    const opacityStart = randomBetween(config.opacityStart[0], config.opacityStart[1]).toFixed(2);
    const opacityMid = randomBetween(config.opacityMid[0], config.opacityMid[1]).toFixed(2);

    puff.style.width = `${size}px`;
    puff.style.height = `${size}px`;
    puff.style.left = `calc(50% + ${startOffset}px)`;
    puff.style.setProperty("--drift-x", `${drift}px`);
    puff.style.setProperty("--drift-mid", `${driftMid}px`);
    puff.style.setProperty("--curve-x", `${curve}px`);
    puff.style.setProperty("--rise-y", `${rise}px`);
    puff.style.setProperty("--dur", `${duration}s`);
    puff.style.setProperty("--delay", `${delay}s`);
    puff.style.setProperty("--scale-end", endScale);
    puff.style.setProperty("--scale-mid", midScale);
    puff.style.setProperty("--blur", `${blur}px`);
    puff.style.setProperty("--op-start", opacityStart);
    puff.style.setProperty("--op-mid", opacityMid);
  };

  puffs.forEach((puff, index) => {
    resetPuff(puff, index * 0.3);

    puff.addEventListener("animationiteration", () => {
      resetPuff(puff);
    });
  });
});
