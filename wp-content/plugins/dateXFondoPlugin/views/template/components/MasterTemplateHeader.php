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
                    $('#saveInputButton').show();
                    $('#inputFondo').attr('readonly', false);
                    $('#inputAnno').attr('readonly', false);
                    $('#inputDescrizioneFondo').attr('readonly', false);
                });
                $('#saveInputButton').click(function () {
                    {
                        let fondo = $('#inputFondo').val();
                        let anno = parseInt($('#inputAnno').val());
                        let descrizione_fondo = $('#inputDescrizioneFondo').val();
                        $('#inputFondo').attr('readonly', true);
                        $('#inputAnno').attr('readonly', true);
                        $('#inputDescrizioneFondo').attr('readonly', true);

                        const payload = {
                            fondo,
                            anno,
                            descrizione_fondo
                        }
                        console.log(payload)
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
                        $('#saveInputButton').hide();
                        $('#editInputButton').show();
                    }
                });
            });
        </script>
        <?php
    }

    public static function render()
    {
        $data = new MasterTemplateRepository();
        $results_articoli = $data->getArticoli();
        ?>
        <div class="col-3">
            <input type="text" placeholder="Fondo" id="inputFondo" readonly>
        </div>
        <div class="col-3">
            <input type="text" placeholder="Anno" id="inputAnno" readonly>
        </div>
        <div class="col-3">
            <input type="text" placeholder="Descrizione Fondo" id="inputDescrizioneFondo" readonly>
        </div>
        <div class="col-3">
            <?php
            if ($results_articoli[0]['editable'] == '1') {
                ?>
                <button class="btn btn-link" id="editInputButton"><i class="fa-solid fa-pen"></i></button>
                <button class="btn btn-link" id="saveInputButton" style="display: none"><i
                            class="fa-solid fa-floppy-disk"></i></button>
                <?php
            } else {
                ?>
                <button class="btn btn-link" id="editInputButton" disabled><i class="fa-solid fa-pen"></i></button>
                <button class="btn btn-link" id="saveInputButton" style="display: none"><i
                            class="fa-solid fa-floppy-disk"></i></button>
                <?php
            }
            ?>

        </div>
        <?php
        self::render_scripts();

    }
}