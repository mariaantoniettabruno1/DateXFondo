<?php

class MasterModelloFondoNewUtilizzoRow
{
public static function render_scripts(){
    ?>
    <script>
            function renderUtilizzoSectionFilterRow() {
                $('#selectNewUtilizzoRowSezione').html('<option>Seleziona Sezione</option>');
                Object.keys(sezioniUtilizzoList).forEach(sez => {
                    $('#selectNewUtilizzoRowSezione').append(`<option>${sez}</option>`);
                });
            }

            function filterUtilizzoSubsectionsRow(section) {
                $('#selectNewUtilizzoRowSottosezione').html('<option>Seleziona Sottosezione</option>');
                sezioniUtilizzoList[section].forEach(ssez => {
                    $('#selectNewUtilizzoRowSottosezione').append(`<option>${ssez}</option>`);
                });
            }

            function clearUtilizzoInputRow() {
                $('#selectNewUtilizzoRowSezione').prop('selectedIndex', 0);
                $('#selectNewUtilizzoRowSottosezione').prop('selectedIndex', -1);
                $('#newUtilizzoRowSottosezione').val('');
                $('#newUtilizzoRowOrdinamento').val('');
                $('#newUtilizzoRowNomeArticolo').val('');
                $('#newUtilizzoRowPreventivo').val('');
                $('#newUtilizzoRowConsuntivo').val('');
            }

            $(document).ready(function () {
                clearUtilizzoInputRow();
                renderUtilizzoSectionFilterRow();
                $('#selectNewUtilizzoRowSezione').change(function () {
                    const section = $('#selectNewUtilizzoRowSezione').val();
                    if (section !== 'Seleziona Sezione') {
                        filterUtilizzoSubsectionsRow(section);
                    } else {
                        $('#selectNewUtilizzoRowSottosezione').html('');
                    }
                });
                $('.subsectionUtilizzoButtonGroup1').click(function () {
                    $('#selectNewUtilizzoRowSottosezione').show();
                    $('#newUtilizzoRowSottosezione').attr('style', 'display:none');
                });
                $('.subsectionUtilizzoButtonGroup2').click(function () {
                    $('#newUtilizzoRowSottosezione').attr('style', 'display:block');
                    $('#selectNewUtilizzoRowSottosezione').hide();
                });
                $('#addNewUtilizzoRowButton').click(function () {
                    {
                        let nome_articolo = $('#newUtilizzoRowNomeArticolo').val();
                        let ordinamento = $('#newUtilizzoRowOrdinamento').val();
                        let preventivo = $('#newUtilizzoRowPreventivo').val();
                        let consuntivo = $('#newUtilizzoRowConsuntivo').val();
                        let sezione = '';
                        if (sezione !== 'Seleziona Sezione') {
                            sezione = $('#selectNewUtilizzoRowSezione').val();
                        }
                        let sottosezione = '';
                        if ($('#selectNewUtilizzoRowSottosezione').val() != null && $('#selectNewUtilizzoRowSottosezione').val() !== 'Seleziona Sottosezione') {
                            sottosezione = $('#selectNewUtilizzoRowSottosezione').val();
                        } else if ($('#newUtilizzoRowSottosezione').val() != null) {
                            sottosezione = $('#newUtilizzoRowSottosezione').val();
                        }

                        let document_name = $('#inputDocumentName').val();
                        let anno = $('#inputYear').val();
                        if (articoli.find(art => art.nome_articolo === nome_articolo) === undefined && sezione !== 'Seleziona Sezione' && sottosezione!=='Seleziona Sottosezione') {
                        const payload = {
                            nome_articolo,
                            ordinamento,
                            preventivo,
                            consuntivo,
                            sezione,
                            sottosezione,
                            document_name,
                            anno
                            }
                            console.log(payload)
                            $.ajax({
                                url: '<?= DateXFondoCommon::get_website_url() ?>/wp-json/datexfondoplugin/v1/newutilizzorow',
                                data: payload,
                                type: "POST",
                                success: function (response) {
                            $("#addUtilizzoRowModal").modal('hide');
                            articoli.push({...payload, id: response['id']});
                                    renderDataTable();
                                    $(".alert-new-row-success").show();
                                    $(".alert-new-row-success").fadeTo(2000, 500).slideUp(500, function () {
                                        $(".alert-new-row-success").slideUp(500);
                                    });
                                    clearUtilizzoInputRow();
                                },
                                error: function (response) {
                            $("#addUtilizzoRowModal").modal('hide');
                            console.error(response);
                            $(".alert-new-row-wrong").show();
                            $(".alert-new-row-wrong").fadeTo(2000, 500).slideUp(500, function () {
                                $(".alert-new-row-wrong").slideUp(500);
                            });
                        }
                            });
                        }  else if (sezione !== 'Seleziona Sezione') {
                        $("#errorSection").attr('style', 'display:block');
                    }
                    else if(sottosezione !== 'Seleziona Sottosezione'){
                        $("#errorSubsection").attr('style', 'display:block');

                    }

                    }
                });
            });
            </script>
        <?php

    }

