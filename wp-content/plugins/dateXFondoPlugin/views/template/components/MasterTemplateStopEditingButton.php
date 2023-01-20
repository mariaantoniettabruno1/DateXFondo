<?php

namespace dateXFondoPlugin;

class MasterTemplateStopEditingButton
{

    public static function render_scripts()
    {
        ?>

        <script>
            $(document).ready(function () {

                $('#stopEditTemplateButton').click(function () {
                    $("#idAddRow").attr("disabled", true);
                    $("#btnSpecialRow").attr("disabled", true);
                    $("#btnDecurtazione").attr("disabled", true);


                    let fondo = $('#inputFondo').val();
                    let anno = $('#inputAnno').val();
                    let descrizione_fondo = $('#inputDescrizioneFondo').val();
                    let template_name = $('#inputNomeTemplate').val();
                    let version = articoli[0].version;

                    const payload = {
                        fondo,
                        anno,
                        descrizione_fondo,
                        version,
                        template_name
                    }
                    console.log(payload)
                   $.ajax({
                       url: '<?= DateXFondoCommon::get_website_url() ?>/wp-json/datexfondoplugin/v1/disabletemplate',
                       data: payload,
                       type: "POST",
                       success: function (response) {
                           console.log(response);
                           $("#stopEditTemplateButton").attr("disabled", true);
                           location.href = '<?= DateXFondoCommon::get_website_url() ?>/all-template-table';
                       },
                       error: function (response) {
                           console.error(response);
                            $(".alert-block-wrong").show();
                            $(".alert-block-wrong").fadeTo(2000, 500).slideUp(500, function(){
                                $(".alert-block-wrong").slideUp(500);
                            });
                          // location.href = '<?= DateXFondoCommon::get_website_url() ?>/visualizza-template-fondo/?template_name=' + template_name;
                       }
                   });
                });
            });

        </script>
        <?php
    }

    public static function render()
    {
        ?>
        <button class="btn btn-link" id="stopEditingButton" data-toggle="modal" data-target="#stopEditModal"><i class="fa-solid fa-ban stopIcon" ></i> Blocca la modifica</button>
        <div class="modal fade" id="stopEditModal" tabindex="-1" role="dialog" aria-labelledby="stopEditModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="stopEditModalLabel">Blocca modifica sul template </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Vuoi bloccare definitivamente la modifica su questo template?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                        <button type="button" class="btn btn-primary" id="stopEditTemplateButton">Blocca</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-success alert-block-success" role="alert"
             style="position:fixed; top: <?= is_admin_bar_showing() ? 47 : 15 ?>px; right: 15px; display:none">
            Blocco modifica applicato correttamente!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-danger alert-block-wrong" role="alert"
             style="position:fixed; top: <?= is_admin_bar_showing() ? 47 : 15 ?>px; right: 15px; display:none">
            Blocco sulla modifica dei campi non riuscito
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php
        self::render_scripts();
    }
}