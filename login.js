//Login popup
const openbtn = document.getElementById('openbtn');
const closebtn = document.getElementById('closebtn');
const modaloverlay = document.getElementById('modaloverlay');
const switchtosignup = document.getElementById('switchtosignup');
const switchtologin = document.getElementById('switchtologin');
const loginformcontainer = document.getElementById('loginformcontainer');
const signupformcontainer = document.getElementById('formcontainer');

if (openbtn) {
    openbtn.addEventListener('click',(event)=>{
        event.preventDefault();
        modaloverlay.classList.add('active');
        loginformcontainer.classList.add('active');
        signupformcontainer.classList.remove('active');
    });
}
if (closebtn) {
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

//Signup
const openbtnsignup = document.getElementById('openbtnsignup');
const closebtnsignup = document.getElementById('closebtnsignup');
const modaloverlaysignup = document.getElementById('modaloverlaysignup');

if (openbtnsignup) {
    openbtnsignup.addEventListener('click',(event)=>{
        event.preventDefault();
        modaloverlaysignup.classList.add('active');        
        loginformcontainer.classList.remove('active');
        signupformcontainer.classList.add('active');
    });
}
if (closebtnsignup) {
    closebtnsignup.addEventListener('click',()=>{
        modaloverlaysignup.classList.remove('active');
    });
}
if (modaloverlaysignup) {
    modaloverlaysignup.addEventListener('click',(e)=>{
        if (e.target==modaloverlaysignup) {
            modaloverlaysignup.classList.remove('active');    
        }
    });
}
if (switchtosignup) {
    switchtosignup.addEventListener('click',(e)=>{
        e.preventDefault();
        modaloverlaysignup.classList.add('active');
        loginformcontainer.classList.add('active');
        signupformcontainer.classList.remove('active');
    });
}
if (switchtologin) {
    switchtologin.addEventListener('click',(e)=>{
        e.preventDefault();
        modaloverlaysignup.classList.add('active');
        loginformcontainer.classList.add('active');
        signupformcontainer.classList.remove('active');
        
    });
} 