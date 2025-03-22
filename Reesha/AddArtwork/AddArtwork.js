// Image Preview - Keep this to preview the selected image
function handleImageUpload() {
    let input = document.getElementById("artwork-image");
    let imagePreview = document.getElementById("image-preview");

    if (input.files.length > 0) {
        let file = input.files[0];
        let reader = new FileReader();

        reader.onload = function (e) {
            imagePreview.innerHTML = `
                <div class="image-container">
                    <img src="${e.target.result}" alt="Uploaded Artwork">
                </div>
            `;
        };

        reader.readAsDataURL(file);
    }
}

// Toggle between marketplace and auction options
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

    // Clear any selected listing type on load
    let listingRadios = document.querySelectorAll('input[name="listing-type"]');
    listingRadios.forEach(radio => radio.checked = false);

    // Hide both fields initially
    document.getElementById("marketplace-fields").classList.add("hidden");
    document.getElementById("auction-fields").classList.add("hidden");

    // Redirects for icons (optional, if you're using links in <a>)
    document.querySelector(".logo").addEventListener("click", function () {
        window.location.href = "../Home/index.php"; // Changed to .php if you're now using PHP for routing
    });

    document.querySelector("#wishlist-header").addEventListener("click", function () {
        window.location.href = "../Wishlist/Wishlist.php";
    });

    document.querySelector("#profile-header").addEventListener("click", function () {
        window.location.href = "../Profile/Profile.php";
    });

    // Price formatting for marketplace and auction prices
    function formatPriceInput(event) {
        let input = event.target;
        let value = input.value.replace(/[^0-9.]/g, "");

        if (!input.value.startsWith("$")) {
            input.value = "$" + value;
        } else {
            input.value = "$" + value;
        }

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

    let priceFields = document.querySelectorAll("#artwork-price, #starting-price");

    priceFields.forEach(field => {
        field.addEventListener("input", formatPriceInput);
        field.addEventListener("focus", ensureDollarSign);
    });

});
