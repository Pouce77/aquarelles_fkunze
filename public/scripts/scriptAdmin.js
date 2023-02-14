let idUser="";
const buttonsUser=document.querySelectorAll('.btn-danger');
for(let i = 0; i < buttons.length;i++) {
  buttonsUser[i].addEventListener('click', function(){
    idUser=(buttonsUser[i].getAttribute('id'));
  });
}

// Modifier role
let idRole="";
const buttonsWarning=document.querySelectorAll('.btn-warning');
for(let i = 0; i < buttonsWarning.length;i++) {
  buttonsWarning[i].addEventListener('click', function(){
    idRole=(buttonsWarning[i].getAttribute('id'));
    console.log(idRole);
  });
}

document.addEventListener("shown.bs.modal",function(e) {
  
  const modalButtonUser = document.querySelector('#deleteUser');
  if (modalButtonUser!==null){
    modalButtonUser.setAttribute('href','/user/delete/'+idUser);
  }
  
  const form=document.querySelector('#formModal');
  if (form!==null){
    form.setAttribute('action', '/admin/user/update/'+idRole); 
  }

  let p=document.querySelector('#par'+idRole);
  let inputUser=document.querySelector('#user');
  let inputAdmin=document.querySelector('#admin');

  let text=p.innerText;
  
  if (text.match('Administrateur, Utilisateur') ){
    inputAdmin.checked=true;
    inputUser.checked=true;
    console.log('both');
  }else if (text.match('Utilisateur')){
    inputUser.checked=true;
    inputAdmin.checked=false;
    console.log('Util');
  }
  else if (text.match('Administrateur')){
    inputUser.checked=false;
    inputAdmin.checked=true;
    console.log('Admin');
  }
})

