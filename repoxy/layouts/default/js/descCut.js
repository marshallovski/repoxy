document.querySelectorAll('.post_text')
    .forEach(elem => {
        if (elem.innerText.length > 400) {
            elem.innerText = `${elem.innerText.slice(0, 400)}...`;
        }
    });