const SelectionClick = document.querySelector('.SelectionClick');
SelectionClick.addEventListener('click', onClickSelection);

const menuSelection = document.querySelector('.Selection');

function onClickSelection(){
    menuSelection.classList.toggle("appears");
}
