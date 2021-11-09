/*MENU BURGER*/

let navElt = document.querySelector('.SiteHeader-nav');
let burgerElt = document.querySelector('.SiteHeader-nav-link.burger');
let noBurgerElt = document.querySelector('.SiteHeader-nav-link.noBurger');

burgerElt.addEventListener("click",function(){
    navElt.classList.add("burgerOn");

});
noBurgerElt.addEventListener("click",function(){
    navElt.classList.remove("burgerOn");
});