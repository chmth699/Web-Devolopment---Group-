//Login popup
const openbtn = document.getElementById('openmodalbtn');
const closebtn = document.getElementById('closemodalbtn');
const modaloverlay = document.getElementById('modaoverlay');
const switchtosignup = document.getElementById('switchtosignup');
const switchtologin = document.getElementById('switchtologin');
const loginformcontainer = document.getElementById('loginformcontainer');
const signupformcontainer = document.getElementById('signupformcontainer');

if (openbtn) {
    openbtn.addEventListener('click',(event)=>{
        event.preventDefault();
        modaloverlay.classList.add('active');
        loginformcontainer.classList.add('active');
        signupformcontainer.classList.remove('active');
    });
}
if (closebtnbtn) {
    closebtn.addEventListener('click',()=>{
        modaloverlay.classList.remove('active');
    });
}
if (modaloverlay) {
    modaloverlay.addEventListener('click',(e)=>{
        if (e.target==modaloverlay) {
            modaloverlay.classList.remove('active');    
        }
    });
}
if (switchtosignup) {
    switchtosignup.addEventListener('click',(e)=>{
        e.preventDefault();
        modaloverlay.classList.add('active');
        loginformcontainer.classList.remove('active');
        signupformcontainer.classList.add('active');
    });
}
if (switchtologin) {
    switchtologin.addEventListener('click',(e)=>{
        e.preventDefault();
        modaloverlay.classList.add('active');
        signupformcontainer.classList.remove('active');
        loginformcontainer.classList.add('active');
    });
}