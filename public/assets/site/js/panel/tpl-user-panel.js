// edit informatio
let EditBtn = document.querySelectorAll("#editButton");
let nameEditInput = document.getElementById("name");

EditBtn.forEach((item) =>{
    item.addEventListener("click", function(event){
        const input = item.parentElement.children[0];
        input.removeAttribute('readonly');
        input.removeAttribute('disabled');
        input.focus();
        if(!input.hasAttribute('readonly') || input.hasAttribute('disabled')){
          item.innerHTML = "";
        }
    })
})
