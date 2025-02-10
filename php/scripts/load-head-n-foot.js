"use strict";

async function loadHTML(elementName, filePath) {
  const element = document.querySelector(elementName);

  try {
    const response = await fetch(filePath);

    // Check if the response is successful
    if (!response.ok) {
      console.error(
        "Failed to fetch " + filePath + ": Status " + response.status
      );
      return;
    }

    // Grab html from the response
    const content = await response.text();

    // Add html to element
    if (element) {
      element.innerHTML = content;
    } else {
      console.error("Element not found: " + elementName);
    }
  } catch (error) {
    // Handle any fetch-related errors
    console.error("Error loading " + filePath + ":", error);
  }
}

document.addEventListener("DOMContentLoaded", function () {
  //Don't display anything until header and footer have been loaded
  document.querySelector("body").style.display = "none";

  //Add header and footer content for the page
  Promise.all([
    loadHTML("header", "components/header.html"),
    loadHTML("footer", "components/footer.php"),
  ]).then(() => {
    //Set display back to block after header and footer are loaded
    document.querySelector("body").style.display = "block";
  });
});
