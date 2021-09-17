// createStar = (onClick, data, defaultValue = null) => {
//     const stars = document.createElement('div');
//     let active = null;
//     stars.classList.add('stars');
//     stars.innerHTML = `
//     <div data-value="5" class="star ${defaultValue && defaultValue == 5 ? 'active' : ''}">★</div>
//     <div data-value="4" class="star ${defaultValue && defaultValue == 4 ? 'active' : ''}">★</div>
//     <div data-value="3" class="star ${defaultValue && defaultValue == 3 ? 'active' : ''}">★</div>
//     <div data-value="2" class="star ${defaultValue && defaultValue == 2 ? 'active' : ''}">★</div>
//     <div data-value="1" class="star ${defaultValue && defaultValue == 1 ? 'active' : ''}">★</div>`;

//     stars.addEventListener('click', (e) => {
//         if (e.target.classList.contains('star')) {
//             if (active) {
//                 active.classList.remove('active');
//             }
//             active = e.target;
//             active.classList.add('active');
//             onClick(data, +active.getAttribute('data-value'));
//         }
//     });

//     return stars;
// }


const timeout = (ms) => {
    return new Promise(resolve => setTimeout(resolve, ms));
}

const findElement = async (query, ms) => {
    await timeout(ms);
    const find = document.querySelector(query);
    if (!find) {
        return findElement(query);
    }
    return find;
};

const setStars = async (imageModal = null) => {
    if (!imageModal) {
        imageModal = await findElement('.pswp img:not(.init)', 100);
    }
    imageModal.classList.add('init');
    const star = createStar(
        clickStar,
        imageModal
    );
    const container = document.createElement('div');
    container.classList.add('container__star');
    container.appendChild(star);
    imageModal.parentElement.parentElement.appendChild(container);
    const newImage = document.querySelector('.pswp img:not(.init)');
    if (newImage) {
        setStars(newImage);
    }
}

const startGaleria = async () => {
    const galeria = await findElement('.mi-clase:not(.init)', 300);
    galeria.classList.add('init');
    galeria.addEventListener('click', (e) => {
        setStars();
    });
}

startGaleria();
