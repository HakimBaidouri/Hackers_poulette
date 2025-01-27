if (typeof validator !== 'undefined') {
    function validateForm() {
        let isValid = true;
    
        const name = document.getElementById('name').value.trim();
        const firstName = document.getElementById('firstName').value.trim();
        const mail = document.getElementById('mail').value.trim();
        const url = document.getElementById('url').value.trim();
        const description = document.getElementById('description').value.trim();
    
        if (name.length < 2 || name.length > 255) {
            isValid = false;
        }
        console.log(isValid);
    
        if (firstName.length < 2 || firstName.length > 255) {
            isValid = false;
        }
        console.log(isValid);
    
        if (!validator.isEmail(mail)) {
            isValid = false;
        }
        console.log(isValid);
    
        if (!validator.isURL(url)) {
            isValid = false;
        }
        console.log(isValid);
    
        if (description.length < 2 || description.length > 1000) {
            isValid = false;
        }
        if(isValid === false){
            alert('Invalid form');
        }
    
        return isValid;
    }
} else {
    console.error('Validator.js is not loaded');
}

const inputs = document.querySelectorAll("input");

// Parcours chaque élément <input>
inputs.forEach(input => {
    input.addEventListener("input", () => {
        const span = input.nextElementSibling;
        const message = span.nextElementSibling;
        console.log(span);

        if (input.id === "name" && (input.value.length < 2 || input.value.length > 255)) {
            console.log("condition remplies");
            span.style.color = "red";
            message.textContent = "The length must be between 2 and 255 characters";
        } else if (input.id === "name"){
            span.style.color = "green";
            message.textContent = "";
        }

        if (input.id === "firstName" && (input.value.length < 2 || input.value.length > 255)) {
            console.log("condition remplies");
            span.style.color = "red";
            message.textContent = "The length must be between 2 and 255 characters";
        } else if (input.id === "firstName"){
            span.style.color = "green";
            message.textContent = "";
        }
    
        if (input.id === "mail" && !validator.isEmail(input.value.trim())) {
            console.log("condition remplies");
            span.style.color = "red";
            message.textContent = "Invalid. For example : user@gmail.com"
        } else if (input.id === "mail"){
            span.style.color = "green";
            message.textContent = "";
        }

        if (input.id === "url" && !validator.isURL(input.value.trim())) {
            console.log("condition remplies");
            span.style.color = "red";
            message.textContent = "Invalid. For example : https://..."
        } else if (input.id === "url"){
            span.style.color = "green";
            message.textContent = "";
        }
    
        if (input.id === "description" && (input.value.length < 2 || input.value.length > 1000)) {
            console.log("condition remplies");
            span.style.color = "red";
            message.textContent = "The length must be between 2 and 1000 characters";
        } else if (input.id === "description"){
            span.style.color = "green";
            message.textContent = "";
        }
    });
});