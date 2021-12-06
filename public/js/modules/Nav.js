export class Nav {

    constructor(){
        this.SelectionClick = document.querySelector('.SelectionClick');
        this.SelectionClick.addEventListener('click', this.onClickSelection.bind(this));
        this.menuSelection = document.querySelector('.Selection');
    }
    
    onClickSelection(){
        this.menuSelection.classList.toggle("appears");
    }   
}
 

