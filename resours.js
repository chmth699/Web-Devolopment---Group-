// ---------- Categories dropdown ----------
const dropdown = document.getElementById('categoryDropdown');
const dropdownToggle = document.getElementById('dropdownToggle');
const dropdownMenu = document.getElementById('dropdownMenu');
const dropdownLabel = document.getElementById('dropdownLabel');
const typeCards = document.querySelectorAll('.type-card');

// Open / close dropdown
dropdownToggle.addEventListener('click', () => {
  dropdown.classList.toggle('open');
});

// Close dropdown if clicking outside of it
document.addEventListener('click', (event) => {
  if (!dropdown.contains(event.target)) {
    dropdown.classList.remove('open');
  }
});

// Handle category selection
dropdownMenu.querySelectorAll('li').forEach((item) => {
  item.addEventListener('click', () => {
    const value = item.getAttribute('data-value');

    // Update selected label
    dropdownLabel.textContent = item.textContent;

    // Highlight the selected item
    dropdownMenu.querySelectorAll('li').forEach((li) => li.classList.remove('selected'));
    item.classList.add('selected');

    // Close the dropdown
    dropdown.classList.remove('open');

    // Filter the resource type cards below
    filterResourceCards(value);
  });
});

function filterResourceCards(category) {
  typeCards.forEach((card) => {
    const cardCategory = card.getAttribute('data-category');

    if (category === 'all' || cardCategory === category) {
      card.classList.remove('dimmed');
    } else {
      card.classList.add('dimmed');
    }
  });
}

// Optional: clicking a resource card also scrolls to it / could link to a filtered resources list
typeCards.forEach((card) => {
  card.addEventListener('click', () => {
    const category = card.getAttribute('data-category');
    console.log('Selected resource type:', category);
    // Example: redirect to a filtered resources listing page
    // window.location.href = `resources-list.html?category=${category}`;
  });
});