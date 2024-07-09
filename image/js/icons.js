/* icons.js */
document.addEventListener("DOMContentLoaded", function() {
    const iconsPerPage = 10;
    const iconWrappers = document.querySelectorAll(".filter-icon-wrapper");
    const totalPages = Math.ceil(iconWrappers.length / iconsPerPage);
    let currentPage = 1;

    function showPage(page) {
        iconWrappers.forEach((wrapper, index) => {
            wrapper.style.display = (index >= (page - 1) * iconsPerPage && index < page * iconsPerPage) ? "block" : "none";
        });
    }

    function updateButtons() {
        document.getElementById("prev-btn").style.display = currentPage === 1 ? "none" : "inline-block";
        document.getElementById("next-btn").style.display = currentPage === totalPages ? "none" : "inline-block";
    }

    document.getElementById("prev-btn").addEventListener("click", function() {
        if (currentPage > 1) {
            currentPage--;
            showPage(currentPage);
            updateButtons();
        }
    });

    document.getElementById("next-btn").addEventListener("click", function() {
        if (currentPage < totalPages) {
            currentPage++;
            showPage(currentPage);
            updateButtons();
        }
    });

    // Initialize
    showPage(currentPage);
    updateButtons();
});
