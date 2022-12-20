// eyes at the end of password input fields
const togglePassword = () => {
  const passwordInput = document.querySelector("#user_password")
  passwordInput.type = passwordInput.type === "text" ? "password" : "text"
  const eyeIcon = document.querySelector("#eye")
  eyeIcon.classList.contains("d-none") ? eyeIcon.classList.remove("d-none") : eyeIcon.classList.add("d-none")
  const eyeSlashIcon = document.querySelector("#eye-slash")
  eyeSlashIcon.classList.contains("d-none") ? eyeSlashIcon.classList.remove("d-none") : eyeSlashIcon.classList.add("d-none")
}

//Opening modal for deleting image in gallery
let id="";
const buttons=document.querySelectorAll('.btn-danger');
for(let i = 0; i < buttons.length;i++) {
  buttons[i].addEventListener('click', function(){
    id=(buttons[i].getAttribute('id'));
  });
}
document.addEventListener("shown.bs.modal",function(e) {
  const modalButton = document.querySelector("#delete");
  if (modalButton!==null){
  modalButton.setAttribute('href','/painting/delete/'+id);
  }
})
//Opening modal for picture in gallery
let src="";
const link=document.querySelectorAll('.card-img-top');
for(let i = 0; i < link.length;i++) {
  link[i].addEventListener('click', function(){
    src=(link[i].getAttribute('src'));
  });
}
document.addEventListener("shown.bs.modal",function(e) {
  const modalImage = document.querySelector("#imgModal");
  if (modalImage!==null){
  modalImage.setAttribute('src',src);
  }
})

//Opening modal for picture in Actuality
let srcActu="";
const linkActu=document.querySelectorAll('#imgActu');
for(let i = 0; i < linkActu.length;i++) {
  linkActu[i].addEventListener('click', function(){
    srcActu=linkActu[i].getAttribute('src');
    console.log(srcActu);
  });
}

document.addEventListener("shown.bs.modal",function(e) {
  const modalImage = document.querySelector("#imgModal");
  if (modalImage!==null){
  modalImage.setAttribute('src',srcActu);
  }
})