function validateForm() {
    var firstName = document.getElementById("First_name").value;
    var lastName = document.getElementById("Last_name").value;
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirm-password").value;
    var ageGroup = document.getElementById("agegroup").value;

    // Validate first name
    if (firstName.trim() === "") {
      alert("Please enter your first name");
      return false;
    }

    // Validate last name
    if (lastName.trim() === "") {
      alert("Please enter your last name");
      return false;
    }

    // Validate email
    if (email.trim() === "") {
      alert("Please enter your email address");
      return false;
    } else if (!validateEmail(email)) {
      alert("Please enter a valid email address");
      return false;
    }

    // Validate password
    if (password === "") {
      alert("Please enter a password");
      return false;
    } else if (password.length < 8 || password.length > 20) {
      alert("Password must be 8-20 characters long");
      return false;
    }

    // Validate confirm password
    if (confirmPassword === "") {
      alert("Please confirm your password");
      return false;
    } else if (password !== confirmPassword) {
      alert("Passwords do not match");
      return false;
    }

    // Validate age group
    if (ageGroup === "Select your age group") {
      alert("Please select your age group");
      return false;
    }
    return true;
  }

  function validateEmail(email) {
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }
