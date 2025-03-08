let imageList = [];

function handleImageUpload() {
    let input = document.getElementById("artwork-image");
    let imagePreview = document.getElementById("image-preview");

    if (input.files.length > 0) {
        let file = input.files[0]; // Get only the first file
        let reader = new FileReader();

        reader.onload = function (e) {
            // Replace existing image (allows only one)
            imagePreview.innerHTML = `
                <div class="image-container">
                    <img src="${e.target.result}" alt="Uploaded Artwork">
                </div>
            `;
        };

        reader.readAsDataURL(file);
    }
}

document.addEventListener("DOMContentLoaded", function () {
    let listingRadios = document.querySelectorAll('input[name="listing-type"]');
    listingRadios.forEach(radio => radio.checked = false);
    
    document.getElementById("marketplace-fields").classList.add("hidden");
    document.getElementById("auction-fields").classList.add("hidden");
});

function toggleListingType() {
    let selectedOption = document.querySelector('input[name="listing-type"]:checked');

    if (selectedOption) {
        let listingType = selectedOption.value;

        if (listingType === "marketplace") {
            document.getElementById("marketplace-fields").classList.remove("hidden");
            document.getElementById("auction-fields").classList.add("hidden");
        } else if (listingType === "auction") {
            document.getElementById("auction-fields").classList.remove("hidden");
            document.getElementById("marketplace-fields").classList.add("hidden");
        }
    }
}

document.addEventListener("DOMContentLoaded", function () {
    function formatPriceInput(event) {
        let input = event.target;
        let value = input.value.replace(/[^0-9.]/g, ""); // Remove everything except numbers and "."

        // Ensure the first character is "$"
        if (!input.value.startsWith("$")) {
            input.value = "$" + value;
        } else {
            input.value = "$" + value;
        }

        // Prevent multiple decimal points
        let parts = input.value.split(".");
        if (parts.length > 2) {
            input.value = "$" + parts[0] + "." + parts.slice(1).join("");
        }
    }

    function ensureDollarSign(event) {
        let input = event.target;
        if (input.value.trim() === "") {
            input.value = "$";
        }
    }

    // Select both the Marketplace Price and Auction Starting Price fields
    let priceFields = document.querySelectorAll("#artwork-price, #starting-price");
    
    // Apply event listeners
    priceFields.forEach(field => {
        field.addEventListener("input", formatPriceInput);
        field.addEventListener("focus", ensureDollarSign); // Adds "$" if empty when clicked
    });
});
	// JavaScript to handle the clicks for the elements
	document.addEventListener("DOMContentLoaded", function() {

        // Redirect to index.html when clicking on the logo
        document.querySelector(".logo").addEventListener("click", function() {
            window.location.href = "../Home/index.html";
        });
        
        // Redirect to Wishlist.html when clicking on the wishlist icon
        document.querySelector("#wishlist-header").addEventListener("click", function() {
            window.location.href = "../Wishlist/Wishlist.html";
        });
        
        // Redirect to Profile.html when clicking on the profile icon
        document.querySelector("#profile-header").addEventListener("click", function() {
            window.location.href = "../Profile/Profile.html";
        });
        
        });