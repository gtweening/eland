function formhash(form, password) {
    // Create a new element input, this will be our hashed password field. 
    var p = document.createElement("input");
 
    // Add the new element to our form. 
    form.appendChild(p);
    p.name = "p";
    p.type = "hidden";
    p.value = hex_sha512(password.value);
 
    // Make sure the plaintext password doesn't get sent. 
    password.value = "";
 
    // Finally submit the form. 
    form.submit();
}

function formhash2(form, passwordn, passwordh) {
    // Create a new element input, this will be our hashed password field. 
    var pn = document.createElement("input");
    var ph = document.createElement("input");

    // Add the new element to our form. 
    form.appendChild(pn);
    pn.name = "pn";
    pn.type = "hidden";
    pn.value = hex_sha512(passwordn.value);
    form.appendChild(ph);
    ph.name = "ph";
    ph.type = "hidden";
    ph.value = hex_sha512(passwordh.value);

    // Make sure the plaintext password doesn't get sent. 
    passwordv.value = "";
    passwordh.value = "";
    // Finally submit the form. 
    form.submit();
}

function regformhash(form, uid, email, password, conf) {
     // Check each field has a value
    if (uid.value == '' || 
        email.value == '' || 
        password.value == '' || 
        conf.value == '') {
 
        alert('U moet alle gevraagde details opgeven. Probeer het nog een keer');
        return false;
    }
 
    // Check the username
 
    re = /^\w+$/; 
    if(!re.test(form.username.value)) { 
        alert("Username must contain only letters, numbers and underscores. Please try again"); 
        form.username.focus();
        return false; 
    }
 
    // Check that the password is sufficiently long (min 6 chars)
    // The check is duplicated below, but this is included to give more
    // specific guidance to the user
    if (password.value.length < 6) {
        alert('Password moet langer zijn dan 6 tekens.  Probeer het nog een keer');
        form.password.focus();
        return false;
    }
 
    // At least one number, one lowercase and one uppercase letter 
    // At least six characters 
 
    var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/;
    if (!re.test(password.value)) {
        alert('Passwords must contain at least one number, one lowercase and one uppercase letter.  Please try again');
        return false;
    }
 
    // Check password and confirmation are the same
    if (password.value != conf.value) {
        alert('Your password and confirmation do not match. Please try again');
        form.password.focus();
        return false;
    }
 
    // Create a new element input, this will be our hashed password field. 
    var p = document.createElement("input");
 
    // Add the new element to our form. 
    form.appendChild(p);
    p.name = "p";
    p.type = "hidden";
    p.value = hex_sha512(password.value);
 
    // Make sure the plaintext password doesn't get sent. 
    password.value = "";
    conf.value = "";
 
    // Finally submit the form. 
    form.submit();
    return true;
}
