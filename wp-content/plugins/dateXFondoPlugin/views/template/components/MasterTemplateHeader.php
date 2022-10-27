<?php

namespace dateXFondoPlugin;

class MasterTemplateHeader
{
    public static function render_scripts()
    {
        ?>
        <style>

            #editInputButton {
                color: #26282f;
            }

            #saveInputButton {
                color: #26282f;
            }
            #deleteEditButton{
                color: #26282f;
            }


        </style>

        <script>
            $(document).ready(function () {
                console.log(articoli)
                $('#inputFondo').val(`${articoli[0].fondo}`);
                $('#inputAnno').val(`${articoli[0].anno}`);
                $('#inputDescrizioneFondo').val(`${articoli[0].descrizione_fondo}`);
                $('#inputNomeTemplate').val(`${articoli[0].template_name}`);
                let old_template_name = $('#inputNomeTemplate').val();

                $("#editInputButton").click(function () {
                    $(this).hide();
                    $('#saveInputButton').show();
                    $('#deleteEditButton').show();
                    $('#inputFondo').attr('readonly', false);
                    $('#inputAnno').attr('readonly', false);
                    $('#inputDescrizioneFondo').attr('readonly', false);
                    $('#inputNomeTemplate').attr('readonly', false);

                });
                $("#deleteEditButton").click(function (){
                    $(this).hide();
                    $('#saveInputButton').hide();
                    $('#editInputButton').show();

                });
                $('#saveInputButton').click(function () {
                    {
                        let fondo = $('#inputFondo').val();
                        let anno = parseInt($('#inputAnno').val());
                        let descrizione_fondo = $('#inputDescrizioneFondo').val();
                        let template_name = $('#inputNomeTemplate').val();
                        $('#inputFondo').attr('readonly', true);
                        $('#inputAnno').attr('readonly', true);
                        $('#inputDescrizioneFondo').attr('readonly', true);
                        $('#inputNomeTemplate').attr('readonly', true);

                        const payload = {
                            fondo,
                            anno,
                            descrizione_fondo,
                            template_name,
                            old_template_name
                        }
                        console.log(payload)
                        $.ajax({
                            url: '<?= DateXFondoCommon::get_website_url() ?>/wp-json/datexfondoplugin/v1/templateheader',
                            data: payload,
                            type: "POST",
                            success: function (response) {
                                console.log(response);
                                $(".alert-header-success").show();
                                $(".alert-header-success").fadeTo(2000, 500).slideUp(500, function(){
                                    $(".alert-header-success").slideUp(500);
                                });
                            },
                            error: function (response) {
                                console.error(response);
                                $(".alert-header-wrong").show();
                                $(".alert-header-wrong").fadeTo(2000, 500).slideUp(500, function(){
                                    $(".alert-header-wrong").slideUp(500);
                                });
                            }
                        });
                        $('#saveInputButton').hide();
                        $('#deleteEditButton').hide();
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
        if (isset($_GET['fondo']) || isset($_GET['anno']) || isset($_GET['descrizione']) || isset($_GET['version'])) {
            $results_articoli = $data->visualize_template($_GET['fondo'], $_GET['anno'], $_GET['descrizione'], $_GET['version'], $_GET['template_name']);

        } else {
            $results_articoli = $data->getArticoli($_GET['template_name']);
        }        ?>
        <div class="col-2">
            <input type="text" placeholder="Fondo" id="inputFondo" readonly>
        </div>
        <div class="col-1 pl-0">
            <input type="text" placeholder="Anno" id="inputAnno" readonly>
        </div>
        <div class="col-4 pl-0">
            <input type="text" placeholder="Descrizione Fondo" id="inputDescrizioneFondo" readonly>
        </div>
        <div class="col-3 pl-0">
            <input type="text" placeholder="Nome Template" id="inputNomeTemplate" readonly>
        </div>
        <div class="col-2 pl-0">
            <?php
            if ($results_articoli[0]['editable'] == '1') {
                ?>
                <button class="btn btn-link" id="editInputButton"><i class="fa-solid fa-pen"></i></button>
                <button class="btn btn-link" id="saveInputButton" style="display: none"><i
                            class="fa-solid fa-floppy-disk"></i></button>
                <button class="btn btn-link" id="deleteEditButton" style="display: none"> Annulla </button>

                <?php
            } else {
                ?>
                <button class="btn btn-link" id="editInputButton" disabled><i class="fa-solid fa-pen"></i></button>
                <button class="btn btn-link" id="saveInputButton" style="display: none"><i
                            class="fa-solid fa-floppy-disk"></i></button>
                <button class="btn btn-link" id="deleteEditButton" style="display: none"> Annulla </button>
                <?php
            }
            ?>

        </div>
        <div class="alert alert-success alert-header-success" role="alert"
             style="position:fixed; top: <?= is_admin_bar_showing() ? 47 : 15 ?>px; right: 15px; display:none">
            Modifica eseguita correttamente!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-danger alert-header-wrong" role="alert"
             style="position:fixed; top: <?= is_admin_bar_showing() ? 47 : 15 ?>px; right: 15px; display:none">
            Modifica non riuscita
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php
        self::render_scripts();

    }
}