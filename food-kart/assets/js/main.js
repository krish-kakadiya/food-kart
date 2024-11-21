document.addEventListener("DOMContentLoaded", () => {
    console.log("JavaScript loaded");

    // Get the profile icon and user profile section
    const profileIcon = document.querySelector('.profile-icon');
    const userProfile = document.querySelector('.user-profile');

    // Toggle the profile section visibility on profile icon click
    profileIcon.addEventListener('click', function() {
        userProfile.classList.toggle('open');
    });
});



