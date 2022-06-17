function $(type, elem) {
    if (type === 'id') {
        return document.getElementById(elem);
    } else if (type === 'cl') {
        return document.querySelector(elem);
    }
}

let strings;

fetch('/repoxy/misc/i18.json')
    .then((res) => res.json())
    .then(
        (json) => {
            strings = json;
        }
    );

ClassicEditor
    .create($('id', 'posteditor'))
    .then(editor => window.editor = editor)
    .catch(error => console.error(error));

function getPostText(id) {
    if (id) {
        return $('id', `editptxt_${id}`).value;
    } else {
        return window.editor.getData();
    }
}

function getPostName(id) {
    if (id) {
        return $('id', `editpname_${id}`).value;
    } else {
        return $('id', 'addpostname').value;
    }
}

$('id', 'addpostBtn').onclick = async function () {
    if (!getPostText() || !getPostName()) {
        return null;
    }

    if (getPostText().length > 4000 && !getPostText().includes('img')) {
        return Swal.fire(
            'Error',
            strings[window.navigator.language.slice(0, 2)].toolong,
            'error'
        );
    }

    const addpostQuery = `/repoxy/modules/addPost.php?postcontent=${encodeURI(getPostText())}&postname=${encodeURI(getPostName())}&postcreation=${new Date().toLocaleDateString()} ${new Date().toLocaleTimeString()}`;

    await fetch(addpostQuery)
        .then(res => res.json())
        .then(res => {
            if (res.msg === 'OK')
                Swal.fire(strings[window.navigator.language.slice(0, 2)].successaddpost, res.msg, 'success') ? Swal.fire(strings[window.navigator.language.slice(0, 2)].successaddpost, res.msg, 'success') : Swal.fire(strings.en.successaddpost, res.msg, 'success');
            else
                Swal.fire(strings[window.navigator.language.slice(0, 2)].err, res.msg, 'error') ? Swal.fire(strings[window.navigator.language.slice(0, 2)].err, res.msg, 'error') : Swal.fire(strings.en.err, res.msg, 'error');
        });
}

async function editPost(id) {
    const editpostQuery = `/repoxy/modules/editPost.php?postname=${encodeURI(getPostText(id))}&postcontent=${encodeURI(getPostName(id))}&postupdated=${new Date().toLocaleDateString()} ${new Date().toLocaleTimeString()}&postid=${id}`;
    await fetch(editpostQuery)
        .then(res => res.json())
        .then(res => {
            if (res.msg === 'OK')
                Swal.fire(strings[window.navigator.language.slice(0, 2)].editpostok, res.msg, 'success') ? Swal.fire(strings[window.navigator.language.slice(0, 2)].editpostok, res.msg, 'success') : Swal.fire(strings.en.editpostok, res.msg, 'success');
            else
                Swal.fire(strings[window.navigator.language.slice(0, 2)].err, res.msg, 'error') ? Swal.fire(strings[window.navigator.language.slice(0, 2)].err, res.msg, 'error') : Swal.fire(strings.en.err, res.msg, 'error');
        });
}

async function deletePost(id) {
    const delpostQuery = `/repoxy/modules/deletePost.php?postid=${id}`;
    await fetch(delpostQuery)
        .then(res => res.json())
        .then(res => {
            if (res.msg === 'OK') {
                $('id', `post_${id}`).remove();
                Swal.fire(strings[window.navigator.language.slice(0, 2)].delpostok, res.msg, 'success') ? Swal.fire(strings[window.navigator.language.slice(0, 2)].delpostok, res.msg, 'success') : Swal.fire(strings.en.delpostok, res.msg, 'success');
            }
            else
                Swal.fire(strings[window.navigator.language.slice(0, 2)].err, res.msg, 'error') ? Swal.fire(strings[window.navigator.language.slice(0, 2)].err, res.msg, 'error') : Swal.fire(strings.en.err, res.msg, 'error');
        });
}

async function changeLang(lang) {
    if (!lang) {
        return console.error('Please provide language to change.');
    }

    await fetch(`/repoxy/modules/changeLang.php?lang=${lang}`)
        .then(res => res.json())
        .then(res => {
            if (res.msg === 'OK')
                Swal.fire(strings[window.navigator.language.slice(0, 2)].chlangok, res.msg, 'success') ? Swal.fire(strings[window.navigator.language.slice(0, 2)].chlangok, res.msg, 'success').then(() => window.location.reload()) : Swal.fire(strings.en.chlangok, res.msg, 'success');
            else
                Swal.fire(strings[window.navigator.language.slice(0, 2)].err, res.msg, 'error') ? Swal.fire(strings[window.navigator.language.slice(0, 2)].err, res.msg, 'error') : Swal.fire(strings.en.err, res.msg, 'error');
        });
}

$('id', 'haddpostBtn').onclick = () => $('id', 'addpost').open = true;
$('id', 'heditpostBtn').onclick = () => $('id', 'editpost').open = true;
$('id', 'hchlangBtn').onclick = () => $('id', 'chlang').open = true;