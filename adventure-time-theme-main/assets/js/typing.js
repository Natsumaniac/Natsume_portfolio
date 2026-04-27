document.addEventListener("DOMContentLoaded", function () {

  const roles = [
    { prefix: "I'm ", text: "Neil Roy Generale Omongos" },
    { prefix: "I'm a ", text: "Frontend Developer" },
    { prefix: "I'm a ", text: "UI/UX Designer" },
    { prefix: "I'm a ", text: "Creative Designer" },
    { prefix: "I'm a ", text: "Multimedia Editor" },
    { prefix: "Also known as ", text: "Natsume" }
  ];

  let i = 0;
  let j = 0;
  let isDeleting = false;

  const speed = 60;
  const eraseSpeed = 40;
  const delay = 1500;

  const element = document.getElementById("typed-text");

  function typeEffect() {
    const current = roles[i];
    const fullText = current.text;

    if (!isDeleting) {
      j++;
    } else {
      j--;
    }

    const visibleText = fullText.substring(0, j);

    // IMPORTANT: split colors
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