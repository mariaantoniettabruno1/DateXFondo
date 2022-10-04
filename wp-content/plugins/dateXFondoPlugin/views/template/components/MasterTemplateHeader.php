<?php

namespace dateXFondoPlugin;

class MasterTemplateHeader
{
    public static function render_scripts()
    {
        ?>

        <script>
            $(document).ready(function () {
                $('#inputFondo').val(`${articoli[0].fondo}`);
                $('#inputAnno').val(`${articoli[0].anno}`);
                $('#inputDescrizioneFondo').val(`${articoli[0].descrizione_fondo}`);

                $("#editInputButton").click(function () {
                    $(this).hide();
                });
                $('#saveInputButton').click(function () {
                    {
                        let fondo = $('#inputFondo').val();
                        let anno = $('#inputAnno').val();
                        let descrizione_fondo = $('#inputDescrizioneFondo').val();

                        const payload = {
                            fondo,
                            anno,
                            descrizione_fondo
                        }
                        $.ajax({
                            url: '<?= DateXFondoCommon::get_website_url() ?>/wp-json/datexfondoplugin/v1/templateheader',
                            data: payload,
                            type: "POST",
                            success: function (response) {
                                console.log(response);
                            },
                            error: function (response) {
                                console.error(response);
                            }
                        });
                        $(this).hide();
                        $('#editInputButton').show();
                    }
                });
            });
        </script>
        <?php
    }

    public static function render()
    {
        ?>
        <div class="col-3">
            <input type="text" placeholder="Fondo" id="inputFondo">
        </div>
        <div class="col-3">
            <input type="text" placeholder="Anno" id="inputAnno">
        </div>
        <div class="col-3">
            <input type="text" placeholder="Descrizione Fondo" id="inputDescrizioneFondo">
        </div>
        <div class="col-3">
            <button class="btn btn-link" id="editInputButton"><i class="fa-solid fa-pen"></i></button>
            <button class="btn btn-link" id="saveInputButton"><i class="fa-solid fa-floppy-disk"></i></button>
        </div>
        <?php
        self::render_scripts();

    }
}