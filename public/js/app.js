let menuIcon = document.getElementById('menuIcon')
let closeMenuIcon = document.getElementById('closeMenuIcon')
let navItems = document.getElementById('navItems')
let toggleDarkIcon = document.getElementById('toggleDarkIcon')
let toggleLightIcon = document.getElementById('toggleLightIcon')

if (menuIcon) {
    menuIcon.addEventListener('click', () => {
        menuIcon.classList.remove('block')
        menuIcon.classList.add('hidden')
        closeMenuIcon.classList.remove('hidden')
        closeMenuIcon.classList.add('block')
        navItems.classList.remove('hidden')
        navItems.classList.add('flex', 'flex-col', 'w-full', 'my-2')
    })
}

if (closeMenuIcon) {
    closeMenuIcon.addEventListener('click', () => {
        closeMenuIcon.classList.remove('block')
        closeMenuIcon.classList.add('hidden')
        menuIcon.classList.remove('hidden')
        menuIcon.classList.add('block')
        navItems.classList.remove('flex', 'flex-col', 'w-full', 'my-2')
        navItems.classList.add('hidden')
    })
}

if (toggleDarkIcon) {
    toggleDarkIcon.addEventListener('click', () => {
        let htmlClasses = document.querySelector('html').classList;
        if(localStorage.theme === 'dark') {
            htmlClasses.remove('dark');
            localStorage.removeItem('theme')
        } else {
            htmlClasses.add('dark');
            localStorage.theme = 'dark';
        }
    })
}
