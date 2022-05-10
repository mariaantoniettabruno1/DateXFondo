<?php

namespace dateXFondoPlugin;

use GFAPI;

class ShortCodeDuplicateOldTemplate
{
    public static function visualize_old_template()
    {
        ?>


        <!DOCTYPE html>

        <html lang="en">

        <head>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
            <script type="text/javascript" src="https://unpkg.com/jquery-tabledit@1.0.0/jquery.tabledit.js"></script>
            <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
                  integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU"
                  crossorigin="anonymous">

        </head>
        <body>

        <h2>TABELLA NUOVO FONDO TEMPLATE DUPLICATO</h2>
        <div class="well clearfix">
            <a class="btn btn-primary pull-right add-record"><i class="glyphicon glyphicon-plus"></i>Aggiungi nuova Riga</a>
        </div>
        <div class="table-responsive">

            <table id="defaultTable" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>ID</th>

                    <th>Fondo</th>

                    <th>Ente</th>

                    <th>Anno</th>

                    <th>ID Campo</th>

                    <th>Sezione</th>

                    <th>Label Campo</th>

                    <th>Descrizione Campo</th>

                    <th>Sottotitolo Campo</th>

                    <th>Valore</th>

                    <th>Valore Anno Precedente</th>

                    <th>Nota</th>

                    <th>Attivo</th>
                </tr>
                </thead>
                <tbody id="tbl_posts_body">
                <?php
                $entry_gforms = GFAPI::get_entries(7);
                if (!empty($entry_gforms)) {
                    $fondo = $entry_gforms[0][1];
                    $ente = $entry_gforms[0][26];
                    $anno = $entry_gforms[0][25];
                }
                $old_template = new DuplicateOldTemplate();
                $old_data = $old_template->getCurrentData($ente, $anno, $fondo);
                foreach ($old_data as $entry) {
                    ?>
                    <tr>
                        <td><?php echo $entry[0]; ?></td>
                        <td><?php echo $fondo; ?></td>
                        <td><?php echo $ente; ?></td>
                        <td><?php echo $anno; ?></td>
                        <td><?php echo $entry[4]; ?></td>
                        <td></td>
                        <td><?php echo $entry[5]; ?></td>
                        <td><?php echo $entry[6]; ?></td>
                        <td><?php echo $entry[7]; ?></td>
                        <td><?php echo $entry[8]; ?></td>
                        <td><?php echo $entry[9]; ?></td>
                        <td><?php echo $entry[10]; ?></td>
                        <td>
                            <div class="radio">
                                <label><input type="radio" name=<?php echo $entry[0]; ?> checked> Si</label>
                                <label><input type="radio"
                                              name=<?php echo $entry[0]; ?> onclick="disabledRow(<?php echo $entry[0]; ?>)">No</label>
                            </div>
                        </td>
                    </tr>

                    <?php
                    $sezioni = [0 => 'Risorse fisse aventi carattere di certezza e stabilità - Risorse storiche',
                        1 => 'Risorse fisse aventi carattere di certezza e stabilità - Incrementi stabili ART. 67 C.2 CCNL 2018',
                        2 => 'Risorse fisse aventi carattere di certezza e stabilità - Incrementi con carattere di certezza e stabilità non soggetti a limite',
                        3 => 'Risorse fisse aventi carattere di certezza e stabilità - Decurtazioni (a detrarre)',
                        4 => 'Risorse variabili - risorse variabili sottoposte al limite',
                        5 => 'Risorse variabili - risorse variabili non sottoposte al limite'];
                }
                ?>
                </tbody>
            </table>
            <div >
                <table id="newTable">
                    <tr style="display:none;">
                        <td></td>
                        <td><?php echo $fondo;?></td>
                        <td><?php echo $ente;?></td>
                        <td><?php echo $anno;?></td>
                        <td></td>
                        <td>
                            <select id="idSection" onchange="updateSection()">
                                <?php foreach ($sezioni as $sezione) {
                                    ?>
                                    <option value=<?php echo $sezione; ?>><?php echo $sezione; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            <div class="radio">
                                <label><input type="radio" value="" checked> Si</label>
                                <label><input type="radio"> No</label>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="well clearfix">
            <a class="btn btn-primary pull-right add-record"><i class="glyphicon glyphicon-plus"></i>Aggiungi nuova Riga</a>
        </div>
        </body>
        <script>

            $(document).ready(function () {

                $('#defaultTable').Tabledit({
                    hideIdentifier: false,
                    editButton: false,
                    deleteButton: false,
                    columns: {
                        identifier: [0, 'id'],
                        editable: [[9, 'valore'], [10, 'valore anno precedente'], [11, 'nota']]
                    },

                    url: 'https://demo.mg3.srl/date/wp-json/datexfondoplugin/v1/table/edit',
                });
                $('#newTable').Tabledit({
                    hideIdentifier: false,
                    editButton: false,
                    deleteButton: false,
                    columns: {
                        identifier: [0, 'id'],
                        editable: [[1,'fondo'],
                            [2,'ente'],
                            [3,'anno'],
                            [4,'id_campo'],
                            [6,'label_campo'],
                            [7,'descrizione_campo'],
                            [8,'sottotitolo_campo'],
                            [9,'valore'],
                            [10,'valore_anno_precedente'],
                            [11,'nota']]
                    },

                    url: 'https://demo.mg3.srl/date/wp-json/datexfondoplugin/v1/table/edit',
                });

            });

            $(document).delegate('a.add-record', 'click', function (e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "https://demo.mg3.srl/date/wp-json/datexfondoplugin/v1/table/newrow",
                    data: {   <?php
                        $myObj = ["fondo" => $fondo, "ente" => $ente, "anno" => $anno];
                        ?>
                        "JSONIn":<?php echo json_encode($myObj);?>},
                    success: function (response) {
                        successmessage = 'Riga creata correttamente';
                        alert(successmessage);
                        var content = jQuery('#newTable  tr'),
                            element = content.clone();
                        console.log(element.find('td'))
                        element.attr('id', response.id);
                        element.appendTo('#tbl_posts_body');
                        element.find('.tabledit-identifier').html(response.id);
                        element.find('.tabledit-identifier').attr('value',response.id);
                        element.find('.sn').html(response.id);
                        element.show();
                    },
                    error: function () {
                        successmessage = 'Errore: creazione riga non riuscita';
                        alert(successmessage);
                    }
                });

            });


            function disabledRow(id) {
                $.ajax({
                    type: "POST",
                    url: "https://demo.mg3.srl/date/wp-json/datexfondoplugin/v1/table/deleterow",
                    data: {
                        id: id
                    },
                    success: function () {
                        successmessage = 'Riga cancellata correttamente';
                        alert(successmessage);
                        location.href = "https://demo.mg3.srl/date/duplicazione-template-anno-precedente/"
                    },
                    error: function () {
                        successmessage = 'Errore: cancellazione riga non riuscita';
                        alert(successmessage);
                    }
                });
            }
            function updateSection() {
                var x = document.getElementById("idSection").value;
                console.log(x);
            }
        </script>
        </html>

        <?php


    }
}