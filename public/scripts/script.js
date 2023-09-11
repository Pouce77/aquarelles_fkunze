// eyes at the end of password input fields
const togglePassword = () => {
  const passwordInput = document.querySelector("#user_password")
  passwordInput.type = passwordInput.type === "text" ? "password" : "text"
  const eyeIcon = document.querySelector("#eye")
  eyeIcon.classList.contains("d-none") ? eyeIcon.classList.remove("d-none") : eyeIcon.classList.add("d-none")
  const eyeSlashIcon = document.querySelector("#eye-slash")
  eyeSlashIcon.classList.contains("d-none") ? eyeSlashIcon.classList.remove("d-none") : eyeSlashIcon.classList.add("d-none")
}

//Opening modal for deleting image or actuality

let id="";
const buttons=document.querySelectorAll('.btn-danger');
for(let i = 0; i < buttons.length;i++) {
  buttons[i].addEventListener('click', function(){
    id=(buttons[i].getAttribute('id'));
    console.log(id);
  });
}
document.addEventListener("shown.bs.modal",function(e) {
  const modalButton = document.querySelector("#delete");
  const modalButtonActu =document.querySelector('#deleteActu');

  if (modalButton!==null){
    modalButton.setAttribute('href','/painting/delete/'+id);
  }
  if (modalButtonActu!==null){
    modalButtonActu.setAttribute('href','/actuality/delete/'+id);
  }
})


const img = document.getElementById("imgPaint");
const footer=document.getElementById("footer");
console.log(footer);
if (img!=null) {
let onLoadHeight=img.clientHeight;

// agrandir la hauteur de l'image
const buttonGrow=document.getElementById("buttonGrow");
  buttonGrow.addEventListener("click", function(){
    //si la largeur est inférieur à la div parent, on peut continuer à zoomer
    
    if(img.clientWidth<img.parentElement.clientWidth){
     let heightImg=img.clientHeight;
     //zoom de 10% en plus
     img.style.height=heightImg+((heightImg*10)/100)+"px";  
     console.log(footer);
      footer.style.animation = "footeranim 1s ease-out 1";
      footer.style.opacity=0;
    } 
  })

  // diminuer la hauteur de l'image
  const buttonDimin=document.getElementById("buttonDimin");
  buttonDimin.addEventListener("click", function(){  
    //si la hauteur est plus grande que 60vh, on peut continuer de dézoomer
    if (img.clientHeight>onLoadHeight){
     let heightImg=img.clientHeight;
     //dézoom de 10% en moins
     img.style.height=heightImg-((heightImg*10)/100)+"px";
     footer.style.animation = "footeraniminverse 1s ease-out 1";
      footer.style.opacity=1; 
    }
  })
}