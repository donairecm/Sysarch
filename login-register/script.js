const container = document.getElementById('container');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');

registerBtn.addEventListener('click', (event) => {
    container.classList.add("active");
    event.preventDefault(); 
});

loginBtn.addEventListener('click', (event) => {
    container.classList.remove("active");
    event.preventDefault(); 
});
