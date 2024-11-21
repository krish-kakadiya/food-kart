// Toggle the profile sidebar
function toggleSidebar() {
    const sidebar = document.getElementById('profile-sidebar');
    sidebar.classList.toggle('active');
}

function addToCart(menuItemId) {
    // Send a request to add the item to the cart
    fetch('add_to_cart.php', {
        method: 'POST',
        body: new URLSearchParams({
            'menu_item_id': menuItemId
        })
    })
    .then(response => response.text())
    .then(data => {
        // Show the pop-up message
        alert(data);  // You can customize this to show a nicer pop-up message
    })
    .catch(error => console.error('Error:', error));
}
