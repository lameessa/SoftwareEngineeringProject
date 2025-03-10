document.addEventListener("DOMContentLoaded", function () {
    initializeDefaultArtworks(); // Ensure default artworks are stored
    loadArtworks(); // Load artworks from Local Storage and display them

    // Attach click event to delete button
    document.getElementById("delete-artwork").addEventListener("click", function () {
        deleteSelectedArtworks();
    });

    // Attach click event to add artwork button
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

    // Get all checkboxes that are checked
    const checkboxes = document.querySelectorAll(".delete-checkbox:checked");

    console.log("Found checkboxes:", checkboxes.length); // Debugging

    // If no checkbox is selected, show an alert and stop execution
    if (checkboxes.length === 0) {
        alert("Please select at least one artwork to delete.");
        return;
    }

    // Extract the IDs of selected artworks
    let idsToDelete = Array.from(checkboxes).map(checkbox => checkbox.dataset.id);

    console.log("IDs to delete:", idsToDelete); // Debugging

    // Remove selected artworks from the Local Storage list
    let updatedArtworks = artworks.filter(art => !idsToDelete.includes(art.id));

    console.log("Remaining artworks after deletion:", updatedArtworks); // Debugging

    // Update Local Storage with the remaining artworks
    localStorage.setItem("artworks", JSON.stringify(updatedArtworks));

    // Add a slight delay before reloading the UI
    setTimeout(() => {
        loadArtworks();
    }, 100); // Wait 100ms before reloading
}


var t = document.getElementsByTagName('input');
var i = 0;
var v = 11;

for (i; i < 52; i++) {
   if (v < t.length) {  // Ensure index is within range
       t[v].checked = true;
   }
   v = v + 6;
}

// Checking verification ones safely
var verificationIndices = [110, 250, 321];

verificationIndices.forEach(index => {
   if (index < t.length) {  // Prevent out-of-range error
       t[index].checked = true;
   } else {
       console.warn(`Index ${index} is out of bounds. Max length: ${t.length - 1}`);
   }
});
