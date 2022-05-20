<?php

namespace dateXFondoPlugin;

use GFAPI;

header('Content-Type: text/javascript');

class ShortCodeDuplicateOldTemplate
{
    public static function visualize_old_template()
    {

        ?>


        <!DOCTYPE html>

        <html lang="en">

        <head>

            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
            <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
                  integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU"
                  crossorigin="anonymous">
        </head>
        <body>

        <h2>TABELLA NUOVO FONDO TEMPLATE DUPLICATO</h2>
        <div class="table-responsive">

            <table id="dataTable" style="width:50%">
                <thead>
                <tr>
                    <th>ID</th>
                    <th style="width:70%">Fondo</th>
                    <th>Ente</th>
                    <th>Anno</th>
                    <th>ID Campo</th>
                    <th>Sezione</th>
                    <th>Label campo</th>
                    <th>Descrizione campo</th>
                    <th>Sottotitolo campo</th>
                    <th>Valore</th>
                    <th>Valore anno precedente</th>
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
                        <td id="idTdId">
                            <span id="spanId">
                                <?php echo $entry[4]; ?>
                            </span>
                            <input type="text" id="inputID" value='<?php echo $entry[4]; ?>' style="display: none"
                                   onchange="changeValue('<?php echo $entry[0]; ?>')"/>
                        </td>
                        <td>Sezione</td>
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
            </table>
        </div>
        </div>
        </body>
        <script>
            $("#idTdId").click(function () {
                $("#spanId").hide();
                $("#inputID").show();
            });

            function changeValue(id) {
                var id_campo = document.getElementById("inputID").value;
                console.log(id_campo);
                $.ajax({
                    type: "POST",
                    url: "https://demo.mg3.srl/date/wp-json/datexfondoplugin/v1/table/editnewfondo",
                    data: {
                        id, id_campo
                    },
                    success: function () {
                        successmessage = 'Modifica eseguita correttamente';
                        console.log(successmessage);
                        $("#inputID").hide();
                        $("#spanId").show();
                        var span = document.getElementById("spanId");
                        span.textContent = id_campo;

                    },
                    error: function () {
                        successmessage = 'Modifica non riuscita non riuscita';
                        console.log(successmessage);
                    }
                });
            }
        </script>
        </html>

        <?php


    }
}