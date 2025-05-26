
document.addEventListener('DOMContentLoaded', function () {
    const carousel = document.querySelector('#clientesCarousel');
    if (carousel) {
        const interval = 3000; // 3 segundos

        setInterval(() => {
            const active = carousel.querySelector('.carousel-item.active');
            let next = active.nextElementSibling;
            if (!next || !next.classList.contains('carousel-item')) {
                next = carousel.querySelector('.carousel-item:first-child');
            }

            active.classList.remove('active');
            next.classList.add('active');
        }, interval);
    }
});
