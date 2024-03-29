export class Delete {

    constructor(){
        this.confirmDelete = document.querySelectorAll('.delete');

        for (let i=0; i<this.confirmDelete.length; i++){
            this.confirmDelete[i].addEventListener('click', this.onClickDelete);
        }
    }

    async onClickDelete(event){

        event.preventDefault();

        if (confirm('Confirmez vous la suppression de ce produit ?')){

            var myHeaders = new Headers();
            myHeaders.append('X-Requested-With', 'XMLHttpRequest');

            const options = {
                'headers': myHeaders
            }
           
            const response = await fetch(event.currentTarget.href, options);
            
            const deleteProduct = await response.json();

            const trDelete = document.getElementById('product-'+deleteProduct);
            
            trDelete.remove();

            alert('Produit correctement supprimé !');
        } 
    }
}



