/**
 * 
 * @var thresholdDistance Minimal distance (in px) to trigger swipe to next/previous slide during swipes
 * @export
 * @interface SliderInterface
 */
export default interface SliderInterface {
    effect?: string | 'slide' | 'fade' | 'coverflow' | 'nest';
    loop?: boolean;
    currentPage?: number;
    direction?: string | 'horizontal' | 'vertical';
    autoplay?: number | 1000;
    pagination?: boolean;
    renderPagination?: (h, index: number) => {};
    thresholdDistance?: number | 500;
    thresholdTime?: number | 100;
    loopedSlides?: number | 1;
    slidesToScroll?: number | 1;
    freeze?: boolean;
    infinite?: number | 1;
}
