document.addEventListener("DOMContentLoaded", function () {

  const element = document.getElementById("typed-text");

  if (!element) return;

  const rawRoles = JSON.parse(element.dataset.roles || "[]");

  const roles = rawRoles.map(role => {
    role = role.trim();

    if (role.toLowerCase().includes("known as")) {
      return {
        prefix: "Also known as ",
        text: role.replace(/known as/i, "").trim()
      };
    }

    if (
      role.toLowerCase().includes("developer") ||
      role.toLowerCase().includes("designer") ||
      role.toLowerCase().includes("editor")
    ) {
      return {
        prefix: "I'm a ",
        text: role
      };
    }

    return {
      prefix: "I'm ",
      text: role
    };
  });

  let i = 0;
  let j = 0;
  let isDeleting = false;

  const speed = 60;
  const eraseSpeed = 40;
  const delay = 1500;

  function typeEffect() {

    if (!roles.length) return;

    const current = roles[i];
    const fullText = current.text;

    if (!isDeleting) {
      j++;
    } else {
      j--;
    }

    const visibleText = fullText.substring(0, j);

    element.innerHTML =
      `<span class="white">${current.prefix}</span>` +
      `<span class="gradient">${visibleText}</span>`;

    if (!isDeleting && j === fullText.length) {
      isDeleting = true;
      setTimeout(typeEffect, delay);
      return;
    }

    if (isDeleting && j === 0) {
      isDeleting = false;
      i = (i + 1) % roles.length;
    }

    setTimeout(typeEffect, isDeleting ? eraseSpeed : speed);
  }

  typeEffect();
});