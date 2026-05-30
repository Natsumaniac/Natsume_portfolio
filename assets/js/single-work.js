document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.querySelector('.js-sidebar-toggle');
    const sidebar = document.querySelector('.single-work__sidebar');
    const galleryItems = document.querySelectorAll('.js-gallery-item');
    const lightbox = document.querySelector('.js-lightbox');
    const lightboxClose = document.querySelector('.js-lightbox-close');
    const lightboxImage = document.querySelector('.lightbox__image');
    const lightboxPrev = document.querySelector('.js-lightbox-prev');
    const lightboxNext = document.querySelector('.js-lightbox-next');
    const sidebarCards = document.querySelectorAll('.sidebar-card');
    const summaryToggle = document.querySelector('.js-summary-toggle');
    const summary = document.querySelector('.js-summary');

    let activeGalleryIndex = 0;
    let gallerySources = [];

    // ─── Summary Expand/Collapse ────────────────────────────────────
    if (summaryToggle && summary) {
        function checkSummaryOverflow() {
            const isOverflowing = summary.scrollHeight > summary.clientHeight;
            if (isOverflowing) {
                summaryToggle.style.display = 'inline-block';
            } else {
                summaryToggle.style.display = 'none';
            }
        }

        // Check overflow on page load
        checkSummaryOverflow();

        // Check overflow on window resize
        window.addEventListener('resize', checkSummaryOverflow);

        summaryToggle.addEventListener('click', function() {
            const isExpanded = summary.getAttribute('data-expanded') === 'true';
            if (isExpanded) {
                summary.classList.remove('is-expanded');
                summary.setAttribute('data-expanded', 'false');
                summaryToggle.textContent = 'See More';
            } else {
                summary.classList.add('is-expanded');
                summary.setAttribute('data-expanded', 'true');
                summaryToggle.textContent = 'See Less';
            }
        });
    }

    function updateLightbox() {
        if (!lightbox || !lightboxImage) return;
        const current = gallerySources[activeGalleryIndex];
        if (!current) return;
        lightboxImage.src = current.src;
        lightboxImage.alt = current.alt;
    }

    function openLightbox(index) {
        activeGalleryIndex = index;
        updateLightbox();
        if (lightbox) {
            lightbox.classList.add('is-open');
            lightbox.setAttribute('aria-hidden', 'false');
        }
    }

    function closeLightbox() {
        if (lightbox) {
            lightbox.classList.remove('is-open');
            lightbox.setAttribute('aria-hidden', 'true');
        }
    }

    function showPrevious() {
        activeGalleryIndex = (activeGalleryIndex - 1 + gallerySources.length) % gallerySources.length;
        updateLightbox();
    }

    function showNext() {
        activeGalleryIndex = (activeGalleryIndex + 1) % gallerySources.length;
        updateLightbox();
    }

    if (galleryItems.length && lightbox) {
        galleryItems.forEach((item) => {
            const src = item.getAttribute('data-src');
            const alt = item.getAttribute('data-alt') || '';
            gallerySources.push({ src, alt });
            item.addEventListener('click', () => {
                const index = parseInt(item.getAttribute('data-index'), 10);
                openLightbox(index);
            });
        });
    }

    if (lightboxClose) {
        lightboxClose.addEventListener('click', closeLightbox);
    }

    if (lightboxPrev) {
        lightboxPrev.addEventListener('click', showPrevious);
    }

    if (lightboxNext) {
        lightboxNext.addEventListener('click', showNext);
    }

    if (lightbox) {
        lightbox.addEventListener('click', function(event) {
            if (event.target === lightbox) {
                closeLightbox();
            }
        });
    }

    document.addEventListener('keydown', function(event) {
        if (!lightbox.classList.contains('is-open')) return;
        if (event.key === 'Escape') {
            closeLightbox();
        }
        if (event.key === 'ArrowLeft') {
            showPrevious();
        }
        if (event.key === 'ArrowRight') {
            showNext();
        }
    });

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('sidebar-visible');
        });
    }

    sidebarCards.forEach((card) => {
        const video = card.querySelector('.sidebar-card__video');
        if (!video) return;
        card.addEventListener('mouseenter', function() {
            try {
                video.play();
            } catch (error) {
                // ignore playback errors
            }
        });
        card.addEventListener('mouseleave', function() {
            try {
                video.pause();
                video.currentTime = 0;
            } catch (error) {
                // ignore pause errors
            }
        });
    });

    const mediaPlayer = document.querySelector('.single-work__media-player');
    if (mediaPlayer) {
        // Only auto-play on hover for preview-style players without controls
        if (!mediaPlayer.hasAttribute('controls')) {
            mediaPlayer.addEventListener('mouseenter', function() {
                if (mediaPlayer.paused) {
                    mediaPlayer.play().catch(() => {});
                }
            });
            mediaPlayer.addEventListener('mouseleave', function() {
                if (!mediaPlayer.paused) {
                    mediaPlayer.pause();
                }
            });
        }

        // Playback rate selector (if present)
        const playbackSelect = document.querySelector('.js-playback-rate');
        if (playbackSelect) {
            playbackSelect.addEventListener('change', function() {
                const rate = parseFloat(this.value) || 1;
                try {
                    mediaPlayer.playbackRate = rate;
                } catch (e) {}
            });
        }
    }

    const cardThumbs = document.querySelectorAll('.sidebar-card__thumb-wrap');
    cardThumbs.forEach((thumb) => {
        thumb.addEventListener('mouseenter', function() {
            thumb.style.transform = 'scale(1.02)';
        });
        thumb.addEventListener('mouseleave', function() {
            thumb.style.transform = 'scale(1)';
        });
    });


    const shareBtn = document.querySelector('.js-share-project');

if (shareBtn) {

    shareBtn.addEventListener('click', async () => {

        if (navigator.share) {

            navigator.share({
                title: document.title,
                url: window.location.href
            });

        } else {

            navigator.clipboard.writeText(window.location.href);

            alert('Project link copied!');
        }
    });

}
});