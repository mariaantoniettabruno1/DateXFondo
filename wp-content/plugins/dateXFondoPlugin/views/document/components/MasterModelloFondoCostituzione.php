<?php

use dateXFondoPlugin\DateXFondoCommon;

class MasterModelloFondoCostituzione
{
    public static function render_scripts()
    {
        ?>
        <script>
            function renderDataTable() {
                let filteredDocArticoli = articoli;
                for (let i = 0; i < sezioni.length; i++) {
                    $('#dataTemplateTableBody' + i).html('');
                    filteredDocArticoli = filteredDocArticoli.filter(art => art.sezione === sezioni[i])
                    console.log(sezioni[i])
                    filteredDocArticoli.forEach(art => {
                        $('#dataTemplateTableBody' + i).append(`
                                 <tr>
                                       <td></td>
                                       <td>${art.nome_articolo}</td>
                                       <td></td>
                                       <td>
                                       <div><button class="btn btn-link btn-delete-row" data-id='${art.id}' data-toggle="modal" data-target="#deleteModal"><i class="fa-solid fa-trash"></i></button></div>
                                     </td>
                                 </tr>
                             `);

                    });
                }

            }
            //spostare questa function in un altro file con il button per la conversione 
            function ExportExcel(index) {
                let worksheet_tmp1, a, sectionTable;
                let temp= [''];
                for (let i = 0; i < index; i++) {
                    sectionTable = document.getElementById('exportable_table' + i);
                    worksheet_tmp1 = XLSX.utils.table_to_sheet(sectionTable);
                    a = XLSX.utils.sheet_to_json(worksheet_tmp1, {header: 1})
                    temp = temp.concat(['']).concat(a)
                    }

                let worksheet = XLSX.utils.json_to_sheet(temp, {skipHeader: true})

                const new_workbook = XLSX.utils.book_new()
                XLSX.utils.book_append_sheet(new_workbook, worksheet, "worksheet")
                XLSX.writeFile(new_workbook, ('xlsx' + 'Dasein1.xlsx'))
            }

            //code html2odt
            // var req = new XMLHttpRequest();
            // req.open('GET', 'res/empty.odt');
            // req.responseType = 'arraybuffer';
            // req.addEventListener('load', function() {
            //     var empty = req.response;
            //
            //     var odtdoc = new ODTDocument(empty);
            //     try {
            //         odtdoc.setHTML(html);
            //     } catch(e) {
            //         alert("Couldn't generate odt document.");
            //         throw e;
            //     }
            //     var odt = odtdoc.getODT();
            // });
            // req.send();

            $(document).ready(function () {
                renderDataTable();


                $('#deleteRowButton').click(function () {
                    const payload = {
                        id
                    }

                    $.ajax({
                        //modificare perchè preso da altro codice
                        url: '<?= DateXFondoCommon::get_website_url() ?>/wp-json/datexfondoplugin/v1/delrow',
                        data: payload,
                        type: "POST",
                        success: function (response) {
                            console.log(response);
                            $("#deleteModal").modal('hide');
                            articoli = articoli.filter(art => Number(art.id) !== Number(id));
                            renderDataTable(section);
                            $(".alert-delete-success").show();
                            $(".alert-delete-success").fadeTo(2000, 500).slideUp(500, function () {
                                $(".alert-delete-success").slideUp(500);
                            });

                        },
                        error: function (response) {
                            console.error(response);
                            $("#deleteModal").modal('hide');
                            $(".alert-delete-wrong").show();
                            $(".alert-delete-wrong").fadeTo(2000, 500).slideUp(500, function () {
                                $(".alert-delete-wrong").slideUp(500);
                            });
                        }
                    });
                });

            });

        </script>
        <?php

    }

    public static function render()
    {

        $data = new DocumentRepository();
        $tot_sezioni = $data->getSezioni('Emanuela Sias');
        ?>
        <div class="accordion mt-2 col" id="accordionTemplateTable">
            <?php
            $section_index = 0;
            foreach ($tot_sezioni as $sezione) {
                ?>
                <div class="card" id="templateCard">
                    <div class="card-header" id="headingTemplateTable<?= $section_index ?>">
                        <button class="btn btn-link class-accordion-button" data-toggle="collapse"
                                data-target="#collapseTemplate<?= $section_index ?>"
                                aria-expanded="false" aria-controls="collapseTemplate<?= $section_index ?>"
                                data-section="<?= $sezione['sezione'] ?>">
                            <?= $sezione['sezione'] ?>
                        </button>
                    </div>
                    <div id="collapseTemplate<?= $section_index ?>" class="collapse"
                         aria-labelledby="headingTemplateTable<?= $section_index ?>"
                         data-parent="#accordionTemplateTable">
                        <div class="card-body">
                            <table class="table datetable" id="exportable_table<?= $section_index ?>">
                                <thead>
                                <tr>
                                    <th>Ordinamento</th>
                                    <th>Nome Articolo</th>
                                    <th >Preventivo</th>
                                    <th>Azioni</th>
                                </tr>

                                </thead>
                                <tbody id="dataTemplateTableBody<?= $section_index ?>">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php
                $section_index++;
            }
            ?>
        </div>
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Cancella riga </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Vuoi veramente eliminare questa riga?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                        <button type="button" class="btn btn-primary" id="deleteRowButton">Cancella</button>
                    </div>
                </div>
            </div>
        </div>
        <button onclick="ExportExcel(<?= $section_index ?>)">Genera Foglio Excel</button>
        <?php
        self::render_scripts();

    }
}