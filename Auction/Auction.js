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