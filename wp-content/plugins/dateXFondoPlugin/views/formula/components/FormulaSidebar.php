<?php

namespace dateXFondoPlugin;

class FormulaSidebar
{
    public static function render_scripts()
    {
        ?>
        <script>
            function renderDataTable(section,subsection) {
                $('#dataTableBody').html('');
                let filteredArticoli = articoli;
                if(section){
                    filteredArticoli = filteredArticoli.filter(art=>art.sezione === section)
                }
                if(subsection)
                {
                    filteredArticoli = filteredArticoli.filter(art=>art.sottosezione === subsection)
                }
                filteredArticoli.forEach(art => {
                    $('#dataTableBody').append(`
<tr>
      <td>${art.id_articolo}</td>
      <td>${art.nome_articolo}</td>
      <td>${art.sottotitolo_articolo}</td>
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

            $(document).ready(function () {
                renderDataTable();
                renderSectionFilter();
                $('#inputSelectSezione').change(function () {
                    const section = $('#inputSelectSezione').val();
                    if (section !== 'Seleziona Sezione') {
                        $('#inputSelectSottosezione').attr('disabled', false);
                        filterSubsections(section);
                        renderDataTable(section);
                    } else {
                        $('#inputSelectSottosezione').attr('disabled', true);
                        $('#inputSelectSottosezione').html('');
                        renderDataTable();
                    }
                });
                $('#inputSelectSottosezione').change(function () {
                    const subsection = $('#inputSelectSottosezione').val();
                    const section = $('#inputSelectSezione').val();

                    if (subsection !== 'Seleziona Sottosezione') {
                        renderDataTable(section,subsection);
                    } else {
                        renderDataTable(section);
                    }
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
                    <h5 class="mb-0">
                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseDati"
                                aria-expanded="true" aria-controls="collapseDati">
                            Storico Dati
                        </button>

                    </h5>
                </div>

                <div id="collapseDati" class="collapse show" aria-labelledby="headingDati"
                     data-parent="#accordionSidebar">
                    <div class="card-body">
                        <div class="row pb-2">
                            <div class="col-6">
                                <select class="custom-select" id="inputSelectSezione">
                                </select>
                            </div>
                            <div class="col-6">
                                <select class="custom-select" id="inputSelectSottosezione" disabled>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <table class="table col">
                                <thead>
                                <tr>
                                    <th>Id Articolo</th>
                                    <th>Nome</th>
                                    <th>Sottotitolo</th>
                                </tr>
                                </thead>
                                <tbody id="dataTableBody">
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingFormule">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFormule"
                                aria-expanded="false" aria-controls="collapseFormule">
                            Storico Formule
                        </button>
                    </h5>
                </div>
                <div id="collapseFormule" class="collapse" aria-labelledby="headingFormule"
                     data-parent="#accordionSidebar">
                    <div class="card-body">

                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingCondizionali">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse"
                                data-target="#collapseCondizionali" aria-expanded="false"
                                aria-controls="collapseCondizionali">
                            Storico Condizionali
                        </button>
                    </h5>
                </div>
                <div id="collapseCondizionali" class="collapse" aria-labelledby="headingCondizionali"
                     data-parent="#accordionSidebar">
                    <div class="card-body">

                    </div>
                </div>
            </div>
        </div>
        <?php
        self::render_scripts();
    }

}