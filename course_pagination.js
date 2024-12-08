document.addEventListener('DOMContentLoaded', () => {
    const itemsPerPage = 10; // Maximum items per page
    let currentPage = 0; // Current page index (0-based)
    const courseItems = document.querySelectorAll('.course_item'); // Course items
    const prevButton = document.getElementById('prev_page'); // "Previous" button
    const nextButton = document.getElementById('next_page'); // "Next" button
    const pageIndicator = document.getElementById('page_indicator'); // Page indicator span

    // Function to update pagination display
    function updatePagination() {
        const start = currentPage * itemsPerPage;
        const end = start + itemsPerPage;

        // Show only items for the current page
        courseItems.forEach((item, index) => {
            item.style.display = index >= start && index < end ? 'block' : 'none';
        });

        // Update page indicator
        const totalPages = Math.ceil(courseItems.length / itemsPerPage);
        pageIndicator.textContent = `Page ${currentPage + 1} of ${totalPages}`;

        // Enable/Disable navigation buttons
        prevButton.disabled = currentPage === 0;
        nextButton.disabled = currentPage + 1 >= totalPages;
    }

    // Event listeners for "Previous" and "Next" buttons
    prevButton.addEventListener('click', () => {
        if (currentPage > 0) {
            currentPage--;
            updatePagination();
        }
    });

    nextButton.addEventListener('click', () => {
        const totalPages = Math.ceil(courseItems.length / itemsPerPage);
        if (currentPage + 1 < totalPages) {
            currentPage++;
            updatePagination();
        }
    });

    // Initialize pagination
    updatePagination();
});
