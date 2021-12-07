export class Previewpic {
    
    constructor(){
        this.thumbnailInput = document.getElementById('product_thumbnail');
        this.thumbnailInput.addEventListener('change', this.showPreview.bind(this));
        console.log(this.thumbnailInput);
    }

    showPreview(event){
        const file = event.currentTarget.files[0];
        const src = URL.createObjectURL(file);

        document.getElementById('file-ip-1-preview').src = src;
    }

}