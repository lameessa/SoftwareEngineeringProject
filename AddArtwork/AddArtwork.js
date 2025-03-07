let imageList = [];

function handleImageUpload() {
    let files = document.getElementById("artwork-image").files;
    let previewContainer = document.getElementById("image-preview");

    if (imageList.length + files.length > 4) {
        alert("You can upload up to 4 images.");
        return;
    }

    for (let file of files) {
        if (imageList.length >= 4) break;

        let reader = new FileReader();
        reader.onload = function (event) {
            let imgElement = document.createElement("img");
            imgElement.src = event.target.result;

            let imageContainer = document.createElement("div");
            imageContainer.classList.add("image-container");

            let deleteBtn = document.createElement("button");
            deleteBtn.innerHTML = "&times;";
            deleteBtn.classList.add("delete-btn");
            deleteBtn.onclick = function () {
                imageContainer.remove();
                imageList = imageList.filter(img => img !== imageContainer);
            };

            imageContainer.appendChild(imgElement);
            imageContainer.appendChild(deleteBtn);
            previewContainer.appendChild(imageContainer);
            imageList.push(imageContainer);
        };

        reader.readAsDataURL(file);
    }

    document.getElementById("image-preview-container").classList.remove("hidden");
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
