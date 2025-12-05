<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div id="divCt">
</div>
<script>
    let updateView = async function() {
        let response = fetch("<?= url_to('controle-technique-selection-ajax') ?>");
        let ctList = await response;
    };

  updateView();
</script>
<?= $this->endsection('content') ?>