// script.js
document.addEventListener('DOMContentLoaded', function() {
    // Real-time search functionality
    const searchInput = document.getElementById('search');
    const projectGrid = document.getElementById('project-grid');
    const noResults = document.getElementById('no-results');
    const projects = Array.from(projectGrid.querySelectorAll('.project-card'));

    searchInput.addEventListener('input', () => {
        const term = searchInput.value.toLowerCase();
        let visibleCount = 0;

        projects.forEach(project => {
            const text = project.textContent.toLowerCase();
            if (text.includes(term)) {
                project.style.display = 'block';
                project.classList.add('transition', 'duration-300', 'ease-in-out');
                visibleCount++;
            } else {
                project.style.display = 'none';
            }
        });

        noResults.style.display = visibleCount === 0 ? 'block' : 'none';
    });

    // Back to top button
    const backToTop = document.getElementById('back-to-top');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            backToTop.style.display = 'block';
        } else {
            backToTop.style.display = 'none';
        }
    });

    backToTop.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
});