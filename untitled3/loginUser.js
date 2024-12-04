// Mock data
const mockUserData = {
    orders: ["Order #1: Laptop", "Order #2: Smartphone", "Order #3: Headphones"],
    wishlist: ["Gaming Mouse", "Mechanical Keyboard", "Curved Monitor"],
    reviews: {}
};

// Elements
const sections = document.querySelectorAll(".section");
const sidebarItems = document.querySelectorAll(".sidebar li");
const ordersList = document.getElementById("orders-list");
const wishlistList = document.getElementById("wishlist-list");
const reviewForm = document.getElementById("review-form-element");
const sidebar = document.getElementById("sidebar");
const hamburgerMenu = document.getElementById("hamburger-menu");

// Populate data on page load
document.addEventListener("DOMContentLoaded", function () {
    populateList(ordersList, mockUserData.orders);
    populateList(wishlistList, mockUserData.wishlist);

    // Activate the first section (orders)
    activateSection("orders");
});

// Populate list function
function populateList(listElement, items) {
    listElement.innerHTML = ""; // Clear previous items
    items.forEach((item) => {
        const listItem = document.createElement("li");
        listItem.textContent = item;
        listElement.appendChild(listItem);
    });
}

// Sidebar navigation
sidebarItems.forEach((item) => {
    item.addEventListener("click", () => {
        // Remove active class from all items
        sidebarItems.forEach((i) => i.classList.remove("active"));
        // Add active class to the clicked item
        item.classList.add("active");
        // Show the selected section
        const sectionId = item.dataset.section;
        activateSection(sectionId);
        // Close the sidebar on small screens after clicking a menu item
        if (window.innerWidth <= 768) {
            sidebar.classList.remove("open");
        }
    });
});

// Activate a specific section
function activateSection(sectionId) {
    sections.forEach((section) => {
        section.style.display = section.id === sectionId ? "block" : "none";
    });
}

// Handle review form submission
reviewForm.addEventListener("submit", function (event) {
    event.preventDefault();
    const itemName = document.getElementById("item-name").value.trim();
    const reviewText = document.getElementById("review-text").value.trim();

    if (itemName && reviewText) {
        mockUserData.reviews[itemName] = reviewText;
        alert(`Review added for ${itemName}: "${reviewText}"`);
        reviewForm.reset(); // Clear the form
    }
});

// Toggle sidebar visibility on small screens
function toggleMenu() {
    sidebar.classList.toggle("open");
}
