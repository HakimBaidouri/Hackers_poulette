if (typeof validator !== 'undefined') {
    function validateForm() {
        let isValid = true;
    
        const name = document.getElementById('name').value.trim();
        const firstName = document.getElementById('firstName').value.trim();
        const mail = document.getElementById('mail').value.trim();
        const url = document.getElementById('url').value.trim();
        const description = document.getElementById('description').value.trim();
    
        if (name.length < 2 || name.length > 255) {
            alert('Name must be between 2 and 255 characters.');
            isValid = false;
        }
        console.log(isValid);
    
        if (firstName.length < 2 || firstName.length > 255) {
            alert('Firstname must be between 2 and 255 characters.');
            isValid = false;
        }
        console.log(isValid);
    
        if (!validator.isEmail(mail)) {
            alert('Email invalid');
            isValid = false;
        }
        console.log(isValid);
    
        if (!validator.isURL(url)) {
            alert('Url invalid');
            isValid = false;
        }
        console.log(isValid);
    
        if (description.length < 2 || description.length > 1000) {
            alert('Description must be between 2 and 1000 characters.');
            isValid = false;
        }
        console.log(isValid);
    
        return isValid;
    }
} else {
    console.error('Validator.js is not loaded');
}

