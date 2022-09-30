<?php

namespace dateXFondoPlugin;

use FormulaTable;

header('Content-Type: text/javascript');

class ShortCodeDuplicateOldTemplate
{
    public static function visualize_old_template()
    {

        $old_template = new DuplicateOldTemplate();
        $sections = new FormulaTable();
        $anno = $old_template->getAnno();
        $fondo = $old_template->getFondo($anno);
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

                .icon {
                    color: blue;
                    padding-left: 50px;
                }

                .subsectionButtonGroup1, .subsectionButtonGroup2 {
                    width: 150px;
                    border-radius: 70px;
                    font-size: 15px;
                }


            </style>
        </head>

        <body>

        <h2>TEMPLATE FONDO (MASTER)</h2>

        <div class="row pb-3">
            <div class="col-sm"><input type="text" placeholder="Inserisci nome del fondo" id="inputFondo"
                                       value='<?= $fondo ?>'></div>
            <div class="col-sm"><input type="text" placeholder="Inserisci l'anno corrente" id="inputAnno"
                                       value='<?= $anno ?>'></div>
            <div class="col-sm">
                <button class="btn btn-link"><i class="fa-solid fa-pen" id="btnEditHeader" onclick="editHeader()"></i>
                </button>
            </div>
            <div class="col-sm">
                <button class="btn btn-link"><i class="fa-solid fa-floppy-disk" id="btnSaveHeader"
                                                onclick="saveHeader()" style="display:none"></i></i></button>
            </div>
        </div>

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
                                    <th>Ordinamento</th>
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

                                        <td class="field_description ordinamento">
                            <span>
                                <?php echo $entry[3]; ?>
                            </span>
                                        </td>
                                        <td class="field_description id_articolo">
                            <span data-id="<?= $entry[0] ?>">
                                <?php echo $entry[4]; ?>
                            </span>
                                        </td>
                                        <td class="field_description nome_articolo">
                            <span>
                                <?php echo $entry[7]; ?>
                            </span>
                                        </td>
                                        <td class="field_description sottotitolo_articolo">
                            <span>
                                <?php echo $entry[8]; ?>
                            </span>
                                        </td>
                                        <td class="field_description descrizione_articolo">
                             <span>
                                <?php echo $entry[9]; ?>
                            </span>
                                        </td>
                                        <td class="field_description valore">
                            <span>
                                <?php echo $entry[10]; ?>
                            </span>
                                        </td>
                                        <td class="field_description valore_anno_precedente">
                            <span>
                                <?php echo $entry[11]; ?>
                            </span>
                                        </td>
                                        <td class="field_description">
                              <span class="toggleable-span">
                                 <?php echo $entry[12]; ?>
                            </span>
                                            <input type="text" class="toggleable-input"
                                                   value='<?php echo $entry[12]; ?>'
                                                   style="display: none" data-field="nota" data-id="<?= $entry[0] ?>"
                                            /></td>
                                        <td class="field_description">
                              <span class="toggleable-span">
                                 <?php echo $entry[13]; ?>
                            </span>
                                            <input type="text" class="toggleable-input"
                                                   value='<?php echo $entry[13]; ?>'
                                                   style="display: none" data-field="link" data-id="<?= $entry[0] ?>"
                                            /></td>
                                        <td>
                                            <div class="container">
                                                <button id="editRow" type="button" class="btn btn-link"
                                                        data-toggle="modal"
                                                        data-target="#editModal<?php echo $entry[0]; ?>">
                                                    <i class="fa-solid fa-pen"></i></button>
                                                <button id="deleteRow" type="button" class="btn btn-link"
                                                        data-id="<?= $entry[0] ?>"
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
                                                <?php if ($entry[16] === 'decurtazione') { ?>
                                                    <input type="text" class="form-control" id="id_riga"
                                                           value='<?php echo $entry[0]; ?>' name="id_riga" hidden>
                                                    <label>Ordinamento</label>
                                                    <input type="text" class="form-control" id="ordinamento"
                                                           value='<?php echo $entry[3]; ?>' name="ordinamento"
                                                           data-id="<?= $entry[0] ?>">
                                                    <label>Nome Decurtazione</label>
                                                    <input type="text" class="form-control" id="idNomeArticolo"
                                                           name="idNomeArticolo"
                                                           value='<?php echo $entry[7]; ?>'>
                                                    <label>Descrizione</label>
                                                    <textarea class="form-control"
                                                              id="idDescrizioneArticolo"
                                                              name="idDescrizioneArticolo"> <?php echo $entry[8]; ?></textarea>
                                                    <label>Nota</label>
                                                    <textarea class="form-control"
                                                              id="idNotaArticolo"
                                                              name="idNotaArticolo"> <?php echo $entry[12]; ?></textarea>
                                                    <label for="inputNota">Tipologia decurtazione: </label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="typeDecEdit"
                                                               id="percentualeSelected"
                                                               value="%" checked='<?php echo $entry[13]; ?>'>
                                                        <label class="form-check-label" for="percentualeSelected">
                                                            %
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="typeDecEdit"
                                                               id="valAbsSelected"
                                                               value="ValoreAssoluto">
                                                        <label class="form-check-label" for="valAbsSelected">
                                                            Valore Assoluto
                                                        </label>
                                                    </div>
                                                <?php } else { ?>
                                                    <input type="text" class="form-control" id="id_riga"
                                                           value='<?php echo $entry[0]; ?>' name="id_riga" hidden>
                                                    <label>Ordinamento</label>
                                                    <input type="text" class="form-control" id="ordinamento"
                                                           value='<?php echo $entry[3]; ?>' name="ordinamento"
                                                           data-id="<?= $entry[0] ?>">
                                                    <label>Id Articolo</label>
                                                    <input type="text" class="form-control" id="id_articolo"
                                                           value='<?php echo $entry[4]; ?>' name="id_articolo"
                                                           data-id="<?= $entry[0] ?>">

                                                    <label>Nome Articolo</label>
                                                    <input type="text" class="form-control" id="idNomeArticolo"
                                                           name="idNomeArticolo"
                                                           value='<?php echo $entry[7]; ?>'>

                                                    <label>Sottotitolo Articolo</label>
                                                    <textarea class="form-control"
                                                              id="idSottotitoloArticolo"
                                                              name="idSottotitoloArticolo"><?php echo $entry[9]; ?> </textarea>

                                                    <label>Descrizione Articolo</label>
                                                    <textarea class="form-control"
                                                              id="idDescrizioneArticolo"
                                                              name="idDescrizioneArticolo"> <?php echo $entry[8]; ?></textarea>

                                                    <label>Nota</label>
                                                    <textarea class="form-control"
                                                              id="idNotaArticolo"
                                                              name="idNotaArticolo"> <?php echo $entry[12]; ?></textarea>
                                                    <label>Link associato</label>
                                                    <input type="text" class="form-control" id="idLinkAssociato"
                                                           name="idLinkAssociato"
                                                           value='<?php echo $entry[13]; ?>'>
                                                <?php } ?>
                                            </div>
                                            <div class="modal-footer">
                                                <input type="submit" class="btn btn-primary"
                                                       onclick="editRow()"
                                                       value="Salva modifica">
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
                                        <input type="text" class="form-control" id="id_riga"
                                               value='<?php echo $entry[0]; ?>' name="id_riga" hidden>
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
                                            <button class="btn btn-primary" onclick="disabledRow()">
                                                Elimina
                                            </button>
                                        </div>
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

        <!--Inizio modale per aggiungere una nuova riga basic-->
        <div class="modal fade" id="addRowModal" tabindex="-1"
             role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><b>Nuova riga:</b></h5>
                        <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="selectSezione"><b>Sezione:</b></label>

                            <select id='newRowSezione' name='newRowSezione'>
                                <option disabled selected> Seleziona la sezione</option>

                                <?php foreach ($sections_entries as $section_entry): ?>

                                    <option <?= isset($_POST['section_selected']) && $_POST['section_selected'] === $section_entry[0] ? 'selected' : '' ?>

                                            value='<?= $section_entry[0] ?>'><?= $section_entry[0] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <br>
                            <div class="btn-group pb-3" role="group" aria-label="Basic example">
                                <button type="button" class="btn  btn-outline-primary subsectionButtonGroup1"
                                        onclick="changeToSelectSubsection()">Seleziona Sezione
                                </button>
                                <button type="button" class="btn btn-outline-primary subsectionButtonGroup2"
                                        onclick="showNewRowSubsectionInput()">Nuova sezione
                                </button>
                            </div>

                            <select id='newRowSelectSottosezione' name='newRowSelectSottosezione'>
                                <option disabled selected> Seleziona la sottosezione</option>

                                <?php foreach ($results_subsections as $subsection_entry): ?>

                                    <option <?= isset($_POST['subsection_selected']) && $_POST['subsection_selected'] === $subsection_entry[0] ? 'selected' : '' ?>

                                            value='<?= $subsection_entry[0] ?>'><?= $subsection_entry[0] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group" id="divNewRowSottosezione" hidden>
                            <input type="text" class="form-control" id="newRowSottosezione"
                                   value='' name="newRowSottosezione">
                        </div>
                        <div class="form-group">
                            <label for="ordinamento"><b>Ordinamento:</b></label>
                            <input type="text" class="form-control" id="newRowOrdinamento"
                                   value='' name="newRowIdOrdinamento">
                        </div>
                        <div class="form-group">
                            <label for="inputIdArticolo"><b>Id Articolo:</b></label>
                            <input type="text" class="form-control" id="newRowIdArticolo"
                                   value='' name="newRowIdArticolo">
                        </div>
                        <div class="form-group">
                            <label for="idNomeArticolo"><b>Articolo:</b> </label>
                            <input type="text" class="form-control" id="newRowNomeArticolo"
                                   name="newRowNomeArticolo"
                                   value=''>
                        </div>
                        <div class="form-group">
                            <label for="idSottotitoloArticolo"><b>Sottotitolo Articolo: </b></label>
                            <textarea class="form-control"
                                      id="newRowSottotitoloArticolo"
                                      name="newRowSottotitoloArticolo"></textarea>

                        </div>
                        <div class="form-group">
                            <label for="idDescrizioneArticolo"><b>Descrizione Articolo: </b></label>
                            <textarea class="form-control"
                                      id="newRowDescrizioneArticolo"
                                      name="newRowDescrizioneArticolo"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="idNota"><b>Nota</b></label>
                            <textarea class="form-control"
                                      id="newRowNota"
                                      name="newRowNota"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="idLinkAssociato"><b>Link associato: </b></label>
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
        </div>      <!--Fine modale per aggiungere una nuova riga basic-->
        </body>
        <div>
            <!--Button per aggiungere una nuova riga basic-->
            <button id="btnAddRow" class="btn btn-outline-primary" data-toggle="modal"
                    data-target="#addRowModal" style="float: right">Aggiungi nuova Riga
            </button>
        </div>
        <div class="pl-3">
            <!--Button per aggiungere una nuova riga di decurtazione-->
            <button id="btnDecurtazione" data-toggle="modal"
                    data-target="#addRowDecModal" class="btn btn-outline-primary " style="float: right">Aggiungi
                decurtazione
            </button>
        </div>
        <div class="pl-3">
            <!--Button per aggiungere una nuova riga di decurtazione speciale-->
            <button id="btnAddSpecialDec" data-toggle="modal"
                    data-target="#addRowSpecialDecModal" class="btn btn-outline-primary " style="float: right">Aggiungi
                decurtazione speciale
            </button>
        </div>
        <!--Inizio modale per aggiunta di una nuova riga di decurtazione-->
        <div class="modal fade" id="addRowDecModal" tabindex="-1"
             role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><b>Nuova decurtazione:</b></h5>
                        <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="selectSezione"><b>Sezione: </b></label>
                            <select id='decSezione' name='decSezione'>
                                <option disabled selected> Seleziona la sezione</option>

                                <?php foreach ($sections_entries as $dec_entry): ?>

                                    <option value='<?= $dec_entry[0] ?>'><?= $dec_entry[0] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group" id="divSelectSottosezione">
                            <br>
                            <div class="btn-group pb-3" role="group" aria-label="Basic example">
                                <button type="button" class="btn  btn-outline-primary subsectionButtonGroup1"
                                        onclick="changeToSelectSubsectionDec()">Seleziona Sezione
                                </button>
                                <button type="button" class="btn btn-outline-primary subsectionButtonGroup2"
                                        onclick="showNewSubsectionInput()">Nuova sezione
                                </button>
                            </div>
                            <select id='decSottosezione' name='decSottosezione'>
                                <option disabled selected> Seleziona la sottosezione</option>
                                <?php
                                foreach ($results_subsections as $dec_subsection): ?>

                                    <option value='<?= $dec_subsection[0] ?>'><?= $dec_subsection[0] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group" id="divNewSottosezione" hidden>
                            <input type="text" class="form-control" id="decNewSottosezione"
                                   value='' name="decNewSottosezione">
                        </div>
                        <div class="form-group">
                            <label for="ordinamento"><b>Ordinamento: </b></label>
                            <input type="text" class="form-control" id="decOrdinamento"
                                   value='' name="decOrdinamento">
                        </div>
                        <label for="inputNota"><b>Tipologia decurtazione:</b> </label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="typeDec" id="percentualeSelected"
                                   value="%">
                            <label class="form-check-label" for="percentualeSelected">
                                %
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="typeDec" id="valAbsSelected"
                                   value="ValoreAssoluto">
                            <label class="form-check-label" for="valAbsSelected">
                                Valore Assoluto
                            </label>
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="decDescrizioneArticolo"><b>Descrizione:</b></label>
                            <textarea class="form-control"
                                      id="decDescrizioneArticolo"
                                      name="decDescrizioneArticolo"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="decNota"><b>Nota:</b></label>
                            <textarea class="form-control"
                                      id="decNota"
                                      name="decNota"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary"
                               value="Aggiungi nuova riga" onclick="addNewRowDecurtazione()">
                    </div>
                </div>
            </div>    <!--Fine modale per aggiunta di una nuova riga di decurtazione-->
        </div>
        <!--Inizio modale per aggiungere una nuova riga decurtazione speciale-->
        <div class="modal fade" id="addRowSpecialDecModal" tabindex="-1"
             role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><b>Nuova riga di decurtazione speciale:</b></h5>
                        <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="selectSezione"><b>Sezione: </b></label>
                            <select id='decSpecialSezione' name='decSpecialSezione'>
                                <option disabled selected> Seleziona la sezione</option>

                                <?php foreach ($sections_entries as $dec_entry): ?>

                                    <option value='<?= $dec_entry[0] ?>'><?= $dec_entry[0] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group" id="divSelectSpecialSottosezione">
                            <br>
                            <div class="btn-group pb-3" role="group" aria-label="Basic example">
                                <button type="button" class="btn  btn-outline-primary subsectionSpecialButtonGroup1"
                                        onclick="changeToSelectSpecialSubsectionDec()">Seleziona Sezione
                                </button>
                                <button type="button" class="btn btn-outline-primary subsectionSpecialButtonGroup2"
                                        onclick="showNewSpecialSubsectionInput()">Nuova sezione
                                </button>
                            </div>
                            <select id='decSpecialSottosezione' name='decSpecialSottosezione'>
                                <option disabled selected> Seleziona la sottosezione</option>
                                <?php
                                foreach ($results_subsections as $dec_subsection): ?>

                                    <option value='<?= $dec_subsection[0] ?>'><?= $dec_subsection[0] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group" id="divNewSpecialSottosezione" hidden>
                            <input type="text" class="form-control" id="decNewSpecialSottosezione"
                                   value='' name="decNewSpecialSottosezione">
                        </div>
                        <div class="form-group">
                            <label for="ordinamento"><b>Ordinamento: </b></label>
                            <input type="text" class="form-control" id="decSpecialOrdinamento"
                                   value='' name="decSpecialOrdinamento">
                        </div>
                        <div class="form-group">
                            <label for="decSpecialIdArticolo"><b>id Decurtazione: </b></label>
                            <input type="text" class="form-control" id="decSpecialIdArticolo"
                                   value='' name="decSpecialIdArticolo">
                        </div>

                        <label for="inputLink"><b>Tipologia decurtazione:</b> </label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="typeSpecialDec" id="specialPercentualeSelected"
                                   value="%">
                            <label class="form-check-label" for="specialPercentualeSelected">
                                %
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="typeSpecialDec" id="specialValAbsSelected"
                                   value="ValoreAssoluto">
                            <label class="form-check-label" for="specialValAbsSelected">
                                Valore Assoluto
                            </label>
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="decSpecialDescrizioneArticolo"><b>Descrizione:</b></label>
                            <textarea class="form-control"
                                      id="decSpecialDescrizioneArticolo"
                                      name="decDescrizioneArticolo"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="decSpecialNota"><b>Nota:</b></label>
                            <textarea class="form-control"
                                      id="decSpecialNota"
                                      name="decSpecialNota"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary"
                               value="Aggiungi nuova riga" onclick="addNewRowSpecialDecurtazione()">
                    </div>
                </div>

            </div>
        </div>
        </div>
        <form method="post">
            <div>
                <i class="icon fa-solid fa-ban"></i>
                <input type="submit" name="button1"
                       class="btn btn-link" value="Blocca la Modifica"/>
                <i class=" icon fa-regular fa-copy"></i>
                <input type="submit" name="button2"
                       class="btn btn-link" value="Duplica la Tabella"/>

            </div>
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
                //TODO provare ad implementare una funzione unica dato che fanno la stessa cosa
                function changeToSelectSubsection() {
                    document.getElementById('newRowSelectSottosezione').removeAttribute("hidden");
                    const newRowSubsection = document.getElementById('newRowSelectSottosezione');
                    const newRowSubsectionHidden = document.getElementById('divNewRowSottosezione');
                    newRowSubsection.setAttribute('style', 'display:block');
                    newRowSubsectionHidden.setAttribute('style', 'display:none');
                }

                function changeToSelectSubsectionDec() {
                    document.getElementById('decSottosezione').removeAttribute("hidden");
                    const newRowSubsection = document.getElementById('decSottosezione');
                    const newRowSubsectionHidden = document.getElementById('divNewSottosezione');
                    newRowSubsection.setAttribute('style', 'display:block');
                    newRowSubsectionHidden.setAttribute('style', 'display:none');
                }

                function showNewSubsectionInput() {
                    document.getElementById('divNewSottosezione').removeAttribute("hidden");
                    const decSubsection = document.getElementById('divNewSottosezione');
                    const decSubsectionHidden = document.getElementById('decSottosezione');
                    decSubsection.setAttribute('style', 'display:block');
                    decSubsectionHidden.setAttribute('style', 'display:none');
                }

                function showNewRowSubsectionInput() {
                    document.getElementById('divNewRowSottosezione').removeAttribute("hidden");
                    const newRowSubsection = document.getElementById('divNewRowSottosezione');
                    const newRowSubsectionHidden = document.getElementById('newRowSelectSottosezione');
                    newRowSubsection.setAttribute('style', 'display:block');
                    newRowSubsectionHidden.setAttribute('style', 'display:none');
                }

                function editRow() {
                    let id_articolo = document.getElementById('id_articolo');
                    if (typeof id_articolo !== 'undefined' && id_articolo !== null) {
                        id_articolo.hinnerHTML = value;
                    }
                    else {
                        id_articolo = '';
                    }
                    let sottotitolo_articolo = document.getElementById('idSottotitoloArticolo');
                    if (typeof sottotitolo_articolo !== 'undefined' && sottotitolo_articolo !== null) {
                        sottotitolo_articolo.hinnerHTML = value;
                    }
                    let link = document.getElementById('idLinkAssociato');
                    if (typeof link != 'undefined' && link != null) {
                        link.hinnerHTML = value;

                    } else {
                        link = document.querySelector('input[name="typeDecEdit"]:checked').value;

                    }
                    data = {
                        'ordinamento': document.getElementById('ordinamento').value,
                        'id_riga': document.getElementById('id_riga').value,
                        'id_articolo': id_articolo,
                        'nome_articolo': document.getElementById('idNomeArticolo').value,
                        'sottotitolo_articolo': sottotitolo_articolo,
                        'descrizione_articolo': document.getElementById('idDescrizioneArticolo').value,
                        'nota': document.getElementById('idNotaArticolo').value,
                        'link': link
                    }
                    console.log(data)
                    $.ajax({
                        type: "POST",
                        //url: "https://demo.mg3.srl/date/wp-json/datexfondoplugin/v1/table/editnewfondo",
                        data: data,
                        success: function (response) {
                            successmessage = 'Modifica eseguita correttamente';
                           // location.href = "https://demo.mg3.srl/date/duplicazione-template-anno-precedente/";

                        },
                        error: function (response) {
                            successmessage = 'Modifica non riuscita non riuscita';
                            console.log(response);
                        }
                    });
                }

                function addNewRow() {

                    data = {
                        'ordinamento': document.getElementById('newRowOrdinamento').value,
                        'sezione': document.getElementById('newRowSezione').value,
                        'sottosezione': document.getElementById('newRowSottosezione').value,
                        'id_articolo': document.getElementById('newRowIdArticolo').value,
                        'nome_articolo': document.getElementById('newRowNomeArticolo').value,
                        'sottotitolo_articolo': document.getElementById('newRowSottotitoloArticolo').value,
                        'descrizione_articolo': document.getElementById('newRowDescrizioneArticolo').value,
                        'link': document.getElementById('newRowLink').value
                    }
                    console.log('Basic row')
                    console.log(data)
                    $.ajax({
                        type: "POST",
                        url: "https://demo.mg3.srl/date/wp-json/datexfondoplugin/v1/table/newrow",
                        data: data,
                        success: function (response) {
                            successmessage = 'Riga creata correttamente';
                            alert(successmessage);
                            location.href = "https://demo.mg3.srl/date/duplicazione-template-anno-precedente/"
                        },
                        error: function () {
                            successmessage = 'Errore: creazione riga non riuscita';
                            alert(successmessage);
                        }
                    });

                }

                //TODO fare funzione post per salvare la sezione selezionata. La get mi restituisce tutte le sottosezioni collegate

                // function getAllSubsection() {
                //     data = {
                //         'sezione': document.getElementById('decSezione').value
                //     }
                //
                //     $.ajax({
                //         type: "POST",
                //         url: "https://demo.mg3.srl/date/wp-json/datexfondoplugin/v1/table/newrowdec",
                //         data: data,
                //         success: function (response) {
                //
                //         },
                //         error: function () {
                //         }
                //     });
                //
                // }

                function editHeader() {
                    let editBtn = document.getElementById('btnEditHeader');
                    editBtn.setAttribute('style', 'display:none');
                    let saveBtn = document.getElementById('btnSaveHeader');
                    saveBtn.setAttribute('style', 'display:block');
                    //   document.getElementById('inputFondo').removeAttribute("readonly");
                    //   document.getElementById('inputAnno').removeAttribute("readonly");

                }

                function saveHeader() {
                    let editBtn = document.getElementById('btnEditHeader');
                    editBtn.setAttribute('style', 'display:block');
                    let saveBtn = document.getElementById('btnSaveHeader');
                    saveBtn.setAttribute('style', 'display:none');
                    data = {
                        'fondo': document.getElementById('inputFondo').value,
                        'anno': document.getElementById('inputAnno').value
                    }
                    $.ajax({
                        type: "POST",
                        url: "https://demo.mg3.srl/date/wp-json/datexfondoplugin/v1/table/editfondoanno",
                        data: data,
                        success: function (response) {
                            successmessage = 'Modifica effettuata correttamente';
                            alert(successmessage);
                            location.href = "https://demo.mg3.srl/date/duplicazione-template-anno-precedente/"
                        },
                        error: function () {
                            successmessage = 'Errore: modifica non riuscita';
                            alert(successmessage);
                        }
                    });
                }

                function addNewRowDecurtazione() {
                    data = {
                        'ordinamento': document.getElementById('decOrdinamento').value,
                        'sezione': document.getElementById('decSezione').value,
                        'sottosezione': document.getElementById('decSottosezione').value,
                        'sottosezione_nuova': document.getElementById('divNewSottosezione').value,
                        'descrizione': document.getElementById('decDescrizioneArticolo').value,
                        'nota': document.getElementById('decNota').value,
                        'link': document.querySelector('input[name="typeDec"]:checked').value,
                        'row_type' : 'decurtazione'
                    }
                    console.log('Riga decurtazione')
                    console.log(data)
                    $.ajax({
                        type: "POST",
                        url: "https://demo.mg3.srl/date/wp-json/datexfondoplugin/v1/table/newrowdec",
                        data: data,
                        success: function (response) {
                            successmessage = 'Riga creata correttamente';
                            alert(successmessage);
                            location.href = "https://demo.mg3.srl/date/duplicazione-template-anno-precedente/"
                        },
                        error: function () {
                            successmessage = 'Errore: creazione riga non riuscita';
                            alert(successmessage);
                        }
                    });

                }
                function addNewRowSpecialDecurtazione() {
                    data = {
                        'id_articolo' : document.getElementById('decSpecialIdArticolo').value,
                        'ordinamento': document.getElementById('decSpecialOrdinamento').value,
                        'sezione': document.getElementById('decSpecialSezione').value,
                        'sottosezione': document.getElementById('decSpecialSottosezione').value,
                        'sottosezione_nuova': document.getElementById('divNewSpecialSottosezione').value,
                        'descrizione': document.getElementById('decSpecialDescrizioneArticolo').value,
                        'nota': document.getElementById('decSpecialNota').value,
                        'link': document.querySelector('input[name="typeSpecialDec"]:checked').value,
                        'row_type' : 'special'

                    }
                    console.log('Special')
                    console.log(data)
                    $.ajax({
                        type: "POST",
                        url: "https://demo.mg3.srl/date/wp-json/datexfondoplugin/v1/table/newrowspdec",
                        data: data,
                        success: function (response) {
                            successmessage = 'Riga decurtazione speciale creata correttamente';
                            alert(successmessage);
                            location.href = "https://demo.mg3.srl/date/duplicazione-template-anno-precedente/"
                        },
                        error: function () {
                            successmessage = 'Errore: creazione riga decurtazione speciale non riuscita';
                            alert(successmessage);
                        }
                    });

                }

                function disabledRow() {
                    $.ajax({
                        type: "POST",
                        url: "https://demo.mg3.srl/date/wp-json/datexfondoplugin/v1/table/deleterow",
                        data: {'id_riga': document.getElementById('id_riga').value},
                        success: function () {
                            location.href = "https://demo.mg3.srl/date/duplicazione-template-anno-precedente/"

                        },
                        error: function () {

                        }
                    })
                    ;
                }
            } else {
                document.getElementById("btnAddRow").disabled = true;
                document.getElementById("btnDecurtazione").disabled = true;
                document.getElementById("deleteRow").disabled = true;
                document.getElementById("editRow").disabled = true;
            }


        </script>
        </html>

        <?php
    }


}