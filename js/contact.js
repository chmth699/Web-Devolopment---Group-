// ---------- Contact form validation ----------
const form = document.getElementById('contactForm');
const successMsg = document.getElementById('formSuccess');

const fields = {
  name: {
    input: document.getElementById('name'),
    error: document.getElementById('nameError'),
    validate: (value) => value.trim().length >= 2,
    message: 'Please enter your full name.'
  },
  email: {
    input: document.getElementById('email'),
    error: document.getElementById('emailError'),
    validate: (value) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value.trim()),
    message: 'Please enter a valid email address.'
  },
  subject: {
    input: document.getElementById('subject'),
    error: document.getElementById('subjectError'),
    validate: (value) => value.trim().length >= 3,
    message: 'Please enter a subject.'
  },
  message: {
    input: document.getElementById('message'),
    error: document.getElementById('messageError'),
    validate: (value) => value.trim().length >= 10,
    message: 'Message should be at least 10 characters.'
  }
};

// Validate a single field and show/hide its error
function validateField(key) {
  const field = fields[key];
  const value = field.input.value;
  const isValid = field.validate(value);

  field.error.textContent = isValid ? '' : field.message;
  field.input.closest('.form-group').classList.toggle('invalid', !isValid);

  return isValid;
}

// Live validation as the user types
Object.keys(fields).forEach((key) => {
  fields[key].input.addEventListener('input', () => validateField(key));
});

// Handle form submission
form.addEventListener('submit', (event) => {
  event.preventDefault();

  let allValid = true;
  Object.keys(fields).forEach((key) => {
    const valid = validateField(key);
    if (!valid) allValid = false;
  });

  if (!allValid) {
    // Block submission until every field passes client-side validation.
    event.preventDefault();
    if (successMsg) successMsg.classList.remove('show');
    return;
  }

  // All fields look good client-side: let the form submit normally to
  // contact.php, which re-validates on the server and saves the message
  // to the database. The success banner is then shown by PHP after the
  // redirect (see contact.php), not here.
});