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
    if (id)
        return $('id', `editptxt_${id}`).value;
    else
        return window.editor.getData();
}

function getPostName(id) {
    if (id)
        return $('id', `editpname_${id}`).value;
    else
        return $('id', 'addpostname').value;
}

// @TODO: показывать только один алерт
$('id', 'addpostBtn').onclick = async () => {
    if (!getPostText() || !getPostName())
        return null;

    if (getPostText().length > 4000 && !getPostText().includes('img'))
        return Swal.fire(
            'Error',
            strings[window.navigator.language.slice(0, 2)].toolong,
            'error'
        );


    const addpostQuery = `/repoxy/modules/addPost.php?postcontent=${encodeURI(getPostText().replaceAll('&nbsp;', ' '))}&postname=${encodeURI(getPostName())}&postcreation=${new Date().toLocaleDateString()} ${new Date().toLocaleTimeString()}`;

    await fetch(addpostQuery)
        .then(res => res.status === 200 ? res.json() : Swal.fire(strings[window.navigator.language.slice(0, 2)].err, res.statusText, 'error'))
        .then(res => {
            strings[window.navigator.language.slice(0, 2)].successaddpost && res.msg ?
                Swal.fire(
                    strings[window.navigator.language.slice(0, 2)].successaddpost, res.msg || 'Try again', 'success'
                ) : Swal.fire(strings.en.err, res.msg || 'Try again', 'error');
        });
}

async function editPost(id) {
    const editpostQuery = `/repoxy/modules/editPost.php?postname=${encodeURI(getPostName(id))}&postcontent=${encodeURI(getPostText(id))}&postupdated=${new Date().toLocaleDateString()} ${new Date().toLocaleTimeString()}&postid=${id}`;
    await fetch(editpostQuery)
        .then(res => res.status === 200 ? res.json() : Swal.fire(strings[window.navigator.language.slice(0, 2)].err, res.statusText, 'error'))
        .then(res => {
            strings[window.navigator.language.slice(0, 2)].editpostok && res.msg ?
                Swal.fire(
                    strings[window.navigator.language.slice(0, 2)].editpostok, res.msg || 'Try again', 'success'
                ) : Swal.fire(strings.en.err, res.msg || 'Try again', 'error');
        });
}

async function deletePost(id) {
    const delpostQuery = `/repoxy/modules/deletePost.php?postid=${id}`;
    await fetch(delpostQuery)
        .then(res => res.status === 200 ? res.json() : Swal.fire(strings[window.navigator.language.slice(0, 2)].err, res.statusText, 'error'))
        .then(res => {
            strings[window.navigator.language.slice(0, 2)].delpostok && res.msg ?
                (() => {
                    $('id', `post_${id}`).remove(); // removing div with already deleted post

                    Swal.fire(
                        strings[window.navigator.language.slice(0, 2)].delpostok, res.msg || 'Try again', 'success'
                    );
                })()
                : Swal.fire(strings.en.err, res.msg || 'Try again', 'error');
        });
}

async function changeLang(lang) {
    if (!lang)
        return console.error('Please provide language to change.');

    await fetch(`/repoxy/modules/changeLang.php?lang=${lang}`)
        .then(res => res.status === 200 ? res.json() : Swal.fire(strings[window.navigator.language.slice(0, 2)].err, res.statusText, 'error'))
        .then(res => {
            strings[window.navigator.language.slice(0, 2)].chlangok && res.msg ?
                Swal.fire(
                    strings[window.navigator.language.slice(0, 2)].chlangok, res.msg || 'Try again', 'success'
                ).then(() => window.location.reload()) :
                Swal.fire(strings.en.err, res.msg || 'Try again', 'error');
        });
}

$('id', 'haddpostBtn').onclick = () => $('id', 'addpost').open = true;
$('id', 'heditpostBtn').onclick = () => $('id', 'editpost').open = true;
$('id', 'hchlangBtn').onclick = () => $('id', 'chlang').open = true;
$('id', 'logoutBtn').onclick = () => logout();

// https://stackoverflow.com/questions/179355/clearing-all-cookies-with-javascript
function logout() {
    // This function will attempt to remove a cookie from all paths.
    var pathBits = location.pathname.split('/');
    var pathCurrent = ' path=';

    // do a simple pathless delete first.
    document.cookie = 'PHPSESSID' + '=; expires=Thu, 01-Jan-1970 00:00:01 GMT;';

    for (var i = 0; i < pathBits.length; i++) {
        pathCurrent += ((pathCurrent.substring(-1) != '/') ? '/' : '') + pathBits[i];
        document.cookie = 'PHPSESSID' + '=; expires=Thu, 01-Jan-1970 00:00:01 GMT;' + pathCurrent + ';';
    }

    window.location.href = '/admlogin';
}