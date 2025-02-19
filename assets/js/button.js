document.addEventListener('DOMContentLoaded', function () {
    const heartIcon = document.querySelector('.heart-icon');

    heartIcon.addEventListener('click', function () {
        heartIcon.classList.toggle('far');
        heartIcon.classList.toggle('fas');
        heartIcon.classList.toggle('liked');
    })
})

