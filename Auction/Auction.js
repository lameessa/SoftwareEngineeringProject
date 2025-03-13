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
    initializeDefaultArtworks();
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
            },
            {
                id: "4",
                image: "../images/Cafeterrasse-bei-Nacht.jpg",
                title: "Cafe Terrace at Night (1890)",
                description: "This painting of a colorful outdoor view is a picturesque work, the vision of a relaxed spectator who enjoys the charm of his surrounding without any moral concern.",
                category: "Post-Impressionism",
                size: "10x15 cm",
                price: "$8800",
                listingType: "auction",
                endTime: "2025-05-12T11:11"
            },
            {
                id: "5",
                image: "../images/StarryNight.jpg",
                title: "The Starry Night (1889)",
                description: "A swirling night sky over a small town, a powerful and emotional depiction of Van Gogh’s inner turmoil.",
                category: "Post-Impressionism",
                size: "24x36 cm",
                price: "$1000",
                listingType: "auction",
                endTime: "2025-06-12T11:11"
            },
            {
                id: "6",
                image: "../images/MonaLisa.jpg",
                title: "Mona Lisa (1503–1506)",
                description: "The enigmatic portrait of a woman that has captivated viewers for centuries, created by Leonardo da Vinci.",
                category: "Renaissance",
                size: "50x70 cm",
                price: "$1020",
                listingType: "auction",
                endTime: "2025-07-12T11:11"
            },
            {
                id: "7",
                image: "../images/TheScream.jpg",
                title: "The Scream (1893)",
                description: "An iconic expressionist painting of a figure on a bridge, with a distorted, screaming face.",
                category: "Expressionism",
                size: "20x30 cm",
                price: "$940",
                listingType: "auction",
                endTime: "2025-08-12T11:11"
            },];
        localStorage.setItem("artworks", JSON.stringify(defaultArtworks));
        localStorage.setItem("initialized", "true");
    }

}
