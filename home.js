const text = "Find notes, past papers, lectures and more easily Save time, learn better, grow together.";
const speed = 100; // typing speed in ms

let i = 0;

function typeWriter() {
  if (i < text.length) {
    document.getElementById("typewriter").innerHTML += text.charAt(i);
    i++;
    setTimeout(typeWriter, speed);
  }
}

typeWriter();