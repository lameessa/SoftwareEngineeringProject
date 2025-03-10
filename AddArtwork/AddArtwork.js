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







        // add artwork to profile
        document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("artwork-form").addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent form submission

        const imageInput = document.getElementById("artwork-image");
        const title = document.getElementById("artwork-title").value;
        const category = document.getElementById("artwork-category").value;
        const size = document.getElementById("artwork-size").value;
        const description = document.getElementById("artwork-description").value;
        const listingType = document.querySelector("input[name='listing-type']:checked")?.value;

        if (!listingType) {
            alert("Please select a listing type.");
            return;
        }

        let price = "0.00";
        if (listingType === "marketplace") {
            price = document.getElementById("artwork-price").value || "0.00";
        } else if (listingType === "auction") {
            price = document.getElementById("starting-price").value || "0.00";
            const auctionEnd = document.getElementById("auction-end").value;
        
            if (!auctionEnd) {
                alert("Please select an auction end date.");
                return;
            }
        
            // Save auctionEnd to localStorage if needed
        }
        

        // Handle file selection (store base64 for now)
        const file = imageInput.files[0];
        if (!file) {
            alert("Please select an image for the artwork.");
            return;
        }

        const reader = new FileReader();
        reader.onload = function (event) {
            const imageBase64 = event.target.result;

            let artworks = JSON.parse(localStorage.getItem("artworks")) || [];
            artworks.push({
                id: Date.now().toString(), // Unique ID for deletion tracking
                image: imageBase64,
                title: title,
                category: category,
                size: size,
                description: description,
                price: price,
                listingType: listingType // Save whether it's marketplace or auction
            });

            localStorage.setItem("artworks", JSON.stringify(artworks));

            alert("Artwork added successfully!");

            if (listingType === "marketplace") {
                window.location.href = "../Profile/Profile.html"; // Redirect to profile only for marketplace items
            } else {
                window.location.href = "../Auction/Auction.html"; // Redirect to auction page
            }
        };

        reader.readAsDataURL(file);
    });
});
