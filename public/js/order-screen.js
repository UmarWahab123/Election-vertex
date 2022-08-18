document.addEventListener('DOMContentLoaded', () => {

// Get the modal
var modal = document.getElementById("themeModal");

// Get the button that opens the modal
var btn = document.getElementById("modal-button");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// Get the <span> element that closes the modal
var span1 = document.getElementsByClassName("close-button")[0];

// When the user clicks on the button, open the modal
btn.onclick = function() {
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    document.body.classList.add('slide-out-animation');
    modal.style.display = "none";
}

// When the user clicks on <span> (x), close the modal
span1.onclick = function() {
    document.body.classList.add('slide-out-animation');
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        document.body.classList.add('slide-out-animation');
        modal.style.display = "none";
    }
}
});
