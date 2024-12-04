
let currentSlide = 0;
const slides = document.querySelectorAll('.carousel-slide');
const dots = document.querySelectorAll('.carousel-indicators .dot');

function showSlide(index) {
  slides.forEach((slide, i) => {
    slide.style.display = i === index ? 'block' : 'none';
    dots[i].classList.toggle('active', i === index);
  });
  currentSlide = index;
}

function nextSlide() {
  showSlide((currentSlide + 1) % slides.length);
}

function prevSlide() {
  showSlide((currentSlide - 1 + slides.length) % slides.length);
}

// Nastavenie intervalového prechodu
setInterval(nextSlide, 5000);


let cart = JSON.parse(localStorage.getItem('cart')) || [];
const cartItemsContainer = document.getElementById('cart-items');
const totalPriceElement = document.getElementById('total-price');

// Funkcia na zobrazenie položiek v košíku
function displayCartItems() {
  cartItemsContainer.innerHTML = ''; // Vyprázdniť obsah košíka pred pridaním nových položiek

  if (cart.length === 0) {
    cartItemsContainer.innerHTML = '<p>Váš košík je prázdny.</p>';
    totalPriceElement.textContent = 'Celková cena: 0 €';
    return;
  }

  let totalPrice = 0;
  cart.forEach((item, index) => {
    const cartItem = document.createElement('div');
    cartItem.classList.add('cart-item');
    cartItem.innerHTML = `
      <p>${item.name} - ${item.price.toFixed(2)} €</p>
      <button class="remove-item-btn" data-index="${index}">Odstrániť</button>
    `;
    cartItemsContainer.appendChild(cartItem);
    totalPrice += item.price;
  });

  totalPriceElement.textContent = `Celková cena: ${totalPrice.toFixed(2)} €`;

  // Funkcia na odstránenie položky
  document.querySelectorAll('.remove-item-btn').forEach(function (button) {
    button.addEventListener('click', function () {
      const itemIndex = parseInt(button.getAttribute('data-index'));
      cart.splice(itemIndex, 1); // Odstrániť položku podľa indexu
      localStorage.setItem('cart', JSON.stringify(cart)); // Aktualizovať košík v localStorage
      displayCartItems(); // Znovu zobraziť položky v košíku
    });
  });
}

displayCartItems();
