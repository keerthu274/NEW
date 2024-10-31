function togglePage(page) {
    const signUpPage = document.getElementById('signUpPage');
    const loginPage = document.getElementById('loginPage');
    
    if (page === 'login') {
        signUpPage.style.display = 'none';
        loginPage.style.display = 'block';
    } else {
        signUpPage.style.display = 'block';
        loginPage.style.display = 'none';
    }
}