//Code to register user into local storage
var user;

const registerUser = (ev) => {
    const firstName = document.getElementById("firstName").value;
    const lastName = document.getElementById("lastName").value;
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    // Check if any field is empty
    if (!firstName || !lastName || !email || !password) {
        alert('Please fill in all fields.');
        return; // Prevent form submission
    }

    ev.preventDefault();
    user = {
        firstName: document.getElementById("firstName").value,
        lastName: document.getElementById("lastName").value,
        email: document.getElementById("email").value,
        password: document.getElementById("password").value 
    };

    // Store user data in local storage
    localStorage.setItem('registeredUser', JSON.stringify(user));

    // Log the JSON data to be submitted
    console.log('JSON data to be submitted:', JSON.stringify(user));
    window.location.href = "login.html";
};

document.addEventListener("DOMContentLoaded", () => {
    document.getElementById('register-button').addEventListener('click', registerUser);
});


//Code to login user
const loginUser = () => {
    const enteredEmail = document.getElementById("loginEmail").value;
    const enteredPassword = document.getElementById("loginPassword").value;

    // Retrieve the registered user data from local storage
    const registeredUserData = JSON.parse(localStorage.getItem('registeredUser'));

    if (registeredUserData) {
        // Check if the entered email and password match the stored registration data
        if (
            enteredEmail === registeredUserData.email &&
            enteredPassword === registeredUserData.password
        ) {
            // Redirect to the home page on successful login
            window.location.href = "Home.html";
        } else {
            // Display an error message for unsuccessful login
            alert("Login failed. Please check your credentials.");
        }
    } else {
        // If there is no registered user data, display an error message
        alert("No registered user found. Please register first.");
    }
}
document.addEventListener("DOMContentLoaded", () => {
    document.getElementById('login-button').addEventListener('click',loginUser)
})


//Add recipe page
document.addEventListener("DOMContentLoaded", () => {
    const recipeForm = document.getElementById("recipe-form");
    const saveRecipeButton = document.getElementById("save-recipe");

    recipeForm.addEventListener("submit", function (event) {
        event.preventDefault();

        const recipeName = document.getElementById("recipe-name").value;
        const ingredients = document.getElementById("ingredients").value;
        const instructions = document.getElementById("instructions").value;

        // Create a recipe object
        const recipe = {
            Recipe_name: recipeName,
            ingredients: ingredients,
            instructions: instructions,
        };

        // Check if recipes already exist in local storage
        let recipes = JSON.parse(localStorage.getItem("recipes")) || [];

        // Add the new recipe to the recipes array
        recipes.push(recipe);

        // Save the updated recipes array to local storage
        localStorage.setItem("recipes", JSON.stringify(recipes));

        // Reset the form
        recipeForm.reset();
        window.location.href = "confirmation.html";
    });
});


// Profile page
document.addEventListener("DOMContentLoaded", () => {
    const userNameElement = document.getElementById("userName");
    const recipeTable = document.querySelector('.recipe-table');
    const logoutButton = document.getElementById("logout-button");

    // Check if the user is logged in by retrieving user data from local storage
    const userData = JSON.parse(localStorage.getItem('registeredUser'));

    if (userData) {
        // If user data is found, display the user's name
        userNameElement.textContent = userData.firstName + " " + userData.lastName;
    } else {
        // If no user data is found, the user may not be logged in
        userNameElement.textContent = "Guest";
    }

    // Display the recipes
    const recipeData = JSON.parse(localStorage.getItem('recipes'));

    if (recipeData && recipeData.length > 0) {
        // Create table rows for each recipe
        for (const recipe of recipeData) {
            const row = recipeTable.insertRow();
            const nameCell = row.insertCell(0);
            const ingredientsCell = row.insertCell(1);
            const instructionsCell = row.insertCell(2);

            nameCell.textContent = recipe.Recipe_name;
            ingredientsCell.textContent = recipe.ingredients;
            instructionsCell.textContent = recipe.instructions;
        }
    }

    // Add a click event listener to the "Log Out" button
    logoutButton.addEventListener('click', () => {
        // Remove user data from local storage (simulate logging out)
        localStorage.removeItem('registeredUser');
        // Redirect the user to the index page
        window.location.href = "index.html";
    });
});



//Populate the food detail page
document.addEventListener("DOMContentLoaded", () => {
    const foodDetailsContainer = document.getElementById("food-details");

    // Get the query parameter (food number) from the URL
    const urlParams = new URLSearchParams(window.location.search);
    const foodNumber = urlParams.get("food");

    // Define an object with food details (you can retrieve this data from a database or API)
    const foodDetails = {
        1: {
            name: "Spaghetti Carbonara",
            ingredients: "Spaghetti, eggs, Pecorino Romano cheese, guanciale, black pepper",
            instructions: "1. Cook spaghetti. 2. Sauté guanciale. 3. Whisk eggs and cheese. 4. Combine all ingredients.",
        },
        2: {
            name: "Chicken Tikka Masala",
            ingredients: "Chicken, yogurt, tomato sauce, garam masala, cumin, coriander, cream",
            instructions: "1. Marinate chicken. 2. Grill chicken. 3. Simmer in tomato sauce. 4. Add cream and spices.",
        },
        3: {
            name: "Caesar Salad",
            ingredients: "Romaine lettuce, croutons, Parmesan cheese, Caesar dressing",
            instructions: "1. Toss lettuce, croutons, and cheese. 2. Drizzle with dressing. 3. Serve.",
        },
        4: {
            name: "Sushi",
            ingredients: "Rice, fish (e.g., salmon, tuna), seaweed, vegetables",
            instructions: "1. Prepare sushi rice. 2. Assemble with fish, veggies, and seaweed. 3. Roll and slice.",
        },
        5: {
            name: "Margarita Pizza",
            ingredients: "Pizza dough, tomatoes, mozzarella cheese, basil, olive oil",
            instructions: "1. Roll out dough. 2. Add tomatoes, cheese, and basil. 3. Bake. 4. Drizzle with olive oil.",
        },
    };

    // Display the food details for the selected food
    if (foodDetails[foodNumber]) {
        const food = foodDetails[foodNumber];
        foodDetailsContainer.innerHTML = `
            <h2>${food.name}</h2>
            <p><strong>Ingredients:</strong> ${food.ingredients}</p>
            <p><strong>Instructions:</strong> ${food.instructions}</p>
        `;
    } else {
        foodDetailsContainer.innerHTML = "Food details not found.";
    }
});
