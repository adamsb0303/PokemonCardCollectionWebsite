function pageUp(current){
    var setID = document.getElementById('up').value;
    self.location= window.location.href + (current + 1);
}

function pageDown(current){
    var setID = document.getElementById('down').value;
    self.location = window.location.href + (current - 1);
}