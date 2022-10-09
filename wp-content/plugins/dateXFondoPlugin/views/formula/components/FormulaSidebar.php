<?php

namespace dateXFondoPlugin;

class FormulaSidebar
{
    public static function render_scripts()
    {
        ?>
        <script>
            function renderDataTable(section, subsection) {
                $('#dataTableBody').html('');
                let filteredArticoli = articoli;
                if (section) {
                    filteredArticoli = filteredArticoli.filter(art => art.sezione === section)
                }
                if (subsection) {
                    filteredArticoli = filteredArticoli.filter(art => art.sottosezione === subsection)
                }
                filteredArticoli.forEach(art => {

                    let button = "";
                    if(art.row_type !== "decurtazione"){
                        button = `<button type="button" class="btn btn-sm btn-outline-primary" title="Aggiungi ${art.id_articolo} alla formula" onclick="insertIntoFormula('${art.id_articolo}')"><i class="fa-solid fa-plus"></i></button>`
                    } else {
                        button = `<button type="button" class="btn btn-sm btn-outline-success" title="Aggiungi decurtazione ${art.id_articolo} alla formula" onclick="insertDecurtazioneIntoFormula('${art.link}', '${art.id_articolo}')"><i class="fa-solid fa-plus"></i></button>`
                    }

                    $('#dataTableBody').append(`
                        <tr>
                          <td class="text-truncate" data-toggle="tooltip" title="${art.id_articolo}">
                           ${art.id_articolo}
                          </td>
                          <td class="text-truncate" data-toggle="tooltip" title="${art.nome_articolo}">
                           ${art.nome_articolo}
                          </td>
                          <td class="text-truncate" data-toggle="tooltip" title="${art.sottotitolo_articolo}">
                           ${art.sottotitolo_articolo}
                          </td>
                          <td>
                            ${button}
                            <button type="button" class="btn btn-sm btn-outline-secondary" title="Visualizza"><i class="fa-solid fa-eye"></i></button>
                          </td>
                        </tr>
                    `);
                });
            }

            function renderFormulaTables(section, subsection) {
                $('#formulaTableBody').html('');
                $('#conditionalTableBody').html('');
                let filteredFormule = formule;
                if (section) {
                    filteredFormule = filteredFormule.filter(f => f.sezione === section)
                }
                if (subsection) {
                    filteredFormule = filteredFormule.filter(f => f.sottosezione === subsection)
                }
                filteredFormule.forEach(f => {
                    let table;
                    if (Number(f.condizione) === 1) { <?php // QUANDO FACCIAMO IL JSON PARSE VIENE TUTTO CONVERTITO IN STRINGA. ?>
                        table = $('#conditionalTableBody');
                    } else {
                        table = $('#formulaTableBody');

                    }
                    table.append(`
                        <tr>
                          <td class="text-truncate" data-toggle="tooltip" title="${f.nome}">${f.nome}</td>
                          <td class="text-truncate" data-toggle="tooltip" title="${f.descrizione}">${f.descrizione}</td>
                          <td>
                            <button type="button" class="btn btn-sm btn-outline-primary" title="Aggiungi ${f.nome} alla formula" onclick="insertIntoFormula('${f.nome}')"><i class="fa-solid fa-plus"></i></button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" title="Visualizza" onclick="editFormula('${f.id}')"><i class="fa-solid fa-pencil"></i></button>
                          </td>
                        </tr>
                    `);
                });
            }

            function renderSectionFilter() {
                $('#inputSelectSezione').html('<option>Seleziona Sezione</option>');
                Object.keys(sezioni).forEach(sez => {
                    $('#inputSelectSezione').append(`<option>${sez}</option>`);
                });
            }

            function filterSubsections(section) {
                $('#inputSelectSottosezione').html('<option>Seleziona Sottosezione</option>');
                sezioni[section].forEach(ssez => {
                    $('#inputSelectSottosezione').append(`<option>${ssez}</option>`);
                });
            }

            function handleFilter() {
                let subsection = $('#inputSelectSottosezione').val();
                let section = $('#inputSelectSezione').val();
                if (subsection === 'Seleziona Sottosezione' || subsection==="") {
                    subsection = null
                }
                if (section === 'Seleziona Sezione') {
                    section = null
                }
                console.log(section, subsection);
                renderDataTable(section, subsection);
                renderFormulaTables(section, subsection);
            }

            $(document).ready(function () {
                renderDataTable();
                renderFormulaTables();
                renderSectionFilter();


                $('#inputSelectSezione').change(function () {
                    const section = $('#inputSelectSezione').val();
                    if (section !== 'Seleziona Sezione') {
                        $('#inputSelectSottosezione').attr('disabled', false);
                        filterSubsections(section);
                        renderDataTable(section);
                        renderFormulaTables(section);
                    } else {
                        $('#inputSelectSottosezione').attr('disabled', true);
                        $('#inputSelectSottosezione').html('');
                        renderDataTable();
                        renderFormulaTables();
                    }
                });
                $('#inputSelectSottosezione').change(function () {
                    handleFilter();
                });
            })
        </script>
        <?php
    }

    public static function render()
    {

        ?>
        <div id="accordionSidebar">
            <div class="card">
                <div class="card-header" id="headingDati">
                    <h5 class="mb-0">Filtri</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <select class="custom-select" id="inputSelectSezione">
                            </select>
                        </div>
                        <div class="col-6">
                            <select class="custom-select" id="inputSelectSottosezione" disabled>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="card">
                <div class="card-header" id="headingDati">
                    <h5 class="mb-0">
                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseDati"
                                aria-expanded="true" aria-controls="collapseDati">
                            Dati
                        </button>

                    </h5>
                </div>

                <div id="collapseDati" class="collapse show" aria-labelledby="headingDati"
                     data-parent="#accordionSidebar">
                    <div class="card-body">
                        <table class="table" style="table-layout: fixed">
                            <thead>
                            <tr>
                                <th style="width: 110px">Id Articolo</th>
                                <th>Nome</th>
                                <th>Sottotitolo</th>
                                <th style="width: 94px"></th>
                            </tr>
                            </thead>
                            <tbody id="dataTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingFormule">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFormule"
                                aria-expanded="false" aria-controls="collapseFormule">
                            Formule
                        </button>
                    </h5>
                </div>
                <div id="collapseFormule" class="collapse" aria-labelledby="headingFormule"
                     data-parent="#accordionSidebar">
                    <div class="card-body">
                        <table class="table" style="table-layout: fixed">
                            <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Descrizione</th>
                            </tr>
                            </thead>
                            <tbody id="formulaTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingCondizionali">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse"
                                data-target="#collapseCondizionali" aria-expanded="false"
                                aria-controls="collapseCondizionali">
                            Condizionali
                        </button>
                    </h5>
                </div>
                <div id="collapseCondizionali" class="collapse" aria-labelledby="headingCondizionali"
                     data-parent="#accordionSidebar">
                    <div class="card-body">
                        <table class="table" style="table-layout: fixed">
                            <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Descrizione</th>
                            </tr>
                            </thead>
                            <tbody id="conditionalTableBody">
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
        <?php
        self::render_scripts();
    }

}