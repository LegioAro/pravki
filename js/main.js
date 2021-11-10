const BTN = document.querySelectorAll('.one-btn')
const POP = document.querySelector('.pop_up')
const POP_CLOSE = document.querySelector('.pop_close')


for(b of BTN){
    b.addEventListener('click',()=>{ POP.classList.toggle('active')})
}
POP_CLOSE.onclick = function (){
    POP.classList.toggle('active')
}

