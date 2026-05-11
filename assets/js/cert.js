document.addEventListener("DOMContentLoaded", () => {

  const years = document.querySelectorAll(".year");
  const contents = document.querySelectorAll(".cert-content");
  const image = document.getElementById("cert-image");

  function activateItem(item) {
    const parent = item.parentElement;

    parent.querySelectorAll(".cert-item").forEach(i => {
      i.classList.remove("active");
      const desc = i.querySelector(".cert-desc");
      if (desc) desc.textContent = "";
    });

    item.classList.add("active");

    // IMAGE
    image.src = item.dataset.img;

    // DESCRIPTION
    const descBox = item.querySelector(".cert-desc");
    if (descBox) {
      descBox.textContent = item.dataset.desc;
    }
  }

  /* YEAR CLICK */
  years.forEach(year => {
    year.addEventListener("click", () => {

      years.forEach(y => y.classList.remove("active"));
      contents.forEach(c => c.classList.remove("active"));

      year.classList.add("active");

      const target = year.dataset.target;
      const content = document.getElementById(target);

      content.classList.add("active");

      const firstItem = content.querySelector(".cert-item");

      if (firstItem) activateItem(firstItem);
    });
  });

  /* ITEM CLICK */
  document.addEventListener("click", (e) => {
    const item = e.target.closest(".cert-item");
    if (!item) return;

    activateItem(item);
  });

  /* INIT FIRST */
  const firstActive = document.querySelector(".cert-item.active");
  if (firstActive) activateItem(firstActive);

});