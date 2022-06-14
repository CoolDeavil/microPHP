
export function main(){
 console.log('Assets Bundled by Webpack!...');
}

if (document.readyState === 'complete') {
    main()
} else {
    document.addEventListener('DOMContentLoaded', main);
}