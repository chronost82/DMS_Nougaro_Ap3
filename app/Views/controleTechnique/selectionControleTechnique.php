<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div id="divCt">
</div>
<script>
  let updateView = async function() {
    let response = fetch("<?= url_to('controle-technique-selection-ajax') ?>");
    let ctList = await response;
    let url = '<?= url_to('ecran-controle-technique', 9999999) ?>';
    ctList = await ctList.json();
    divCt.innerHTML = "";
    ctList.forEach(function(ct) {
      let a = document.createElement('a');
      a.href = url.replace('9999999', ct.IDCT);
      a.innerText = ct.NOM + " " + ct.PRENOM + " - " + ct.MARQUE + " " + ct.MODELE;
      divCt.appendChild(a);
    });

  };
  setInterval(updateView, 2000);
</script>
<?= $this->endsection('content') ?>