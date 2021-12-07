export class Deletecat {

    constructor(){

        this.confirmDeleteCat = document.querySelectorAll('.deleteCat');

        for (let i=0; i<this.confirmDeleteCat.length; i++){
            this.confirmDeleteCat[i].addEventListener('click', this.onClickDeleteCat);
            
        }
    }
    async onClickDeleteCat(event){

        event.preventDefault();

        if (confirm('Confirmez vous la suppression de cette catégorie ?')){

            var myHeaders = new Headers();
            myHeaders.append('X-Requested-With', 'XMLHttpRequest');

            const options = {
                'headers': myHeaders
            }
            const response = await fetch(event.currentTarget.href, options);

            const deleteCat = await response.json();

            const trDeleteCat = document.getElementById('category-'+deleteCat);
            
            trDeleteCat.remove();

            alert('Catégorie correctement supprimée !');
        } 
    }
}





