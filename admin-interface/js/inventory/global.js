// Page direct
function redirectToPage(page) {
    window.location.href = page;  
}

// Page scroll
document.addEventListener('keydown', function(event) {
    // Only trigger on up/down arrow keys
    if (event.key === 'ArrowUp' || event.key === 'ArrowDown') {
        event.preventDefault();
        
        // New margin adjustment for top
        const marginTop = 70;
    
        // Select all the inventory-item elements
        const sectionHeaders = document.querySelectorAll('.inventory-item');
        const currentScrollPosition = window.scrollY;
        
        let currentSectionIndex = 0;
        let nextSectionIndex = 0;
    
        // Calculate current section based on the scroll position
        for (let i = 0; i < sectionHeaders.length; i++) {
            const sectionTop = sectionHeaders[i].offsetTop;
            if (currentScrollPosition < sectionTop - marginTop + 50) {
                currentSectionIndex = i;
                break;
            }
        }
    
        // Determine the next section to scroll to based on the key pressed
        if (event.key === 'ArrowDown') {
            // Move to the next section, but ensure we don't go beyond the last section
            nextSectionIndex = Math.min(currentSectionIndex + 1, sectionHeaders.length - 1);
        } else if (event.key === 'ArrowUp') {
            // Move to the previous section, but ensure we don't go before the first section
            nextSectionIndex = Math.max(currentSectionIndex - 1, 0);
        }
    
        // Scroll to the next or previous section's header smoothly
        const nextSection = sectionHeaders[nextSectionIndex];
        let scrollToPosition;

        if (nextSectionIndex === 0) {
            // For the first section, scroll all the way to the top without margin
            scrollToPosition = 0;
        } else {
            // For other sections, account for the margin at the top
            scrollToPosition = nextSection.offsetTop - marginTop;
        }

        window.scrollTo({
            top: scrollToPosition,
            behavior: 'smooth'
        });
    }
});
