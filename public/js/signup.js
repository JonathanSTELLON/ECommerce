const avatarForm = document.querySelector('.avatarForm');
avatarForm.addEventListener('submit', onSubmitAvatar);

async function onSubmitAvatar(event){

    //On stope le comportement par défaut du navigateur
    event.preventDefault();

    //On récupère les doinnées du form
    const formData = new FormData(avatarForm);

    const options = {
        method: 'POST',
        body: formData
    };

    //On récupère l'action du form et c'est vers cette url qu'on envoie la requete AJAX
    const url = document.querySelector('.avatarForm').action;
    const response = await fetch(url, options);
    const newAvatar = await response.text();

    //On remplace l'avatar qui s'affiche dans le DOM
    let avatar = document.getElementById('svgAvatar');
    avatar.innerHTML = newAvatar;

    //On remplace la valeur du champ caché svg
    document.querySelectorAll('[name="svg"]').forEach(field => {
        field.value = newAvatar;
    });

}

