document.addEventListener("DOMContentLoaded", function () {
    const deleteButton = document.getElementById("delete-artwork");
    const addButton = document.getElementById("add-artwork");
    const artGrid = document.querySelector(".art-grid");

    // Load stored artworks from localStorage
    loadArtworks();

    // DELETE selected artworks
    deleteButton.addEventListener("click", function () {
        const selectedArtworks = document.querySelectorAll(".delete-checkbox:checked");

        if (selectedArtworks.length === 0) {
            alert("Please select artworks to delete.");
            return;
        }

        let artworks = JSON.parse(localStorage.getItem("artworks")) || [];

        selectedArtworks.forEach((checkbox) => {
            const artItem = checkbox.parentElement;
            const title = artItem.querySelector("h3").textContent;

            // Remove the item from the DOM
            artItem.remove();

            // Remove from localStorage by filtering it out
            artworks = artworks.filter(art => art.title !== title);
        });

        // Update localStorage
        localStorage.setItem("artworks", JSON.stringify(artworks));
    });

    // ADD NEW ARTWORK - Redirect to AddArtwork.html
    addButton.addEventListener("click", function () {
        window.location.href = "../AddArtwork/AddArtwork.html";
    });

    // Function to load artworks from localStorage (NO DUPLICATION)
    function loadArtworks() {
        const storedArtworks = JSON.parse(localStorage.getItem("artworks")) || [];
        artGrid.innerHTML = ""; // Clear to avoid duplication

        storedArtworks.forEach((art) => {
            addArtworkToPage(art);
        });
    }

    // Function to add an artwork to the page
    function addArtworkToPage(art) {
        const artItem = document.createElement("div");
        artItem.classList.add("art-item");
        artItem.innerHTML = `
            <input type="checkbox" class="delete-checkbox">
            <img src="${art.image}" alt="${art.title}">
            <h3>${art.title}</h3>
            <p>${art.description}</p>
            <p>${art.category}</p>
            <p>${art.size}</p>
            <p>${art.price}</p>
            <p>${art.availability}</p>
            <p><a href="../Edit/Edit.html" class="edit-icon">✎</a></p>
        `;

        artGrid.appendChild(artItem);
    }
});
