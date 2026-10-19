export default function productCarousel() {
    let observer;
    return {
        hasOverflow: false,
        atStart: true,
        atEnd: true,
        init() {
            this.$nextTick(() => {
                observer = new ResizeObserver(() => this.update());
                observer.observe(this.$refs.track);
                this.update();
            });
        },
        update() {
            const track = this.$refs.track;
            const maximum = track.scrollWidth - track.clientWidth;
            this.hasOverflow = maximum > 2;
            this.atStart = track.scrollLeft <= 2;
            this.atEnd = track.scrollLeft >= maximum - 2;
        },
        move(direction) {
            const track = this.$refs.track;
            const card = track.firstElementChild;
            if (!card) return;
            const step = card.getBoundingClientRect().width + parseFloat(getComputedStyle(track).columnGap || 0);
            track.scrollBy({
                left: direction * step,
                behavior: matchMedia('(prefers-reduced-motion: reduce)').matches ? 'instant' : 'smooth',
            });
        },
        destroy() {
            observer?.disconnect();
        },
    };
}
