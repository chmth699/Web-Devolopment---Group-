// ---------- Categories dropdown (native <select>) ----------
const categorySelect = document.getElementById('categorySelect');
const typeCards = document.querySelectorAll('.type-card');

if (categorySelect) {
  categorySelect.addEventListener('change', () => {
    filterResourceCards(categorySelect.value);
  });
}

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

// Clicking a resource card also selects its category in the dropdown,
// so the two controls always stay in sync.
typeCards.forEach((card) => {
  card.addEventListener('click', () => {
    const category = card.getAttribute('data-category');
    if (categorySelect) {
      categorySelect.value = category;
    }
    filterResourceCards(category);
  });
});