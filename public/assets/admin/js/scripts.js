//////////////////////////////////// FUNCTION UTILS
function datetimeLocalToDateString(datetimeLocal) {
    let date = new Date(datetimeLocal);
    let year = date.getFullYear().toString();
    let month = (date.getMonth() + 1).toString().padStart(2, '0'); // Month is zero-based
    let day = date.getDate().toString().padStart(2, '0');
    let hours = date.getHours().toString().padStart(2, '0');
    let minutes = date.getMinutes().toString().padStart(2, '0');
    let seconds = date.getSeconds().toString().padStart(2, '0');

    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
}


////////////////////////////////// DISPLAY IMAGE
const fileInput = document.getElementById('image');
if(fileInput) {
    fileInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const imageURL = URL.createObjectURL(file);
            const imageDisplay = document.getElementById('imageDisplay');
            imageDisplay.src = imageURL;
        }
    });
}

