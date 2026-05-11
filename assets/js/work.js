document.addEventListener("DOMContentLoaded", function() {
  console.log("Slider initialized");
  
  const items = document.querySelectorAll(".work-item");
  const next = document.querySelector(".next");
  const prev = document.querySelector(".prev");

  if (!items.length || !next || !prev) {
    console.error("Slider elements not found");
    return;
  }

  let index = 2; // start sa middle (work3)

  function updateSlider() {
    console.log("Index:", index);
    
    items.forEach((item, i) => {
      // Remove all position classes first
      item.classList.remove("center", "left1", "left2", "right1", "right2");

      // Calculate position relative to current index
      let pos = (i - index + items.length) % items.length;

      // Assign correct position class
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

  // Event listeners
  next.addEventListener("click", () => {
    index = (index + 1) % items.length;
    updateSlider();
  });

  prev.addEventListener("click", () => {
    index = (index - 1 + items.length) % items.length;
    updateSlider();
  });

  // Initialize slider on page load
  updateSlider();
});