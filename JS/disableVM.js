let pozicija = document.getElementById('sel3');
let vmText = document.getElementById('VMtext');
let radio1 = document.getElementById('customRadio10');
let radio2 = document.getElementById('customRadio11');

if(radio1.checked || radio2.checked){
  radio1.disabled = false;
  radio2.disabled = false; 
  vmText.style.color = "white";
}
else{
  radio1.disabled = true;
  radio2.disabled = true; 
  vmText.style.color = "gray";
}

function VMdisable(){
  if(pozicija.value == "14" || pozicija.value == "29"){
    vmText.style.color = "white";
    radio1.disabled = false;
    radio2.disabled = false;

  }
  else{
    radio1.checked = false;
    radio2.checked = false;
    vmText.style.color = "gray";

    radio1.disabled = true;
    radio2.disabled = true;
  }
}
