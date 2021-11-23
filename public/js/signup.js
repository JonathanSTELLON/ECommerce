const avatarForm = document.querySelector('.avatarForm');
avatarForm.addEventListener('submit', onSubmitAvatar);

async function onSubmitAvatar(event){

    event.preventDefault();

    const formData = new FormData(avatarForm);

    const options = {
        method: 'POST',
        body: formData
    };

    const url = document.querySelector('.avatarForm').action;
    const response = await fetch(url, options);
    const newAvatar = await response.text();

    let avatar = document.getElementById('svgAvatar');
    avatar.innerHTML = newAvatar;

    document.querySelectorAll('[name="svg"]').forEach(field => {
        field.value = newAvatar;
    });

}

