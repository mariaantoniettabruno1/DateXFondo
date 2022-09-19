<?php

namespace dateXFondoPlugin;

use FormulaTable;

header('Content-Type: text/javascript');

class ShortCodeDuplicateOldTemplate
{
    public static function visualize_old_template()
    {

        $fondo = 'Fondo 2022';
        $anno = 2022;
        $old_template = new DuplicateOldTemplate();
        $sections = new FormulaTable();
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
        <div class="accordion">
            <?php
            $sections_entries = $old_template->getAllSections($fondo, $anno);

            foreach ($sections_entries

            as $section) {
            ?>

            <div>
                <div class="card-header" id="headingOne<?= $section[0] ?>">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse"
                                data-target="#collapseOne<?= $section[0] ?>"
                                aria-expanded="false" aria-controls="collapseOne<?= $section[0] ?>">
                            <?= $section[0] ?>
                        </button>
                    </h5>
                </div>

                <div id="collapseOne<?= $section[0] ?>" class="collapse show"
                     aria-labelledby="headingOne<?= $section[0] ?>" data-parent="#accordion">
                    <div class="card-body">
                        <a>Sottosezione </a>
                        <div style="width: 30%" class="pt-2 pb-3">
                            <form method='POST'>
                                <?php
                                $results_subsections = $sections->getAllSubsections($section[0]);
                                arsort($results_subsections);

                                ?>

                                <select id='subsection' name='select_subsection' onchange='this.form.submit()'>
                                    <option disabled selected> Seleziona sottosezione</option>

                                    <?php foreach ($results_subsections as $res_subsection): ?>

                                        <option <?= isset($_POST['select_subsection']) && $_POST['select_subsection'] === $res_subsection[0] ? 'selected' : '' ?>

                                                value='<?= $res_subsection[0] ?>'><?= $res_subsection[0] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </div>

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


                                //TODO capire se anche per il master il template del fondo ha un titolo oppure la duplicazione avviene solo per anno
                                $old_data = $old_template->getCurrentDataBySubsections($anno, $fondo, $section[0], $_POST['select_subsection']);
                                if (empty($old_data)){
                                    ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <?php
                                }
                                else{
                                foreach ($old_data

                                as $entry) {
                                ?>
                                <div>
                                    <tr>
                                        <td style="display: none"><?php echo $entry[0]; ?></td>
                                        <td class="field_description">
                            <span data-id="<?= $entry[0] ?>">
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
                                            <input type="text" class="toggleable-input"
                                                   value='<?php echo $entry[10]; ?>'
                                                   style="display: none" data-field="valore_anno_precedente"
                                                   data-id="<?= $entry[0] ?>"
                                            /></td>
                                        <td class="field_description">
                              <span class="toggleable-span">
                                 <?php echo $entry[11]; ?>
                            </span>
                                            <input type="text" class="toggleable-input"
                                                   value='<?php echo $entry[11]; ?>'
                                                   style="display: none" data-field="nota" data-id="<?= $entry[0] ?>"
                                            /></td>
                                        <td class="field_description">
                              <span class="toggleable-span">
                                 <?php echo $entry[12]; ?>
                            </span>
                                            <input type="text" class="toggleable-input"
                                                   value='<?php echo $entry[12]; ?>'
                                                   style="display: none" data-field="link" data-id="<?= $entry[0] ?>"
                                            /></td>
                                        <td>
                                            <div class="container">
                                                <button type="button" class="btn btn-link" data-toggle="modal"
                                                        data-target="#editModal<?php echo $entry[0]; ?>">
                                                    <i class="fa-solid fa-pen"></i></button>
                                                <button type="button" class="btn btn-link" data-id="<?= $entry[0] ?>"
                                                        data-toggle="modal"
                                                        data-target="#deleteModal<?php echo $entry[0]; ?>"><i
                                                            class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>

                                        </td>
                                    </tr>
                                </div>
                                <div class="modal fade" id="editModal<?php echo $entry[0]; ?>" tabindex="-1"
                                     role="dialog"
                                     aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Modifica riga del fondo:</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <input type="text" class="form-control" id="id_riga"
                                                       value='<?php echo $entry[0]; ?>' name="id_riga" hidden>
                                                <label>Id Articolo</label>
                                                <input type="text" class="form-control" id="id_articolo"
                                                       value='<?php echo $entry[3]; ?>' name="id_articolo"
                                                       data-id="<?= $entry[0] ?>">

                                                <label>Nome Articolo</label>
                                                <input type="text" class="form-control" id="idNomeArticolo"
                                                       name="idNomeArticolo"
                                                       value='<?php echo $entry[6]; ?>'>

                                                <label>Sottotitolo Articolo</label>
                                                <textarea class="form-control"
                                                          id="idSottotitoloArticolo"
                                                          name="idSottotitoloArticolo"><?php echo $entry[8]; ?> </textarea>

                                                <label>Descrizione Articolo</label>
                                                <textarea class="form-control"
                                                          id="idDescrizioneArticolo"
                                                          name="idDescrizioneArticolo"> <?php echo $entry[7]; ?></textarea>

                                                <label>Link associato</label>
                                                <input type="text" class="form-control" id="idLinkAssociato"
                                                       name="idLinkAssociato"
                                                       value='<?php echo $entry[12]; ?>'>

                                                <div class="modal-footer">
                                                    <input type="submit" class="btn btn-primary"
                                                           onclick="changeValue()"
                                                           value="Salva modifica">
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="modal fade" id="deleteModal<?php echo $entry[0]; ?>" tabindex="-1" role="dialog"
                             aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <form method="POST">
                                            <h5 class="modal-title" id="exampleModalLabel">Sei sicuro di voler eliminare
                                                questa
                                                riga?</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    Annulla
                                                </button>
                                                <button type="button" class="btn btn-primary" onclick="disabledRow()">
                                                    Elimina
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php

        }
        }
        ?>
            </tbody>
            </table>
        </div>
        </div>
        </div>
        </div>

        <?php
        }
        ?>
        </div>


        <div class="modal fade" id="addRowModal" tabindex="-1"
             role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Nuova riga:</h5>
                        <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="selectSezione">Sezione</label>
                            <select id='section_selected' name='newRowSezione'>
                                <option disabled selected> Seleziona la sezione</option>

                                <?php foreach ($sections_entries as $section_entry): ?>

                                    <option <?= isset($_POST['section_selected']) && $_POST['section_selected'] === $section_entry[0] ? 'selected' : '' ?>

                                            value='<?= $section_entry[0] ?>'><?= $section_entry[0] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="inputSottosezione">Sottosezione</label>
                            <input type="text" class="form-control" id="newRowSottosezione"
                                   value='' name="newRowSottosezione">
                        </div>
                        <div class="form-group">
                            <label for="inputIdArticolo">Id Articolo</label>
                            <input type="text" class="form-control" id="newRowIdArticolo"
                                   value='' name="newRowIdArticolo">
                        </div>
                        <div class="form-group">
                            <label for="idNomeArticolo">Nome Articolo</label>
                            <input type="text" class="form-control" id="newRowNomeArticolo"
                                   name="newRowNomeArticolo"
                                   value=''>
                        </div>
                        <div class="form-group">
                            <label for="idSottotitoloArticolo">Sottotitolo Articolo</label>
                            <textarea class="form-control"
                                      id="newRowSottotitoloArticolo"
                                      name="newRowSottotitoloArticolo"></textarea>

                        </div>
                        <div class="form-group">
                            <label for="idDescrizioneArticolo">Descrizione Articolo</label>
                            <textarea class="form-control"
                                      id="newRowDescrizioneArticolo"
                                      name="newRowDescrizioneArticolo"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="idLinkAssociato">Link associato</label>
                            <input type="text" class="form-control" id="newRowLink"
                                   name="newRowLink"
                                   value=''>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-primary"
                                   value="Aggiungi nuova riga" onclick="addNewRow()">
                        </div>
                    </div>

                </div>
            </div>
        </div>
        </body>
        <div>
            <button class="btn btn-outline-primary pr-3" data-toggle="modal"
                    data-target="#addRowModal" style="float: right">Aggiungi nuova Riga
            </button>
            <button class="btn btn-outline-primary" style="float: right">Aggiungi decurtazione</button>
        </div>
        <form method="post">
            <div>
                <input type="submit" name="button1"
                       class="btn btn-link" value="Blocca la Modifica"/>
                <input type="submit" name="button2"
                       class="btn btn-link" value="Duplica la Tabella"/></div>
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
            $('.collapse').collapse();

            let readOnly = <?php echo $readOnly?>;
            if (!readOnly) {

                function changeValue() {
                    data = {
                        'id_riga': document.getElementById('id_riga').value,
                        'id_articolo': document.getElementById('id_articolo').value,
                        'nome_articolo': document.getElementById('idNomeArticolo').value,
                        'sottotitolo_articolo': document.getElementById('idSottotitoloArticolo').value,
                        'descrizione_articolo': document.getElementById('idDescrizioneArticolo').value,
                        'link': document.getElementById('idLinkAssociato').value
                    }
                    console.log(data)
                    $.ajax({
                        type: "POST",
                        url: "https://demo.mg3.srl/date/wp-json/datexfondoplugin/v1/table/editnewfondo",
                        data: data,
                        success: function (response) {
                            successmessage = 'Modifica eseguita correttamente';
                            console.log(response);
                        },
                        error: function (response) {
                            successmessage = 'Modifica non riuscita non riuscita';
                            console.log(response);
                        }
                    });
                }

                function addNewRow() {

                    $.ajax({
                        type: "POST",
                        url: "https://demo.mg3.srl/date/wp-json/datexfondoplugin/v1/table/newrow",
                        data: {   <?php
                            $myObj = ["fondo" => $fondo, "anno" => $anno,
                                "sezione" => $_POST['newRowSezione'],
                                "sottosezione" => $_POST['newRowSottosezione'],
                                "id_articolo" => $_POST['newRowIdArticolo'],
                                "nome_articolo" => $_POST['newRowNomeArticolo'],
                                "descrizione_articolo" => $_POST['newRowDescrizioneArticolo'],
                                "sottotitolo_articolo" => $_POST['newRowSottotitoloArticolo'],
                                "link" => $_POST['newRowLink']];
                            ?>
                            "JSONIn":<?php echo json_encode($myObj);?>},
                        success: function (response) {
                            successmessage = 'Riga creata correttamente';
                            alert(successmessage);
                            $('#addRowModal').modal('hide');
                        },
                        error: function () {
                            successmessage = 'Errore: creazione riga non riuscita';
                            alert(successmessage);
                        }
                    });

                };

                function disabledRow() {
                    // const id = $(this).parent().parent().attr("data-id");
                    // const data = {id};
                    // console.log(data)
                    // console.log($(this))
                    $.ajax({
                        type: "POST",
                        url: "https://demo.mg3.srl/date/wp-json/datexfondoplugin/v1/table/deleterow",
                        data: {   <?php
                            $myObj = ["id_riga" => $_POST['id_riga']];

                            ?>

                            "JSONIn":<?php echo json_encode($myObj);?>},
                        success: function () {

                            // location.href = "https://demo.mg3.srl/date/duplicazione-template-anno-precedente/"

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
        </html>

        <?php
    }


}