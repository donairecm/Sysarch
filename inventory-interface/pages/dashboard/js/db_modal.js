document.addEventListener("DOMContentLoaded", () => {
    const modalOverlay = document.getElementById("dashboardModal");
    const modalContent = document.getElementById("modalContent");
    const closeModal = document.getElementById("closeModal");
    
    // Attach event listeners to all top-row items
    document.querySelectorAll(".top-row").forEach((item) => {
        item.addEventListener("click", () => {
            // Show the modal overlay
            modalOverlay.style.display = "flex";
            
            // Populate the modal with specific content
            const name = item.querySelector(".name").innerText;
            const value = item.querySelector(".value").innerText;
            
            // Create content dynamically
            modalContent.innerHTML = `
                <h2>${name}</h2>
                <p>Value: ${value}</p>
                <p>Additional details about ${name}...</p>
            `;
        });
    });
    
    // Close modal when clicking on the close button
    closeModal.addEventListener("click", () => {
        modalOverlay.style.display = "none";
    });

    // Close modal when clicking outside the modal content
    modalOverlay.addEventListener("click", (e) => {
        if (e.target === modalOverlay) {
            modalOverlay.style.display = "none";
        }
    });
});

