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
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"
                  integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A=="
                  crossorigin="anonymous" referrerpolicy="no-referrer"/>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
            <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
                    integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
                    crossorigin="anonymous"></script>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
                    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
                    crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
                    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
                    crossorigin="anonymous"></script>

            <style type="text/css">

                .field_description > span {
                    overflow: hidden;
                    max-height: 200px;
                    min-width: 40px;
                    min-height: 40px;
                    display: inline-block;
                }

                .field_section > select {
                    width: 150px;
                }


            </style>
        </head>

        <body>

        <h2>TEMPLATE FONDO (MASTER)</h2>
        <div class="table-responsive">

            <table id="dataTable">
                <thead>
                <tr>
                    <th>ID Articolo</th>
                    <th>Nome Articolo</th>
                    <th>Descrizione Articolo</th>
                    <th>Sottotitolo Articolo</th>
                    <th>Valore</th>
                    <th>Valore anno precedente</th>
                    <th>Nota</th>
                    <th>Link associato</th>
                    <th>Azioni</th>
                </tr>
                </thead>
                <tbody id="tbl_posts_body">
                <?php

                $fondo = 'Fondo 2022';
                $anno = 2022;

                $old_template = new DuplicateOldTemplate();


                //TODO capire se anche per il master il template del fondo ha un titolo oppure la duplicazione avviene solo per anno
                $old_data = $old_template->getCurrentData($anno, $fondo);

                foreach ($old_data as $entry) {
                    ?>
                    <tr>
                        <td style="display: none"><?php echo $entry[0]; ?></td>
                        <td class="field_description">
                            <span id="inputIdArticolo">
                                <?php echo $entry[3]; ?>
                            </span>

                        </td>
                        <td class="field_description">
                            <span>
                                <?php echo $entry[6]; ?>
                            </span>
                        </td>
                        <td class="field_description">
                            <span>
                                <?php echo $entry[7]; ?>
                            </span>
                        </td>
                        <td class="field_description">
                             <span>
                                <?php echo $entry[8]; ?>
                            </span>
                        </td>
                        <td class="field_description">
                            <span>
                                <?php echo $entry[9]; ?>
                            </span>
                        </td>
                        <td class="field_description">   <span class="toggleable-span">
                                <?php echo $entry[10]; ?>
                            </span>
                            <input type="text" class="toggleable-input" value='<?php echo $entry[10]; ?>'
                                   style="display: none" data-field="valore_anno_precedente" data-id="<?= $entry[0] ?>"
                            /></td>
                        <td class="field_description">
                              <span class="toggleable-span">
                                 <?php echo $entry[11]; ?>
                            </span>
                            <input type="text" class="toggleable-input" value='<?php echo $entry[11]; ?>'
                                   style="display: none" data-field="nota" data-id="<?= $entry[0] ?>"
                            /></td>
                        <td class="field_description">
                              <span class="toggleable-span">
                                 <?php echo $entry[12]; ?>
                            </span>
                            <input type="text" class="toggleable-input" value='<?php echo $entry[12]; ?>'
                                   style="display: none" data-field="link" data-id="<?= $entry[0] ?>"
                            /></td>
                        <td>
                            <div class="container">
                                <button type="button" class="btn btn-link" data-toggle="modal"
                                        data-target="#editModal<?php echo $entry[0]; ?>">
                                    <i class="fa-solid fa-pen"></i></button>
                                <button type="button" class="btn btn-link"><i class="fa-solid fa-trash"></i></button>
                            </div>

                        </td>
                        <!--                        <td class="field_description">-->
                        <!--                            <div class="toggleable-radio" data-field="attivo" data-id="-->
                        <?//= $entry[0] ?><!--">-->
                        <!--                                <label><input type="radio" name="-->
                        <?php //echo $entry[0]; ?><!--" checked value='1'> Si</label>-->
                        <!--                                <label><input type="radio"-->
                        <!--                                              name="-->
                        <?php //echo $entry[0]; ?><!--" class="disabledRow"-->
                        <!--                                              value='0'>No</label>-->
                        <!--                            </div>-->
                        <!--                        </td>-->
                    </tr>
                    <div class="modal fade" id="editModal<?php echo $entry[0]; ?>" tabindex="-1" role="dialog"
                         aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Modifica riga del fondo:</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="form-group">
                                            <label for="inputIdArticolo">Id Articolo</label>
                                            <input type="text" class="form-control" id="inputIdArticolo"
                                                   value='<?php echo $entry[3]; ?>'>
                                        </div>
                                        <div class="form-group">
                                            <label for="idNomeArticolo">Nome Articolo</label>
                                            <input type="text" class="form-control" id="idNomeArticolo"
                                                   value='<?php echo $entry[6]; ?>'>
                                        </div>
                                        <div class="form-group">
                                            <label for="idSottotitoloArticolo">Sottotitolo Articolo</label>
                                            <textarea class="form-control"
                                                      id="idSottotitoloArticolo"><?php echo $entry[8]; ?> </textarea>

                                        </div>
                                        <div class="form-group">
                                            <label for="idDescrizioneArticolo">Descrizione Articolo</label>
                                            <textarea class="form-control"
                                                      id="idDescrizioneArticolo"> <?php echo $entry[7]; ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="idLinkAssociato">Link associato</label>
                                            <input type="text" class="form-control" id="idLinkAssociato"
                                                   value='<?php echo $entry[12]; ?>'>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button id="submit_button" type="button" class="btn btn-primary">Salva modifica
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <h5 class="modal-title" id="exampleModalLabel">Sei sicuro di voler eliminare questa
                                        riga?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla
                                    </button>
                                    <button type="button" class="btn btn-primary" onclick="disabledRow()">Elimina
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php

                }
                ?>
                </tbody>
            </table>

            <table id="newTable" class="table table-striped table-bordered">
                <thead style="display:none;">
                <tr>
                    <th>ID</th>

                    <th>ID Articolo</th>

                    <th>Sezione</th>

                    <th>Sottosezione</th>

                    <th>Nome Articolo</th>

                    <th>Descrizione Articolo</th>

                    <th>Sottotitolo Articolo</th>

                    <th>Valore</th>

                    <th>Valore Anno Precedente</th>

                    <th>Nota</th>

                    <th>Link di riferimento</th>

                    <th>Attivo</th>

                </tr>
                </thead>
                <?php $newRowID = $old_template->getLastRowID(); ?>
                <tr style="display: none">
                    <td style="display: none"><?php echo $newRowID; ?></td>

                    <td class="field_description">
                            <span class="toggleable-span">
                            </span>
                        <input type="text" class="toggleable-input" value=''
                               style="display: none" data-field="id_campo" data-id=""
                        />
                    </td>
                    </td>
                    <td class="field_description">
                            <span class="toggleable-span">

                            </span>
                        <input type="text" class="toggleable-input" value=''
                               style="display: none" data-field="sezione" data-id=""
                        /></td>
                    <td class="field_description">
                            <span class="toggleable-span">

                            </span>
                        <input type="text" class="toggleable-input" value=''
                               style="display: none" data-field="sottosezione" data-id=""
                        /></td>
                    <td class="field_description">
                            <span class="toggleable-span">

                            </span>
                        <input type="text" class="toggleable-input" value=''
                               style="display: none" data-field="label_campo" data-id=""
                        /></td>

                    <td class="field_description">
                            <span class="toggleable-span">

                            </span>
                        <input type="text" class="toggleable-input" value=''
                               style="display: none" data-field="descrizione_campo" data-id=""
                        /></td>
                    <td class="field_description">
                             <span class="toggleable-span">

                            </span>
                        <input type="text" class="toggleable-input" value=''
                               style="display: none" data-field="sottotitolo_campo" data-id=""
                        /></td>
                    <td class="field_description">   <span class="toggleable-span">

                            </span>
                        <input type="text" class="toggleable-input" value=''
                               style="display: none" data-field="valore" data-id=""
                        /></td>
                    <td class="field_description">   <span class="toggleable-span">

                            </span>
                        <input type="text" class="toggleable-input" value=''
                               style="display: none" data-field="valore_anno_precedente" data-id=""
                        /></td>
                    <td class="field_description">
                              <span class="toggleable-span">
                            </span>
                        <input type="text" class="toggleable-input" value=''
                               style="display: none" data-field="nota" data-id=""
                        /></td>
                    <td></td>
                    <td class="field_description">
                        <div class="toggleable-radio" data-field="attivo" data-id="">
                            <label><input type="radio" name="" checked value='1'> Si</label>
                            <label><input type="radio"
                                          name="" class="disabledRow"
                                          value='0'>No</label>
                        </div>
                    </td>
                </tr>
                </tbody>

            </table>


        </div>
        <div class="well clearfix">
            <a class="btn btn-primary pull-right add-record text-white">Aggiungi nuova Riga</a><br>
        </div>
        </body>
        <form method="post">
            <input type="submit" name="button1"
                   class="button" value="Blocca la Modifica"/>
            <input type="submit" name="button2"
                   class="button" value="Duplica la Tabella"/>
        </form>
        <?php
        $years = new DuplicateOldTemplate();
        $readOnly = $years->isReadOnly($anno);
        if (!$readOnly && array_key_exists('button1', $_POST)) {
            $years->getTableNotEditable($anno);
        } else if ($readOnly && array_key_exists('button2', $_POST)) {
            $years->duplicateTable($anno);
            $years->deleteReadOnly($anno);
            header("Refresh:0");
        }

        ?>

        <script>
            let readOnly = <?php echo $readOnly?>;
            if (!readOnly) {
                $(document).ready(function () {
                        $(document).on('click', '.editModal', function () {
                            var id = $(this).val();
                            console.log(id);
                            let first = $('#inputIdArticolo' + id).text();
                            let last = $('#idSottotitoloArticolo' + id).text();
                            let address = $('#idDescrizioneArticolo' + id).text();

                            $('#editModal').modal('show');
                            $('#inputIdArticolo').val(first);
                            $('#idSottotitoloArticolo').val(last);
                            $('#idDescrizioneArticolo').val(address);
                        });

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
                        $(".disabledRow").click(disabledRow)
                    }
                )

                function changeValue() {
                    const elem = $(this);
                    var value = elem.val();
                    const id = elem.data("id");
                    const field = elem.data("field");
                    const data = {id};
                    data[field] = value;
                    $.ajax({
                        type: "POST",
                        url: "https://demo.mg3.srl/date/wp-json/datexfondoplugin/v1/table/editnewfondo",
                        data,
                        success: function () {
                            successmessage = 'Modifica eseguita correttamente';
                            console.log(successmessage);
                            elem.siblings(".toggleable-span").text(value);
                            elem.siblings(".toggleable-select").text(value);
                            elem.siblings(".toggleable-radio").val(value);
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
                            $myObj = ["fondo" => $fondo, "anno" => $anno];
                            ?>
                            "JSONIn":<?php echo json_encode($myObj);?>},
                        success: function (response) {
                            successmessage = 'Riga creata correttamente';
                            alert(successmessage);
                            var content = jQuery('#newTable  tr:last'),
                                element = content.clone(true, true);
                            element.attr('id', response.id);
                            element.appendTo('#dataTable');
                            element.find('input').attr("data-id", response.id);
                            element.find('select').attr("data-id", response.id);
                            element.find('.toggleable-radio').attr("data-id", response.id);
                            element.find('.toggleable-radio').find("input").attr("name", response.id);
                            element.show();
                        },
                        error: function () {
                            successmessage = 'Errore: creazione riga non riuscita';
                            alert(successmessage);
                        }
                    });

                });

                function disabledRow() {
                    const id = $(this).parent().parent().attr("data-id");
                    const data = {id};
                    console.log(data)
                    console.log($(this))
                    $.ajax({
                        type: "POST",
                        url: "https://demo.mg3.srl/date/wp-json/datexfondoplugin/v1/table/deleterow",
                        data,
                        success: function () {

                            location.href = "https://demo.mg3.srl/date/duplicazione-template-anno-precedente/"

                            function toggleAlert() {
                                $(".alert").toggleClass('in out');
                                return false;
                            }
                        },
                        error: function () {

                        }
                    })
                    ;
                }

                function updateNewRowValue(id, sezione) {

                    $.ajax({
                        type: "POST",
                        url: "https://demo.mg3.srl/date/wp-json/datexfondoplugin/v1/table/editnewfondo",
                        data: {
                            sezione, id
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
            }


        </script>
        <div class="alert alert-success fade out" id="bsalert">
            This is a success alert—check it out!
        </div>
        </html>

        <?php
    }


}