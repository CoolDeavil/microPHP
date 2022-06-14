
export function main(){
 console.log('Assets Bundled by Webpack!... And ready to go!');

    let languageSwitch = document.querySelector('#formSwitch') as HTMLFormElement;
    const  languageSelectors  = Array.prototype.slice.call(document.querySelectorAll('.language'), 0);
    languageSelectors.forEach((link:any) => {
        link.addEventListener('click',(e: Event)=>{
            e.preventDefault();
            (languageSwitch.elements[0] as HTMLInputElement).value = (e.target as HTMLDivElement).classList[1];
            languageSwitch.submit();
        },false)
    })

}

if (document.readyState === 'complete') {
    main()
} else {
    document.addEventListener('DOMContentLoaded', main);
}