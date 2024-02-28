function goBackInventory() {
    window.history.back();
}

document.addEventListener('DOMContentLoaded', (event) => {
    if (window.location.pathname.includes('create_inventory.php')){
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0'); // get day and add a leading zero if needed
        var mm = String(today.getMonth() + 1).padStart(2, '0'); // get month (0-based) and add a leading zero if needed
        var yyyy = today.getFullYear();

        var formattedDate = yyyy + '-' + mm + '-' + dd;

        // Set the minimum value of the date input
        var itemExpiryDate = document.getElementById('itemExpiryDate');
        itemExpiryDate.setAttribute('min', formattedDate);
    }
});
