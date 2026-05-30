document.addEventListener('DOMContentLoaded', function () {

    /*
    =====================================
    CATEGORY FILTER
    =====================================
    */

    const filterButtons = document.querySelectorAll('.works-filter-btn');
    const workCards = document.querySelectorAll('.work-card');

    if (filterButtons.length && workCards.length) {

        filterButtons.forEach(button => {

            button.addEventListener('click', function (e) {

                e.preventDefault();

                const selectedCategory =
                    this.getAttribute('data-category');

                filterButtons.forEach(btn => {
                    btn.classList.remove('active');
                });

                this.classList.add('active');

                workCards.forEach(card => {

                    const cardCategory =
                        card.getAttribute('data-category');

                    if (
                        selectedCategory === 'all' ||
                        cardCategory === selectedCategory
                    ) {

                        card.style.display = '';

                    } else {

                        card.style.display = 'none';

                    }

                });

            });

        });

    }

    /*
    =====================================
    VIDEO PREVIEW ON HOVER
    =====================================
    */

    const cards = document.querySelectorAll('.work-card');

    cards.forEach(card => {

        const video =
            card.querySelector('.work-card__video');

        if (!video) return;

        card.addEventListener('mouseenter', () => {

            try {

                video.play();

            } catch (error) {

                console.log(error);

            }

        });

        card.addEventListener('mouseleave', () => {

            try {

                video.pause();
                video.currentTime = 0;

            } catch (error) {

                console.log(error);

            }

        });

        video.addEventListener('play', () => {

            video.style.opacity = '1';

        });

        video.addEventListener('pause', () => {

            video.style.opacity = '0';

        });

    });

});