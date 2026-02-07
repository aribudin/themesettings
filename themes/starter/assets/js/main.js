/**
 * Starter starter Theme - Main JavaScript
 *
 * @package starter 
 * @since 1.0.0
 */

(function() {
    'use strict';

    /**
     * DOM Ready
     */
    document.addEventListener('DOMContentLoaded', function() {
        initMobileMenu();
        initSearchModal();
        initBackToTop();
        initStickyHeader();
        initSmoothScroll();
    });

    /**
     * Mobile Menu Toggle
     */
    function initMobileMenu() {
        const toggle = document.querySelector('.menu-toggle');
        const mobileNav = document.querySelector('.mobile-nav');

        if (!toggle || !mobileNav) return;

        toggle.addEventListener('click', function() {
            const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
            
            toggle.setAttribute('aria-expanded', !isExpanded);
            toggle.classList.toggle('active');
            mobileNav.classList.toggle('active');

            // Prevent body scroll when menu is open
            document.body.style.overflow = isExpanded ? '' : 'hidden';
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!toggle.contains(e.target) && !mobileNav.contains(e.target)) {
                toggle.setAttribute('aria-expanded', 'false');
                toggle.classList.remove('active');
                mobileNav.classList.remove('active');
                document.body.style.overflow = '';
            }
        });

        // Close menu on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileNav.classList.contains('active')) {
                toggle.setAttribute('aria-expanded', 'false');
                toggle.classList.remove('active');
                mobileNav.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    }

    /**
     * Search Modal
     */
    function initSearchModal() {
        const toggle = document.querySelector('.search-toggle');
        const modal = document.getElementById('search-modal');
        const closeBtn = document.querySelector('.search-close');

        if (!toggle || !modal) return;

        toggle.addEventListener('click', function() {
            modal.setAttribute('aria-hidden', 'false');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';

            // Focus search input
            const input = modal.querySelector('.search-field');
            if (input) {
                setTimeout(() => input.focus(), 100);
            }
        });

        if (closeBtn) {
            closeBtn.addEventListener('click', closeSearchModal);
        }

        // Close on backdrop click
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeSearchModal();
            }
        });

        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                closeSearchModal();
            }
        });

        function closeSearchModal() {
            modal.setAttribute('aria-hidden', 'true');
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    /**
     * Back to Top Button
     */
    function initBackToTop() {
        const button = document.getElementById('back-to-top');

        if (!button) return;

        // Show/hide button based on scroll position
        window.addEventListener('scroll', function() {
            if (window.scrollY > 500) {
                button.classList.add('visible');
            } else {
                button.classList.remove('visible');
            }
        }, { passive: true });

        // Scroll to top on click
        button.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    /**
     * Sticky Header
     */
    function initStickyHeader() {
        const header = document.querySelector('.header');

        if (!header || !document.body.classList.contains('has-sticky-header')) return;

        let lastScrollY = 0;
        let ticking = false;

        window.addEventListener('scroll', function() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    handleStickyHeader();
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true });

        function handleStickyHeader() {
            const currentScrollY = window.scrollY;

            if (currentScrollY > 100) {
                header.classList.add('is-scrolled');
            } else {
                header.classList.remove('is-scrolled');
            }

            // Hide header on scroll down, show on scroll up
            if (currentScrollY > lastScrollY && currentScrollY > 200) {
                header.classList.add('is-hidden');
            } else {
                header.classList.remove('is-hidden');
            }

            lastScrollY = currentScrollY;
        }
    }

    /**
     * Smooth Scroll for Anchor Links
     */
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href');
                
                if (targetId === '#') return;

                const target = document.querySelector(targetId);
                
                if (target) {
                    e.preventDefault();
                    
                    const headerHeight = document.querySelector('.header')?.offsetHeight || 0;
                    const targetPosition = target.getBoundingClientRect().top + window.scrollY - headerHeight - 20;

                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });

                    // Update URL hash
                    history.pushState(null, null, targetId);
                }
            });
        });
    }

    /**
     * Load More Posts (AJAX)
     */
    function initLoadMore() {
        const loadMoreBtn = document.querySelector('.load-more');

        if (!loadMoreBtn) return;

        let currentPage = 1;
        let loading = false;

        loadMoreBtn.addEventListener('click', function() {
            if (loading) return;

            loading = true;
            currentPage++;

            loadMoreBtn.classList.add('loading');
            loadMoreBtn.textContent = starterStarterData.i18n.loading;

            const formData = new FormData();
            formData.append('action', 'starter_load_more');
            formData.append('page', currentPage);
            formData.append('nonce', starterStarterData.nonce);

            fetch(starterStarterData.ajaxUrl, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data.trim()) {
                    const postsContainer = document.querySelector('.posts');
                    postsContainer.insertAdjacentHTML('beforeend', data);
                    loadMoreBtn.textContent = 'Load More';
                } else {
                    loadMoreBtn.textContent = starterStarterData.i18n.noMorePosts;
                    loadMoreBtn.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                loadMoreBtn.textContent = starterStarterData.i18n.error;
            })
            .finally(() => {
                loading = false;
                loadMoreBtn.classList.remove('loading');
            });
        });
    }

    /**
     * Lazy Load Images
     */
    function initLazyLoad() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const image = entry.target;
                        
                        if (image.dataset.src) {
                            image.src = image.dataset.src;
                            image.removeAttribute('data-src');
                        }
                        
                        if (image.dataset.srcset) {
                            image.srcset = image.dataset.srcset;
                            image.removeAttribute('data-srcset');
                        }
                        
                        image.classList.add('loaded');
                        observer.unobserve(image);
                    }
                });
            }, {
                rootMargin: '50px 0px'
            });

            document.querySelectorAll('img[data-src]').forEach(function(img) {
                imageObserver.observe(img);
            });
        }
    }

})();
