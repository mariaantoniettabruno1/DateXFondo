<?php

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
                        console.log(art)
                        $('#dataTemplateTableBody' + i).append(`
                                 <tr>
                                       <td>${art.nome_articolo}</td>
                                       <td></td>
                                       <td><div class="row pr-3">
                                           <div class="col-3"></div>
                                           <div class="col-3"></div>
                                       </div></td>
                                 </tr>
                             `);

                    });
                }

            }

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
                                    <th>Id Articolo</th>
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

        <button onclick="ExportExcel(<?= $section_index ?>)">Genera Foglio Excel</button>
        <?php
        self::render_scripts();

    }
}