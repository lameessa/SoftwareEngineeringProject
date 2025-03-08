document.addEventListener("DOMContentLoaded", function () {
    initializeDefaultArtworks(); // Ensure default artworks are stored
    loadArtworks(); // Load artworks from Local Storage and display them

    document.getElementById("delete-artwork").addEventListener("click", function () {
        deleteSelectedArtworks();
    });

    document.getElementById("add-artwork").addEventListener("click", function () {
        window.location.href = "../AddArtwork/AddArtwork.html"; // Redirect to Add Artwork page
    });
});

// Function to initialize default artworks in Local Storage (Runs only once)
function initializeDefaultArtworks() {
    if (!localStorage.getItem("initialized")) {
        let defaultArtworks = [
            {
                id: "1",
                image: "../images/Vincent van Gogh - Roses, 1889 at National Museum of Western Art - Tokyo Japan.jpeg",
                title: "Roses (1889)",
                description: "Burst of green and pink, wild and untamed. Roses, painted in thick strokes, within the asylum walls, nature’s beauty flourishes.",
                category: "Abstract",
                size: "40x50 cm",
                price: "$500",
                listingType: "marketplace"
            },
            {
                id: "2",
                image: "../images/Potato Eaters by Vincent Van Gogh Poster _ Zazzle.jpeg",
                title: "The Potato Eaters (1885)",
                description: "A simple meal, shared by those who work the land. Their hands, rough and calloused, have dug the very potatoes they now eat.",
                category: "Realism",
                size: "13x18 cm",
                price: "$700",
                listingType: "marketplace"
            },
            {
                id: "3",
                image: "../images/Vincent van Gogh almond blossom.jpeg",
                title: "Almond Blossoms (1890)",
                description: "Soft white petals against a blue sky—delicate yet strong. The almond tree, the first to bloom after winter, is a symbol of hope.",
                category: "Expressionism",
                size: "10x15 cm",
                price: "$400",
                listingType: "marketplace"
            }
        ];
        localStorage.setItem("artworks", JSON.stringify(defaultArtworks));
        localStorage.setItem("initialized", "true");
    }
}

// Function to load artworks from Local Storage and display them
function loadArtworks() {
    let artworks = JSON.parse(localStorage.getItem("artworks")) || [];
    const artContainer = document.querySelector(".art-grid");

    // Clear existing artworks before loading
    artContainer.innerHTML = "";

    artworks.forEach((art) => {
        const artItem = document.createElement("div");
        artItem.classList.add("art-item");

        artItem.innerHTML = `
            <input type="checkbox" class="delete-checkbox" data-id="${art.id}">
            <img src="${art.image}" alt="${art.title}">
            <h3>${art.title}</h3>
            <p>${art.description}</p>
            <p>Category: ${art.category}</p>
            <p>${art.size}</p>
            <p>${art.price}</p>
            <p>Available</p>
            <p><a href="../Edit/Edit.html" class="edit-icon">✎</a></p>
        `;

        artContainer.appendChild(artItem);
    });

    console.log("Loaded artworks:", artworks); // Debugging: Check loaded artworks
}
function deleteSelectedArtworks() {
    let artworks = JSON.parse(localStorage.getItem("artworks")) || [];
    
    // Get all checkboxes BEFORE making changes
    const checkboxes = Array.from(document.querySelectorAll(".delete-checkbox:checked"));

    // Check if any checkbox is selected before proceeding
    if (checkboxes.length === 0) {
        alert("Please select at least one artwork to delete.");
        return;
    }

    // Extract the IDs of selected artworks
    let idsToDelete = checkboxes.map(checkbox => checkbox.dataset.id);

    console.log("IDs to delete:", idsToDelete); // Debugging: Check selected IDs

    // Remove the selected artworks from the Local Storage list
    let updatedArtworks = artworks.filter(art => !idsToDelete.includes(art.id));

    console.log("Remaining artworks after deletion:", updatedArtworks); // Debugging: Check remaining artworks

    // Update Local Storage with the remaining artworks
    localStorage.setItem("artworks", JSON.stringify(updatedArtworks));

    // Reload artworks after deletion to update the UI
    loadArtworks();
}
