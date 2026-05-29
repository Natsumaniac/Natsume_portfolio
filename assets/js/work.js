document.addEventListener("DOMContentLoaded", function () {

  const items = document.querySelectorAll(".work-item");
  const next = document.querySelector(".next");
  const prev = document.querySelector(".prev");

  if (!items.length || !next || !prev) {
    return;
  }

  let index = Math.floor(items.length / 2);

  function updateSlider() {

    items.forEach((item, i) => {

      item.classList.remove(
        "center",
        "left1",
        "left2",
        "right1",
        "right2"
      );

      let pos = (i - index + items.length) % items.length;

      if (pos === 0) {

        item.classList.add("center");

      } else if (pos === 1) {

        item.classList.add("right1");

      } else if (pos === 2) {

        item.classList.add("right2");

      } else if (pos === items.length - 1) {

        item.classList.add("left1");

      } else if (pos === items.length - 2) {

        item.classList.add("left2");

      }

    });

  }

  next.addEventListener("click", () => {

    index = (index + 1) % items.length;
    updateSlider();

  });

  prev.addEventListener("click", () => {

    index = (index - 1 + items.length) % items.length;
    updateSlider();

  });

  updateSlider();

});