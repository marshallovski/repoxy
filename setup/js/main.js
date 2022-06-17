function $(type, elem) {
  if (type === "id") {
    return document.getElementById(elem);
  } else if (type === 'cl') {
    return document.querySelector(elem);
  }
}

$('id', 'wl_clinstall').onclick = function () {
  let cldiag = window.confirm('Installing isn\'t completed, are you sure?');

  if (cldiag) {
    $('cl', 'main').innerHTML = '<h2>Setup cancelled</h2><br><p class="subheading">You can close this page</p>';
  }
}

$('id', 'wl_continue').onclick = function () {
  $('id', 'inst_welcome').remove();
  $('id', 'inst_cfg').style.display = 'block';
}

let currlayout;
document.querySelectorAll('#opt_layout').forEach(elem => {
  elem.onclick = function () {
    currlayout = elem.innerText;
    elem.style.fontWeight = 'bold';
  }
});

$('id', 'installBtn').onclick = function () {
  const cfg = {
    layout: currlayout ? decodeURI(currlayout) : 'default',
    blname: decodeURI($('id', 'opt_blname').value),
    blauthor: decodeURI($('id', 'opt_blauthor').value),
    bldesc: decodeURI($('id', 'opt_bldesc').value),
    blauthorpsw: decodeURI($('id', 'opt_blauthorpsw').value),
    blemail: $('id', 'opt_blemail').value,
    bltw: $('id', 'opt_bltw').value,
    blfb: $('id', 'opt_blfb').value,
    blrt: $('id', 'opt_blreddit').value,
    blds: decodeURI($('id', 'opt_blds').value),
    dbpsw: decodeURI($('id', 'opt_bldbpsw').value),
    dbuser: decodeURI($('id', 'opt_bldbuser').value),
    dbname: decodeURI($('id', 'opt_bldbname').value),
    dbhost: `${$('id', 'opt_bldbhost').value}:${$('id', 'opt_bldbport').value}`
  };

  window.fetch(`/setup/applyconfig.php?installed=true&blayout=${cfg.layout}&bname=${cfg.blname || 'My Blog'}&bauthor=${cfg.blauthor || 'John Doe'}&blauthorpsw=${cfg.blauthorpsw}&bdesc=${cfg.bldesc || 'This is my blog!'}&bemail=${cfg.blemail || 'none'}&btw=${cfg.bltw || 'none'}&bfb=${cfg.blfb || 'none'}&brt=${cfg.blrt || 'none'}&bds=${cfg.blds || 'none'}&installDate=${new Date().toLocaleDateString()} ${new Date().toLocaleTimeString()}&dbpsw=${cfg.dbpsw}&dbname=${cfg.dbname}&dbuser=${cfg.dbuser}&dbhost=${cfg.dbhost}`, {
    method: 'POST'
  }).then(res => res.json())
    .then(res => {
      if (res.msg === 'OK') {
        $('cl', 'main').innerHTML =
          '<h2>Setup complete</h2><br><p class="subheading">You can delete <code>setup.php</code> and <code>/setup</code> folder</p><br><br><a href="/"><button class="btn-green">Go to blog</button></a>';
      } else {
        $('cl', 'main').innerHTML =
          `<h2 style="color: red;">Setup error</h2><br><p class="subheading">${res.msg}</p>`;
      }
    })
}