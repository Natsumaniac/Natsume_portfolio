/* central JS for beyond content loaded below */

// Music card: playlist navigation and sync
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.music-card').forEach(card => {
        const data = card.getAttribute('data-music') || '[]';
        let items = [];
        try { items = JSON.parse(data); } catch(e){ items = []; }

        const player = card.querySelector('#music-player');
        const playBtn = card.querySelector('.music-play');
        const prevBtn = card.querySelector('.music-prev');
        const nextBtn = card.querySelector('.music-next');
        const favBtn = card.querySelector('.music-fav');
        const coverImg = card.querySelector('.music-cover img');
        const titleEl = card.querySelector('.music-title');

        if (!player) return;
        let idx = 0;

        const loadMusic = (i, autoplay = false) => {
            if (!items.length) return;
            idx = (i + items.length) % items.length;
            const it = items[idx];
            if (it.audio) {
                player.src = it.audio;
                player.load();
            }
            if (coverImg) coverImg.src = it.cover || '';
            if (titleEl) titleEl.textContent = it.title || '';
            // update play icon to pause if autoplay
            if (autoplay) {
                player.play().catch(()=>{});
                if (playBtn) playBtn.innerHTML = '<i class="fa-solid fa-pause"></i>';
            }
        };

        // initialize
        if (items.length) loadMusic(0, false);

        if (prevBtn) prevBtn.addEventListener('click', () => {
            loadMusic(idx - 1, true);
        });

        if (nextBtn) nextBtn.addEventListener('click', () => {
            loadMusic(idx + 1, true);
        });

        if (playBtn) playBtn.addEventListener('click', () => {
            if (player.paused) {
                player.play();
                playBtn.innerHTML = '<i class="fa-solid fa-pause"></i>';
            } else {
                player.pause();
                playBtn.innerHTML = '<i class="fa-solid fa-play"></i>';
            }
        });

        if (player) {
            player.addEventListener('play', () => {
                if (playBtn) playBtn.innerHTML = '<i class="fa-solid fa-pause"></i>';
            });
            player.addEventListener('pause', () => {
                if (playBtn) playBtn.innerHTML = '<i class="fa-solid fa-play"></i>';
            });
        }

        if (favBtn) favBtn.addEventListener('click', () => {
            favBtn.classList.toggle('fav-active');
        });
    });
});

// Singing controls
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.centered-wave-player').forEach(card => {
        const audio = card.querySelector('#singing-player');
        const playBtn = card.querySelector('.singing-play');
        const fav = card.querySelector('.singing-fav');

        if (!audio) return;

        if (playBtn) playBtn.addEventListener('click', () => {
            if (audio.paused) {
                audio.play().catch(()=>{});
            } else {
                audio.pause();
            }
        });
        if (fav) fav.addEventListener('click', () => fav.classList.toggle('fav-active'));

        audio.addEventListener('play', () => {
            if (playBtn) playBtn.innerHTML = '<i class="fa-solid fa-pause"></i>';
        });
        audio.addEventListener('pause', () => {
            if (playBtn) playBtn.innerHTML = '<i class="fa-solid fa-play"></i>';
        });
        audio.addEventListener('ended', () => {
            if (playBtn) playBtn.innerHTML = '<i class="fa-solid fa-play"></i>';
        });
    });
});

/* Movie & Book carousel setup */
document.addEventListener('DOMContentLoaded', () => {

    const setupCarousel = (selector) => {
        const carousels = document.querySelectorAll(selector);

        carousels.forEach((carousel) => {
            // gather direct child slides (supports img, p, div, etc.)
            const slides = Array.from(carousel.children).filter(n => n.nodeType === 1);
            if (!slides.length) return;

            // create track and move slides into it
            const track = document.createElement('div');
            track.className = 'carousel-track';

            slides.forEach(slide => {
                slide.classList.add('carousel-item');
                track.appendChild(slide);
            });

            // add clones for infinite loop
            const firstClone = track.children[0].cloneNode(true);
            const lastClone = track.children[track.children.length - 1].cloneNode(true);
            track.appendChild(firstClone);
            track.insertBefore(lastClone, track.firstChild);

            // clear carousel and append track
            carousel.innerHTML = '';
            carousel.appendChild(track);

            let index = 1; // start after the prepended clone
            // slide width will be derived from the first slide element (CSS-driven)
            let slideWidth = 0;
            const transitionDuration = 700; // ms
            const autoPlayDelay = 4500; // ms
            let intervalId = null;

            const setPosition = (skipTransition = false) => {
                if (skipTransition) track.style.transition = 'none';
                else track.style.transition = `transform ${transitionDuration}ms ease`;

                track.style.transform = `translateX(-${index * slideWidth}px)`;
            };

            // initial sizing: let CSS dictate slide size, then measure
            track.style.display = 'flex';
            Array.from(track.children).forEach(child => {
                child.style.flex = '0 0 auto';
            });

            // measure slide width after DOM insertion
            const measure = () => {
                // first real slide is at index 1 because of the prepended clone
                const firstSlide = track.children[1];
                slideWidth = firstSlide ? firstSlide.getBoundingClientRect().width : carousel.clientWidth;
                setPosition(true);
            };

            measure();

            // handle transition end for infinite loop
            track.addEventListener('transitionend', () => {
                const childrenCount = track.children.length;
                if (index >= childrenCount - 1) {
                    index = 1;
                    setPosition(true);
                }
                if (index <= 0) {
                    index = childrenCount - 2;
                    setPosition(true);
                }
            });

            const next = () => {
                index++;
                setPosition(false);
            };

            const startAutoPlay = () => {
                if (intervalId) return;
                intervalId = setInterval(next, autoPlayDelay);
            };

            const stopAutoPlay = () => {
                if (!intervalId) return;
                clearInterval(intervalId);
                intervalId = null;
            };

            // pause on hover
            carousel.addEventListener('mouseenter', stopAutoPlay);
            carousel.addEventListener('mouseleave', startAutoPlay);

            // handle resize
            window.addEventListener('resize', () => {
                measure();
            });

            // start autoplay
            startAutoPlay();
        });
    };

    setupCarousel('.movie-carousel');
    setupCarousel('.book-carousel');
    setupCarousel('.quote-rotator');

});

// Dance video navigation
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.dance-wrapper').forEach(wrapper => {
        const data = wrapper.getAttribute('data-videos') || '[]';
        let videos = [];
        try { videos = JSON.parse(data); } catch(e){ videos = []; }

        const video = wrapper.querySelector('video');
        const prev = wrapper.querySelector('.dance-prev');
        const next = wrapper.querySelector('.dance-next');

        if (!video) return;
        let idx = 0;

        const loadAt = (n, autoplay = false) => {
            if (!videos.length) return;
            idx = (n + videos.length) % videos.length;
            video.pause();
            video.src = videos[idx];
            video.load();
            if (autoplay) video.play().catch(()=>{});
        };

        // initialize
        if (videos.length) {
            if (!video.querySelector('source')) video.src = videos[0];
            else video.src = videos[0];
        }

        if (prev) prev.addEventListener('click', (e) => {
            e.preventDefault();
            loadAt(idx - 1, true);
        });

        if (next) next.addEventListener('click', (e) => {
            e.preventDefault();
            loadAt(idx + 1, true);
        });
    });
});
