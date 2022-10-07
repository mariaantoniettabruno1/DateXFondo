<?php

namespace dateXFondoPlugin;

class MasterTemplateStopEditingButton
{

    public static function render_scripts()
    {
        ?>
        <script>
            $(document).ready(function () {

                $('#stopEditingButton').click(function () {
                    $("#idAddRow").attr("disabled", true);
                    $("#btnSpecialRow").attr("disabled", true);
                    $("#btnDecurtazione").attr("disabled", true);

                    let fondo = $('#inputFondo').val();
                    let anno = $('#inputAnno').val();
                    let descrizione_fondo = $('#inputDescrizioneFondo').val();
                    let version = articoli[0].version;

                    const payload = {
                        fondo,
                        anno,
                        descrizione_fondo,
                        version
                    }
                    console.log(payload)
                   $.ajax({
                       url: '<?= DateXFondoCommon::get_website_url() ?>/wp-json/datexfondoplugin/v1/disabletemplate',
                       data: payload,
                       type: "POST",
                       success: function (response) {
                           console.log(response);
                       },
                       error: function (response) {
                           console.error(response);
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
        <button class="btn btn-link" id="stopEditingButton"><i class="fa-solid fa-ban"></i> Blocca la modifica</button>

        <?php
        self::render_scripts();
    }
}