    public static function render()
    {
        $data = new DocumentRepository();
        $results_articoli = $data->getArticoliUtilizzo('Emanuele Lesca');
        $formulas = $data->getFormulas('Emanuele Lesca');
        $ids_articolo = $data->getIdsArticoli('Emanuele Lesca');
        $array = $formulas + $ids_articolo;

        //TODO filter per togliere i valori vuoti
//        for( $i=0; $i<count($results_articoli); $i++){
//            if($results_articoli[$i]['preventivo']===null){
//                array_splice($results_articoli[$i], 'preventivo', 1);
//            }
//            echo '<pre>';
//            print_r($results_articoli[$i]);
//            echo '</pre>';
//        }
//


if ($results_articoli[0]['editable'] == '1') {
    ?>
    <button class="btn btn-outline-primary" data-toggle="modal"
            data-target="#addUtilizzoRowModal" id="idAddUtilizzoRow">Aggiungi riga utilizzo
    </button>
    <?php
} else {
    ?>
    <button class="btn btn-outline-primary" data-toggle="modal"
            data-target="#addUtilizzoRowModal" id="idAddUtilizzoRow" disabled>Aggiungi riga utilizzo
    </button>
    <?php
}
?>
<div class="modal fade" id="addUtilizzoRowModal" tabindex="-1"
     role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Nuova riga:</b></h5>
                <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="selectCostRowSezione"><b>Sezione:</b></label>
                    <select class="custom-select" id="selectNewUtilizzoRowSezione">
                    </select>
                    <small id="errorSection" class="form-text text-danger" style="display: none">Campo
                        Obbligatorio</small>
                </div>
                <div class="form-group" id="divSelectNewUtilizzoRowSottosezione">
                    <br>
                    <div class="btn-group pb-3" role="group" aria-label="Basic example">
                        <button type="button" class="btn  btn-outline-primary subsectionUtilizzoButtonGroup1">
                            Seleziona Sottosezione
                        </button>
                        <button type="button" class="btn btn-outline-primary subsectionUtilizzoButtonGroup2">
                            Nuova Sottosezione
                        </button>
                    </div>
                    <div class="form-group">
                        <select class="custom-select" id="selectNewUtilizzoRowSottosezione">
                        </select>
                        <small id="errorSubsection" class="form-text text-danger" style="display: none">Campo
                            Obbligatorio</small>
                    </div>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="newUtilizzoRowSottosezione" style="display:none">
                    <small id="errorSubsection" class="form-text text-danger" style="display: none">Campo
                        Obbligatorio</small>
                </div>

                <div class="form-group">
                    <label for="inputOrdinamento"><b>Ordinamento:</b></label>
                    <input type="text" class="form-control" id="newUtilizzoRowOrdinamento"></div>
                <div class="form-group">
                    <label for="inputNomeArticolo"><b>Articolo:</b> </label>
                    <input type="text" class="form-control" id="newUtilizzoRowNomeArticolo">
                </div>
                <div class="form-group">
                    <label for="idPreventivo"><b>Preventivo: </b></label>

                    <select name="newUtilizzoRowPreventivo" id="newUtilizzoRowPreventivo">
                        <?php
                        foreach ($array as $item) {
                            ?>
                            <option><?= $item[0] ?></option>
                        <?php }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="inputConsuntivo"><b>Consuntivo:</b> </label>
                    <input type="text" class="form-control" id="newUtilizzoRowConsuntivo">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="addNewUtilizzoRowButton">Aggiungi riga</button>
            </div>
        </div>
    </div>
</div>


<div class="alert alert-success alert-new-row-success" role="alert"
     style="position:fixed; top: <?= is_admin_bar_showing() ? 47 : 15 ?>px; right: 15px; display:none">
    Nuova riga aggiunta correttamente!
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="alert alert-danger alert-new-row-wrong" role="alert"
     style="position:fixed; top: <?= is_admin_bar_showing() ? 47 : 15 ?>px; right: 15px; display:none">
    Aggiunta nuova riga non riuscita
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php
        self::render_scripts();
    }

}