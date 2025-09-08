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


/* Attach click listeners to project cards and redirect to current URL + /<project> */

// Find all tiles that have a data-project attribute
document.querySelectorAll('.project-card[data-project]').forEach(function(card) {
  // When a tile is clicked...
  card.addEventListener('click', function(event) {
    // Read the raw project name from the data attribute
    var projectName = card.getAttribute('data-project');
    if (!projectName) return; // nothing to do if missing

    // Build a URL object from the current location (preserves scheme, host, port)
    var url = new URL(window.location.href);

    // Normalize current path by removing any trailing slashes
    var currentPath = url.pathname.replace(/\/+$/, '');

    // Encode the project name safely for a path segment
    var encoded = encodeURIComponent(projectName);

    // Prevent redirect loop: if the path already ends with the project segment, do nothing
    if (currentPath.endsWith('/' + encoded) || currentPath.endsWith('/' + projectName)) {
      return;
    }

    // Append the encoded project name as a new path segment
    url.pathname = currentPath + '/' + encoded;

    // Perform the redirect
    window.location.href = url.toString();
  });
});