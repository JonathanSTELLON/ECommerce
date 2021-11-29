async function onClickDelete(event){

    event.preventDefault();

    if (confirm('Confirmez vous la suppression de cette catégorie ?')){

        // console.log('clickCat');

        var myHeaders = new Headers();
        myHeaders.append('X-Requested-With', 'XMLHttpRequest');

        const options = {
            'headers': myHeaders
        }
        const response = await fetch(this.href, options);

        const deleteCat = await response.json();

        const trDeleteCat = document.getElementById('category-'+deleteCat);
        
        trDeleteCat.remove();

        alert('Catégorie correctement supprimée !');
    } 
}

const confirmDeleteCat = document.querySelectorAll('.deleteCat');

for (let i=0; i<confirmDeleteCat.length; i++){
    confirmDeleteCat[i].addEventListener('click', onClickDelete);
}