function $(elem) {
  return document.querySelector(elem);
}

$('#wl_clinstall').onclick = () => {
  let cldiag = window.confirm('Installing isn\'t completed, are you sure?');

  if (cldiag)
    $('main').innerHTML = '<h2>Setup cancelled</h2><br><p class="subheading">You can close this page</p>';
}

$('#wl_continue').onclick = () => {
  $('#inst_welcome').remove();
  $('#inst_cfg').style.display = 'block';
}

let currlayout;
document.querySelectorAll('#opt_layout').forEach(elem => {
  elem.onclick = function () {
    currlayout = elem.innerText;
    elem.style.fontWeight = 'bold';
  }
});

$('#installBtn').onclick = () => {
  const cfg = {
    layout: currlayout ? decodeURI(currlayout) : 'default', // "layout" or theme or template
    blname: decodeURI($('#opt_blname').value), // blog name
    blauthor: decodeURI($('#opt_blauthor').value), // blog author
    bldesc: decodeURI($('#opt_bldesc').value), // blog description (used in about page and in <meta name="description">)
    blauthorpsw: decodeURI($('#opt_blauthorpsw').value), // blog author's password (used for admin panel)
    // contacts
    blemail: $('#opt_blemail').value, // email
    bltw: $('#opt_bltw').value, // twitter
    blfb: $('#opt_blfb').value, // facebook
    blrt: $('#opt_blreddit').value, // reddit
    blds: decodeURI($('#opt_blds').value), // discord
    // db settings
    dbpsw: decodeURI($('#opt_bldbpsw').value),
    dbuser: decodeURI($('#opt_bldbuser').value),
    dbname: decodeURI($('#opt_bldbname').value),
    dbhost: `${$('#opt_bldbhost').value}:${$('#opt_bldbport').value}`, // hostname
    // other options
    blang: $('#opt_blang').value, // blog language
    delSetup: $('#opt_delsetup').checked, // delete `/setup` folder
    resetdb: $('#opt_resetdb').checked // remove all old posts in DB
  };

  try {
    fetch(`/setup/applyconfig.php?blayout=${cfg.layout}&bname=${cfg.blname || 'My Blog'}&bauthor=${cfg.blauthor || 'John Doe'}&blauthorpsw=${cfg.blauthorpsw}&bdesc=${cfg.bldesc || 'This is my blog!'}&bemail=${cfg.blemail || ''}&btw=${cfg.bltw || ''}&bfb=${cfg.blfb || ''}&brt=${cfg.blrt || ''}&bds=${cfg.blds || ''}&deleteSetup=${cfg.delSetup}&resetDatabase=${cfg.resetdb}&blang=${cfg.blang || 'en'}&installDate=${new Date().toLocaleDateString()} ${new Date().toLocaleTimeString()}&dbpsw=${cfg.dbpsw}&dbname=${cfg.dbname}&dbuser=${cfg.dbuser}&dbhost=${cfg.dbhost}`, {
      method: 'POST'
    }).then(res => res.json())
      .then(res => {
        if (res.msg === 'OK') $('main').innerHTML =
          `<h2>Setup complete</h2><br>${cfg.delSetup ? '' : '<p class="subheading">Now you <b>must</b> delete <code>/setup</code> folder</p><br><br>'}<a href="/"><button class="btn-green">Go to blog</button></a>`;
        else $('main').innerHTML =
          `<h2 style="color: red;">Setup error</h2><br><p class="subheading">${res.msg}</p>`;
      });
  } catch (e) {
    $('main').innerHTML =
      `<h2 style="color: red;">Setup error</h2><br><p class="subheading">${e}</p>`;
  }
}