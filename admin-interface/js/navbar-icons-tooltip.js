document.addEventListener('DOMContentLoaded', () => {
    const icons = document.querySelectorAll('.ic-container');
    const tooltip = document.createElement('div');
    tooltip.classList.add('tooltip');
    document.body.appendChild(tooltip);

    icons.forEach(icon => {
        icon.addEventListener('mouseenter', (e) => {
            const tooltipText = e.currentTarget.getAttribute('data-tooltip');
            tooltip.textContent = tooltipText;
            tooltip.style.opacity = '1';
        });

        icon.addEventListener('mousemove', (e) => {
            tooltip.style.left = `${e.pageX}px`;
            tooltip.style.top = `${e.pageY}px`;
        });

        icon.addEventListener('mouseleave', () => {
            tooltip.style.opacity = '0';
        });
    });
});
