document.body.style.backgroundColor = sessionStorage.getItem('bg');
document.body.style.color = sessionStorage.getItem('cc');
setInterval(function(){
    setImageVisible('darkside', false);
},5000);
setInterval(function(){
    setImageVisible('whiteside', false);
},5000);

//DV
function dark() {

     if (sessionStorage.getItem('bg') === '#e6e6e6') {

            sessionStorage.setItem('bg', 'rgb(6, 23, 37)');
            sessionStorage.setItem('cc', '#777');
                setImageVisible('darkside', true);
           
     }
    else if (sessionStorage.getItem('bg') == null || undefined) {
        sessionStorage.setItem('bg', 'rgb(6, 23, 37)');
        sessionStorage.setItem('cc', '#777');
        setImageVisible('darkside', true);
    }
    else if(sessionStorage.getItem('bg') === 'rgb(6, 23, 37)') {

        sessionStorage.setItem('bg', '#e6e6e6');
        sessionStorage.setItem('cc', '#333');
        setInterval(function(){
            setImageVisible('darkside', false);
        },5000);
        
    }



document.body.style.backgroundColor = sessionStorage.getItem('bg');
document.body.style.color = sessionStorage.getItem('cc');

}

//GANDALF
function white() {

    if (sessionStorage.getItem('bg') === 'rgb(6, 23, 37)') {

           sessionStorage.setItem('bg', '#e6e6e6');
           sessionStorage.setItem('cc', '#777');
               setImageVisible('whiteside', true);
          
    }
   else if (sessionStorage.getItem('bg') == null || undefined) {
       sessionStorage.setItem('bg', 'rgb(6, 23, 37)');
       sessionStorage.setItem('cc', '#777');
       setImageVisible('whiteside', false);
   }
   else if(sessionStorage.getItem('bg') === '#e6e6e6') {

       sessionStorage.setItem('bg', 'rgb(6, 23, 37)');
       sessionStorage.setItem('cc', '#333');
       setInterval(function(){
           setImageVisible('whiteside', false);
       },5000);
       
   }
   
document.body.style.backgroundColor = sessionStorage.getItem('bg');
document.body.style.color = sessionStorage.getItem('cc');
}
function setImageVisible(id, display) {
    
    var img = document.getElementById(id);
    img.style.display = (display ? 'block' : 'none');
    
    img.style.opacity = "0.2";
    img.style.position = "absolute";
    img.style.top = "20%";
    img.style.left = "41%";
    img.style.zIndex = "99";
    img.style.animationName = "dark";
    img.style.animationDuration = "3s";
    img.style.animationFillMode = "forwards";
}


function mod(){
    if(sessionStorage.getItem('bg') == "rgb(6, 23, 37)"){
    white();
  }
  else if(sessionStorage.getItem('bg') == "#e6e6e6"){
    dark();
  }
  else if(sessionStorage.getItem('bg') == null || undefined){
   dark();
  }
}
    
