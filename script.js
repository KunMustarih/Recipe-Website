//Client side validation for user registeration form
document.addEventListener("DOMContentLoaded", () => {
    const firstName = document.getElementById("firstName");
    const lastName = document.getElementById("lastName");
    const email = document.getElementById("email");
    const password = document.getElementById("password");
    const password_confirm = document.getElementById('password_confirmation');
    const form = document.getElementById('registration-form');

    form.addEventListener('submit', e => {
        e.preventDefault();

        if (validateInputs()) {
            form.submit();
        };

       
    });

    const setError = (element, message) => {
        const inputControl = element.parentElement;
        const errorDisplay = inputControl.querySelector('.error');

        errorDisplay.innerText = message;
        inputControl.classList.add('error');
        inputControl.classList.remove('success')
    }

    const setSuccess = element => {
        const inputControl = element.parentElement;
        const errorDisplay = inputControl.querySelector('.error');

        errorDisplay.innerText = '';
        inputControl.classList.add('success');
        inputControl.classList.remove('error');
    };

    const isValidEmail = email => {
        const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    const isOnlyLetters = input => /^[A-Za-z]+$/.test(input);

    const validateInputs = () => {
        let isValid = true; // Initialize the flag

        const firstnamevalue = firstName.value.trim();
        const lastnamevalue = lastName.value.trim();
        const emailvalue = email.value.trim();
        const passwordvalue = password.value.trim();
        const password_confirm_value = password_confirm.value.trim();

        if (firstnamevalue === '') {
            setError(firstName, 'First Name is required');
            isValid = false; // Set the flag to false
        } else if (!isOnlyLetters(firstnamevalue)) {
            setError(firstName, 'First Name should contain only letters');
            isValid = false;
        } else {
            setSuccess(firstName);
        }

        if (lastnamevalue === '') {
            setError(lastName, 'Last Name is required');
            isValid = false;
        } else if (!isOnlyLetters(lastnamevalue)) {
            setError(lastName, 'Last Name should contain only letters');
            isValid = false;
        } else {
            setSuccess(lastName);
        }
    
        if (emailvalue === '') {
            setError(email, 'Email is required');
            isValid = false;
        } else if (!isValidEmail(emailvalue)) {
            setError(email, 'Provide a valid email address');
            isValid = false;
        } else {
            setSuccess(email);
        }
    
        if (passwordvalue === '') {
            setError(password, 'Password is required');
            isValid = false;
        } else if (passwordvalue.length < 8) {
            setError(password, 'Password must be at least 8 characters.');
            isValid = false;
        } else {
            setSuccess(password);
        }
    
        if (password_confirm_value === '') {
            setError(password_confirm, 'Please confirm your password');
            isValid = false;
        } else if (password_confirm_value !== passwordvalue) {
            setError(password_confirm, "Passwords don't match");
            isValid = false;
        } else {
            setSuccess(password_confirm);
        }

        console.log(isValid);

        return isValid; // Return the validation status
    };
});

// Code for client side login validation
document.addEventListener('DOMContentLoaded',() => {
    const loginEmail = document.getElementById("loginEmail");
    const loginPassword = document.getElementById("loginPassword");
    const loginForm = document.getElementById('login-form');

    loginForm.addEventListener('submit', e=> {
        e.preventDefault();

        if(validatelogin()) {
            loginForm.submit();
        }

    });

    const setErrorlogin = (element, message) => {
        const inputControllogin = element.parentElement;
        const errorDisplaylogin = inputControllogin.querySelector('.error');
    
        errorDisplaylogin.innerText = message;
        inputControllogin.classList.add('error');
        inputControllogin.classList.remove('success');
    }
    
    const setSuccesslogin = (element) => {
        const inputControllogin = element.parentElement;
        const errorDisplaylogin = inputControllogin.querySelector('.error');
    
        errorDisplaylogin.innerText = '';
        inputControllogin.classList.add('success');
        inputControllogin.classList.remove('error');
    }
    

    const isValidloginEmail = email => {
        const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }
    
    const validatelogin = () => {
        let isValidlogin = true;

        const loginEmailValue = loginEmail.value.trim();
        const loginPasswordValue = loginPassword.value.trim();

        if (loginEmailValue === '') {
            setErrorlogin(loginEmail, 'Email is required');
            isValidlogin = false;
        } else if (!isValidloginEmail(loginEmailValue)) {
            setErrorlogin(loginEmail, 'Provide a valid email address');
            isValidlogin = false;
        } else {
            setSuccesslogin(loginEmail);
        }

        if (loginPasswordValue === '') {
            setErrorlogin(loginPassword, 'Password is required');
            isValidlogin = false;
        } else if (loginPasswordValue.length < 8) {
            setErrorlogin(loginPassword, 'Password must be at least 8 characters.');
            isValidlogin = false;
        } else {
            setSuccesslogin(loginPassword);
        }
        
        return isValidlogin;
    };
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


