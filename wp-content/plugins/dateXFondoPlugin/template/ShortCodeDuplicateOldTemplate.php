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
                $sezioni = [0 => 'Risorse fisse aventi carattere di certezza e stabilità - Risorse storiche',
                    1 => 'Risorse fisse aventi carattere di certezza e stabilità - Incrementi stabili ART. 67 C.2 CCNL 2018',
                    2 => 'Risorse fisse aventi carattere di certezza e stabilità - Incrementi con carattere di certezza e stabilità non soggetti a limite',
                    3 => 'Risorse fisse aventi carattere di certezza e stabilità - Decurtazioni (a detrarre)',
                    4 => 'Risorse variabili - risorse variabili sottoposte al limite',
                    5 => 'Risorse variabili - risorse variabili non sottoposte al limite'];
                $old_data = $old_template->getCurrentData($ente, $anno, $fondo);

                foreach ($old_data as $entry) {

                    ?>
                    <tr>
                        <td style="display: none"><?php echo $entry[0]; ?></td>
                        <td>  <span class="toggleable-span">
                                <?php echo $fondo; ?>
                            </span>
                            <input type="text" class="toggleable-input" value='<?php echo $fondo; ?>'
                                   style="display: none" data-field="fondo" data-id="<?= $entry[0] ?>"
                            /></td>
                        <td>
                             <span class="toggleable-span">
                                <?php echo $ente; ?>
                            </span>
                            <input type="text" class="toggleable-input" value='<?php echo $ente; ?>'
                                   style="display: none" data-field="ente" data-id="<?= $entry[0] ?>"
                            /></td>
                        <td>
                              <span class="toggleable-span">
                                <?php echo $anno; ?>
                            </span>
                            <input type="text" class="toggleable-input" value='<?php echo $anno; ?>'
                                   style="display: none" data-field="ente" data-id="<?= $entry[0] ?>"
                            /></td>
                        <td>
                            <span class="toggleable-span">
                                <?php echo $entry[4]; ?>
                            </span>
                            <input type="text" class="toggleable-input" value='<?php echo $entry[4]; ?>'
                                   style="display: none" data-field="id_campo" data-id="<?= $entry[0] ?>"
                            />
                        </td>
                        <td style="width: 400px">
                            <select class="toggleable-select">
                                <option disabled selected value class="toggleable-option" data-field="sezione"
                                        value='<?php echo $entry[5]; ?>'
                                        data-id="<?= $entry[0] ?>"> <?php echo $entry[5]; ?></option>
                                <?php foreach ($sezioni as $sezione) {
                                    print_r($sezione)
                                    ?>
                                    <option class="toggleable-option" data-field="sezione"
                                            value='<?php echo $sezione; ?>'
                                            data-id="<?= $entry[0] ?>"><?php echo $sezione; ?></option>
                                <?php } ?>
                            </select></td>


                        <td>  <span class="toggleable-span">
                                <?php echo $entry[6]; ?>
                            </span>
                            <input type="text" class="toggleable-input" value='<?php echo $entry[6]; ?>'
                                   style="display: none" data-field="descrizione_campo" data-id="<?= $entry[0] ?>"
                            /></td>
                        <td>
                             <span class="toggleable-span">
                                <?php echo $entry[7]; ?>
                            </span>
                            <input type="text" class="toggleable-input" value='<?php echo $entry[7]; ?>'
                                   style="display: none" data-field="sottotitolo_campo" data-id="<?= $entry[0] ?>"
                            /></td>
                        <td>   <span class="toggleable-span">
                                <?php echo $entry[8]; ?>
                            </span>
                            <input type="text" class="toggleable-input" value='<?php echo $entry[8]; ?>'
                                   style="display: none" data-field="valore" data-id="<?= $entry[0] ?>"
                            /></td>
                        <td>   <span class="toggleable-span">
                                <?php echo $entry[9]; ?>
                            </span>
                            <input type="text" class="toggleable-input" value='<?php echo $entry[9]; ?>'
                                   style="display: none" data-field="valore_anno_precedente" data-id="<?= $entry[0] ?>"
                            /></td>
                        <td>   <span class="toggleable-span">
                                <?php echo $entry[10]; ?>
                            </span>
                            <input type="text" class="toggleable-input" value='<?php echo $entry[10]; ?>'
                                   style="display: none" data-field="nota" data-id="<?= $entry[0] ?>"
                            /></td>
                        <td>
                              <span class="toggleable-span">
                                <?php echo $entry[11]; ?>
                            </span>
                            <input type="text" class="toggleable-input" value='<?php echo $entry[11]; ?>'
                                   style="display: none" data-field="label_campo" data-id="<?= $entry[0] ?>"
                            /></td>
                        <td>
                            <div class="radio">
                                <label><input type="radio" name=<?php echo $entry[0]; ?> checked> Si</label>
                                <label><input type="radio"
                                              name=<?php echo $entry[0]; ?> onclick="disabledRow(<?php echo $entry[0]; ?>)">No</label>
                            </div>
                        </td>
                    </tr>

                    <?php

                }
                ?>
                </tbody>
            </table>
            </table>
        </div>
        </div>
        </body>
        <script>
            $(document).ready(function () {
                $(".toggleable-span").click(function () {
                    $(this).hide();
                    $(this).siblings(".toggleable-input").show().focus();
                })
                $(".toggleable-input").blur(function () {
                    $(this).hide();
                    $(this).siblings(".toggleable-span").show();
                })
                $(".toggleable-input").change(changeValue)

                $(".toggleable-option").change(changeValue)
            })

            function changeValue() {
                const elem = $(this);
                var value = elem.val();
                const id = elem.data("id");
                const field = elem.data("field");
                const data = {id};
                data[field] = value;
                console.log(data)
                $.ajax({
                    type: "POST",
                    url: "https://demo.mg3.srl/date/wp-json/datexfondoplugin/v1/table/editnewfondo",
                    data,
                    success: function () {
                        successmessage = 'Modifica eseguita correttamente';
                        console.log(successmessage);
                        elem.siblings(".toggleable-span").text(value);
                        elem.siblings(".toggleable-select").text(value);
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