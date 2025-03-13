// JavaScript to handle the clicks for the elements
document.addEventListener("DOMContentLoaded", function () {


    setupTabs();
    // Redirect to index.html when clicking on the logo
    document.querySelector(".logo").addEventListener("click", function () {
        window.location.href = "../Home/index.html";
    });

    // Redirect to Wishlist.html when clicking on the wishlist icon
    document.querySelector("#wishlist-header").addEventListener("click", function () {
        window.location.href = "../Wishlist/Wishlist.html";
    });

    // Redirect to Profile.html when clicking on the profile icon
    document.querySelector("#profile-header").addEventListener("click", function () {
        window.location.href = "../Profile/Profile.html";
    });

    // Call the function to load auction items
    loadAuctionItems("");
    loadAuctionItems("currentUser"); // TODO: fix this to get actual current user id

    document.querySelectorAll(".auction-item").forEach(item => {
    item.addEventListener("click", function (event) {
        // Get the artwork name
        const artName = item.querySelector(".art-name").textContent;

        // Get the artworks from localStorage
        let artworks = JSON.parse(localStorage.getItem("artworks")) || [];

        // Find the matching artwork
        let artwork = artworks.find(artwork => artwork.title === artName);

        // If artwork is found, redirect to the details page
        if (artwork) {
            window.location.href = `../AuctionDetails/AuctionDetails.html?id=${artwork.id}`;
        } else {
            console.error("Artwork not found.");
            // Optionally, show a message to the user
        }
    });
});

});


// Function to load auction items from localStorage and display them
function loadAuctionItems(username) {
    // Fetch the "artworks" from localStorage or initialize as an empty array
    let artworks = JSON.parse(localStorage.getItem("artworks")) || [];

    let auctionItems, auctionContainer;
    if (username === "") { // if no username then get all artworks of type "auction"
        auctionItems = artworks.filter(artwork => artwork.listingType === "auction");
        auctionContainer = document.getElementById("auction-items-container");

    } else {
        auctionItems = artworks.filter(artwork => (artwork.createdBy === username && artwork.listingType === "auction"));
        auctionContainer = document.getElementById("my-auction-items-container");
    }

    // Clear any existing content in the container before adding new items
    auctionContainer.innerHTML = '';

    // Loop through each auction item and dynamically create HTML elements
    auctionItems.forEach(item => {
        // Create a div for each auction item
        const auctionItemDiv = document.createElement("div");
        auctionItemDiv.classList.add("auction-item");

        // Create the image element
        const auctionImage = document.createElement("img");
        auctionImage.src = item.image;
        auctionImage.alt = item.title;

        // Create the name element
        const artName = document.createElement("p");
        artName.classList.add("art-name");
        artName.textContent = item.title;

        // Create the price element
        const artPrice = document.createElement("p");
        artPrice.classList.add("art-price");
        artPrice.textContent = item.price;

        // Create the time left element
        const timeLeft = document.createElement("p");
        timeLeft.classList.add("time-left");

        // Initially set the time left
        timeLeft.textContent = "Time left: " + calculateTimeLeft(item.endTime);

        // Append the elements to the auction item div
        auctionItemDiv.appendChild(auctionImage);
        auctionItemDiv.appendChild(artName);
        auctionItemDiv.appendChild(artPrice);
        auctionItemDiv.appendChild(timeLeft);

        // Append the auction item div to the container
        auctionContainer.appendChild(auctionItemDiv);

        // Update the countdown every second for this item
        const interval = setInterval(() => {
            const timeLeftText = calculateTimeLeft(item.endTime);
            timeLeft.textContent = "Time left: " + timeLeftText;

            // If auction has ended, stop the interval
            if (timeLeftText === "Auction has ended") {
                clearInterval(interval);
            }
        }, 1000); // Update every second (1000ms)
    });

}

// Function to calculate the time left based on endTime
function calculateTimeLeft(endTime) {
    const now = new Date();
    const endDate = new Date(endTime);
    const timeDiff = endDate - now;

    if (timeDiff <= 0) {
        return "Auction has ended";
    }

    const days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
    const hours = Math.floor((timeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((timeDiff % (1000 * 60)) / 1000);

    return `${days}d ${hours}h ${minutes}m ${seconds}s`;
}

function setupTabs() {
    document.querySelectorAll('.tab-btn').forEach(button => {
        button.addEventListener('click', () => {
            const sidebar = button.parentElement;
            const tabs = sidebar.parentElement;
            const tabNumber = button.dataset.forTab;
            const tabActivate = tabs.querySelector(`.tab-content[data-tab="${tabNumber}"]`);

            // Remove active classes
            sidebar.querySelectorAll('.tab-btn').forEach(button => {
                button.classList.remove('tab-btn-active');
            });
            tabs.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('tab-content-active');
            });

            // Add active class to the clicked button and content
            button.classList.add('tab-btn-active');
            tabActivate.classList.add('tab-content-active');
        });
    });
}


