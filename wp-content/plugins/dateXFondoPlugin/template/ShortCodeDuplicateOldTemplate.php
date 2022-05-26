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
            <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
                  integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU"
                  crossorigin="anonymous">

            <style type="text/css">
                .field_description > span{
                    overflow: hidden;
                    max-height: 200px;
                    height: 100%;
                    display: inline-block;
                }

                .field_section > select {
                    width : 150px;
                }
            </style>
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
                        <td class="field_section">
                            <select class="toggleable-select" data-field="sezione" data-id="<?= $entry[0] ?>">
                                <option disabled selected value> <?php echo $entry[5]; ?></option>
                                <?php foreach ($sezioni as $sezione) {
                                    print_r($sezione)
                                    ?>
                                    <option
                                            value='<?php echo $sezione; ?>'
                                            data-id="<?= $entry[0] ?>"><?php echo $sezione; ?></option>
                                <?php } ?>
                            </select>
                        </td>


                        <td class="field_description">
                            <span class="toggleable-span">
                                <?php echo $entry[6]; ?>
                            </span>
                            <input type="text" class="toggleable-input" value='<?php echo $entry[6]; ?>'
                                   style="display: none" data-field="descrizione_campo" data-id="<?= $entry[0] ?>"
                            /></td>
                        <td class="field_description">
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
                                   style="display: none" data-field="label_campo" data-id="<?= $entry[0] ?>"
                            /></td>
                        <td>   <span class="toggleable-span">
                                <?php echo $entry[9]; ?>
                            </span>
                            <input type="text" class="toggleable-input" value='<?php echo $entry[9]; ?>'
                                   style="display: none" data-field="valore" data-id="<?= $entry[0] ?>"
                            /></td>
                        <td>   <span class="toggleable-span">
                                <?php echo $entry[10]; ?>
                            </span>
                            <input type="text" class="toggleable-input" value='<?php echo $entry[10]; ?>'
                                   style="display: none" data-field="valore_anno_precedente" data-id="<?= $entry[0] ?>"
                            /></td>
                        <td>
                              <span class="toggleable-span">
                                 <?php echo $entry[11]; ?>
                            </span>
                            <input type="text" class="toggleable-input" value='<?php echo $entry[11]; ?>'
                                   style="display: none" data-field="nota" data-id="<?= $entry[0] ?>"
                            /></td>
                        <td>
                            <div class="toggleable-radio" data-field="attivo" data-id="<?= $entry[0] ?>">
                                <label><input type="radio" name=<?php echo $entry[0]; ?> checked value='1'> Si</label>
                                <label><input type="radio"
                                              name=<?php echo $entry[0]; ?> onclick="disabledRow(<?php echo $entry[0]; ?>)"
                                              value='0'>No</label>
                            </div>
                        </td>
                    </tr>

                    <?php

                }
                ?>
                </tbody>
            </table>
            <table id="newTable" class="table table-striped table-bordered">
                <thead style="display:none;">
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
                <?php  $newRowID = $old_template->getLastRowID(); ?>
                <tr>
                    <td style="display: none"><?php echo $newRowID; ?></td>
                    <td>  <span class="toggleable-span">
                                <?php echo $fondo; ?>
                            </span>
                        <input type="text" class="toggleable-input" value='<?php echo $fondo; ?>'
                               style="display: none" data-field="fondo" data-id="<?= $newRowID ?>"
                        /></td>
                    <td>
                             <span class="toggleable-span">
                                <?php echo $ente; ?>
                            </span>
                        <input type="text" class="toggleable-input" value='<?php echo $ente; ?>'
                               style="display: none" data-field="ente" data-id="<?= $newRowID ?>"
                        /></td>
                    <td>
                              <span class="toggleable-span">
                                <?php echo $anno; ?>
                            </span>
                        <input type="text" class="toggleable-input" value='<?php echo $anno; ?>'
                               style="display: none" data-field="ente" data-id="<?= $newRowID ?>"
                        /></td>
                    <td>
                            <span class="toggleable-span">

                            </span>
                        <input type="text" class="toggleable-input" value='ì'
                               style="display: none" data-field="id_campo" data-id="<?= $newRowID ?>"
                        />
                    </td>
                    <td class="field_section">
                        <select class="toggleable-select" data-field="sezione" data-id="<?= $newRowID?>">
                            <option disabled selected value> <?php echo $entry[5]; ?></option>
                            <?php foreach ($sezioni as $sezione) {
                                print_r($sezione)
                                ?>
                                <option
                                        value='<?php echo $sezione; ?>'
                                        data-id="<?= $entry[0] ?>"><?php echo $sezione; ?></option>
                            <?php } ?>
                        </select>
                    </td>


                    <td class="field_description">
                            <span class="toggleable-span">

                            </span>
                        <input type="text" class="toggleable-input" value=''
                               style="display: none" data-field="descrizione_campo" data-id="<?= $newRowID ?>"
                        /></td>
                    <td class="field_description">
                             <span class="toggleable-span">

                            </span>
                        <input type="text" class="toggleable-input" value=''
                               style="display: none" data-field="sottotitolo_campo" data-id="<?= $newRowID ?>"
                        /></td>
                    <td>   <span class="toggleable-span">

                            </span>
                        <input type="text" class="toggleable-input" value=''
                               style="display: none" data-field="label_campo" data-id="<?= $newRowID ?>"
                        /></td>
                    <td>   <span class="toggleable-span">

                            </span>
                        <input type="text" class="toggleable-input" value=''
                               style="display: none" data-field="valore" data-id="<?= $newRowID ?>"
                        /></td>
                    <td>   <span class="toggleable-span">

                            </span>
                        <input type="text" class="toggleable-input" value=''
                               style="display: none" data-field="valore_anno_precedente" data-id="<?= $newRowID ?>"
                        /></td>
                    <td>
                              <span class="toggleable-span">
                                 <?php echo $entry[11]; ?>
                            </span>
                        <input type="text" class="toggleable-input" value=''
                               style="display: none" data-field="nota" data-id="<?= $newRowID ?>"
                        /></td>
                    <td>
                        <div class="toggleable-radio" data-field="attivo" data-id="<?= $newRowID ?>">
                            <label><input type="radio" name=<?php echo $newRowID; ?> checked value='1'> Si</label>
                            <label><input type="radio"
                                          name=<?php echo $entry[0]; ?> onclick="disabledRow(<?php echo $newRowID; ?>)"
                                          value='0'>No</label>
                        </div>
                    </td>
                </tr>
                </tbody>

            </table>
            <br>
        </div>
        <div class="well clearfix">
            <a class="btn btn-primary pull-right add-record"><i class="glyphicon glyphicon-plus"></i>Aggiungi nuova Riga</a>
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
                $(".toggleable-select").change(changeValue)
                $(".toggleable-radio").change(changeValue)
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
                        //elem.siblings(".toggleable-radio").text(value);
                    },
                    error: function () {
                        successmessage = 'Modifica non riuscita non riuscita';
                        console.log(successmessage);
                    }
                });
            }

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
                        console.log(element.find('select'))
                        element.attr('id', response.id);
                        element.appendTo('#newTable');
                        element.find('.tabledit-identifier').html(response.id);
                        element.find('.tabledit-identifier').attr('value', response.id);
                        element.find('.sn').html(response.id);
                        element.show();
                        element.find('select').change(function () {
                            updateNewRowValue(response.id, $(this).val())
                        });
                        element.find('input').change(function () {
                            updateNewRowValue(response.id, $(this).val())
                        });
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
                        id
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
            function updateNewRowValue(id,sezione) {

                $.ajax({
                    type: "POST",
                    url: "https://demo.mg3.srl/date/wp-json/datexfondoplugin/v1/table/editnewfondo",
                    data: {
                        sezione,id
                    },
                    success: function () {
                        console.log(sezione)
                        successmessage = 'Valori aggiunti correttamente';
                        console.log(successmessage)
                    },
                    error: function () {
                        successmessage = 'Errore, valori non aggiunti correttamente';
                        console.log(successmessage);
                    }
                });
            }
        </script>
        </html>

        <?php


    }
}