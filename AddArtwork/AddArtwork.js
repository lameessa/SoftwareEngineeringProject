document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("image-preview-container").classList.add("hidden");
    document.getElementById("auction-fields").classList.add("hidden");
});

function toggleListingType() {
    let selectedOption = document.querySelector('input[name="listing-type"]:checked').value;
    document.getElementById("marketplace-fields").classList.toggle("hidden", selectedOption !== "marketplace");
    document.getElementById("auction-fields").classList.toggle("hidden", selectedOption !== "auction");
}

function previewImages() {
    let files = document.getElementById("artwork-image").files;
    let previewContainer = document.getElementById("image-preview");
    previewContainer.innerHTML = "";

    if (files.length > 0) {
        document.getElementById("image-preview-container").classList.remove("hidden");

        for (let i = 0; i < files.length; i++) {
            let file = files[i];
            let reader = new FileReader();

            reader.onload = function (event) {
                let imageContainer = document.createElement("div");
                imageContainer.classList.add("image-container");

                let img = document.createElement("img");
                img.src = event.target.result;

                let order = document.createElement("span");
                order.textContent = i + 1;

                let deleteBtn = document.createElement("button");
                deleteBtn.textContent = "X";
                deleteBtn.classList.add("delete-btn");
                deleteBtn.onclick = function () {
                    imageContainer.remove();
                    updateImageOrder();
                };

                imageContainer.appendChild(img);
                imageContainer.appendChild(order);
                imageContainer.appendChild(deleteBtn);
                previewContainer.appendChild(imageContainer);
            };

            reader.readAsDataURL(file);
        }
    }
}

function updateImageOrder() {
    let images = document.querySelectorAll("#image-preview .image-container span");
    images.forEach((span, index) => {
        span.textContent = index + 1;
    });
}
