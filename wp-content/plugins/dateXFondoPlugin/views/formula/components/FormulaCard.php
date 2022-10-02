<?php

namespace dateXFondoPlugin;

class FormulaCard

{
    public static function render_scripts()
    {
        ?>
        <script>

            function renderSectionInput() {
                $('#inputSelectSezioneFormula').html('<option>Seleziona Sezione</option>');
                Object.keys(sezioni).forEach(sez => {
                    $('#inputSelectSezioneFormula').append(`<option>${sez}</option>`);
                });
            }


            function renderSubsectionInput(section) {
                $('#inputSelectSottosezioneFormula').html('<option>Seleziona Sottosezione</option>');
                sezioni[section].forEach(ssez => {
                    $('#inputSelectSottosezioneFormula').append(`<option>${ssez}</option>`);
                });
            }

            $(document).ready(function () {
                renderSectionInput();
                $('#inputSelectSezioneFormula').change(function () {
                    const section = $('#inputSelectSezioneFormula').val();
                    if (section !== 'Seleziona Sezione') {
                        $('#inputSelectSottosezioneFormula').attr('disabled', false);
                        renderSubsectionInput(section);
                    } else {
                        $('#inputSelectSottosezioneFormula').attr('disabled', true);
                        $('#inputSelectSottosezioneFormula').html('');
                    }
                });
                $('#insertFormula').click(function (){
                    {
                        let sezione = $('#inputSelectSezioneFormula').val();
                        let sottosezione = $('#inputSelectSottosezioneFormula').val();
                        let nome = $('#inputNomeFormula').val();
                        let descrizione = $('#inputDescrizioneFormula').val();
                        let formula = $('#inputFormula').val();
                        let visibile = $('#inputCheckboxVisibileFormula').prop('checked') ? 1 : 0;
                        if (sezione === 'Seleziona Sezione') {
                            sezione = null;
                        }
                        if (sottosezione === 'Seleziona Sottosezione') {
                            sottosezione = null;
                        }

                        const payload = {
                            sezione,
                            sottosezione,
                            nome,
                            descrizione,
                            formula,
                            visibile
                        }
                        $.ajax({
                            url: '<?= DateXFondoCommon::get_website_url() ?>/wp-json/datexfondoplugin/v1/formula',
                            data: payload,
                            type: "POST",
                            success: function (response) {
                                console.log(response);

                            },
                            error: function (response) {
                                console.error(response);
                            }
                        })
                    }
                });
            });

        </script>
        <?php
    }
    public static function render()
    {

        ?>
            <div class="card mb-2">
                <div class="card-header">
                    Informazioni Formula
                </div>
                <div class="card-body">
                    <div class="row pb-2">
                        <div class="col-4"><input type="text" class="form-control" id="inputNomeFormula"
                                                  placeholder="Inserisci nome"
                                                  aria-label="NomeFormula" aria-describedby="basic-addon1">
                        </div>
                        <div class="col-8">
                            <input type="text" class="form-control" id="inputDescrizioneFormula"
                                   placeholder="Inserisci descrizione" aria-label="DescrizioneFormula"
                                   aria-describedby="basic-addon1"></div>
                    </div>
                    <div class="row pb-2">
                        <div class="col">
                            <select class="custom-select" id="inputSelectSezioneFormula">
                            </select>
                        </div>
                        <div class="col-6">
                            <select class="custom-select" id="inputSelectSottosezioneFormula" disabled>
                            </select>
                        </div>
                    </div>
                    <div class="row pb-2">
                        <div class="col">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="true" id="inputCheckboxVisibileFormula">
                                <label class="form-check-label" for="defaultCheck1">
                                    Visibile
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div id="accordion">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne"
                                    aria-expanded="true" aria-controls="collapseOne">
                                Formula
                            </button>
                        </h5>
                    </div>

                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            <div class="row pb-2">
                                <div class="col">
                                    <input type="text" class="form-control" id="inputFormula"
                                           placeholder="Inserisci formula"
                                           aria-label="Formula" aria-describedby="basic-addon1"></div>
                            </div>
                            <div class="d-flex flex-row justify-content-end">
                                <button class="btn btn-outline-primary" id="insertFormula">Aggiungi</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingTwo">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo"
                                    aria-expanded="false" aria-controls="collapseTwo">
                                Condizionale
                            </button>
                        </h5>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">
                            <div class="row pb-2">
                                <div class="col">
                                    <input type="text" class="form-control" id="inputCondizione"
                                           placeholder="Inserisci condizione" aria-label="Condizione"
                                           aria-describedby="basic-addon1"></div>
                            </div>
                            <div class="row pb-2">
                                <div class="col"><input type="text" class="form-control" id="InputVero"
                                                        placeholder="Se vero"
                                                        aria-label="CondizionaleVero" aria-describedby="basic-addon1">
                                </div>
                            </div>
                            <div class="row pb-2">
                                <div class="col">
                                    <input type="text" class="form-control" id="inputFalso" placeholder="Se falso"
                                           aria-label="CondizionaleFalso" aria-describedby="basic-addon1">
                                </div>
                            </div>

                            <div class="d-flex flex-row justify-content-end">
                                <button class="btn btn-outline-primary" id="insertCondition">Aggiungi</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php
        self::render_scripts();
    }
}