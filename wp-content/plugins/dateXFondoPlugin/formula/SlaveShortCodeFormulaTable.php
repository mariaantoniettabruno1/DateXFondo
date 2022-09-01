<?php

namespace dateXFondoPlugin;

use SlaveFormulaTable;
use FormulaTable;
use GFAPI;
use Mpdf\Form;

header('Content-Type: text/javascript');

class SlaveShortCodeFormulaTable
{
    public static function visualize_slave_formula_template()
    {

        ?>


        <!DOCTYPE html>

        <html lang="en">

        <head>
            <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"
                  integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A=="
                  crossorigin="anonymous" referrerpolicy="no-referrer"/>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
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
        <div>
            <form method='POST'>

                <h4>Seleziona Sezione</h4>

                <?php

                $sections = new FormulaTable();
                $results_sections = $sections->getAllSections();
                arsort($results_sections);

                ?>

                <select id='section' name='select_section' onchange='this.form.submit()'>
                    <option disabled selected> Seleziona Sezione</option>

                    <?php foreach ($results_sections as $res_section): ?>

                        <option <?= isset($_POST['select_section']) && $_POST['select_section'] === $res_section[0] ? 'selected' : '' ?>

                                value='<?= $res_section[0] ?>'><?= $res_section[0] ?></option>

                    <?php endforeach; ?>
                </select>
            </form>
        </div>
        <br>
        <div>
            <h2>TABELLA FORMULE</h2>
            <div class="table-responsive">

                <table id="dataTable">
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
                    $formulaData = new SlaveFormulaTable();
                    $old_template = new DuplicateOldTemplate();
                    $data = new FormulaTable();

                    if (isset($_POST['select_section'])) {
                        $selected_section = $_POST['select_section'];

                        $entries = $data->getAllEntriesFromSection($selected_section);
                        $formula = $formulaData->getFormulaBySelectedSection($selected_section);

                        $fondo = $entries[0][1];
                        $ente = $entries[0][2];
                        $anno = $entries[0][3];

                        /** For table pagination **/
                        $limit = 5;
                        $page = get_query_var('index', 1);
                        $startRecord = ($page - 1) * $limit;
                        $recordsCount = $old_template->getCurrentDataCount($ente, $anno, $fondo);
                        $totalPages = ceil($recordsCount / $limit);
                        $previous = $page - 1;
                        $next = $page + 1;


                        foreach ($entries as $entry) {
                            unset($entry[0]);
                            unset($entry[13]);

                            setcookie("Fondo", $fondo);
                            setcookie("Ente", $ente);
                            setcookie("Anno", $anno);
                            setcookie("Sezione", $selected_section);
                            ?>
                            <tr>
                                <td style="display: none"><?php echo $entry[0]; ?></td>
                                <td>  <span class="toggleable-span">
                                <?php echo $fondo; ?>
                            </span>
                                    <input type="text" class="toggleable-input" value='<?php echo $fondo; ?>'
                                           style="display: none" data-field="fondo" data-id="<?= $entry[0] ?>"
                                    /></td>
                                <td class="field_description">
                             <span class="toggleable-span">
                                <?php echo $ente; ?>
                            </span>
                                    <input type="text" class="toggleable-input" value='<?php echo $ente; ?>'
                                           style="display: none" data-field="ente" data-id="<?= $entry[0] ?>"
                                    /></td>
                                <td class="field_description"
                                <span class="toggleable-span">
                                <?php echo $anno; ?>
                            </span>
                                <input type="text" class="toggleable-input" value='<?php echo $anno; ?>'
                                       style="display: none" data-field="anno" data-id="<?= $entry[0] ?>"
                                /></td>

                                <td class="field_description">
                                    <div data-field="id_campo" data-id="<?= $entry[0] ?>">
                                        <label><input type="text" name="id_campo"
                                                      value='<?php echo $entry[4]; ?>'
                                                      onclick="addNumberToFormula()" hidden> <?php echo $entry[4]; ?>
                                        </label>
                                    </div>
                                </td>
                                <td class="field_section">
                            <span class="toggleable-span">
                                <?php echo $entry[5]; ?>
                            </span>
                                    <input type="text" class="toggleable-input" value='<?php echo $entry[5]; ?>'
                                           style="display: none" data-field="sezione" data-id="<?= $entry[0] ?>"
                                    />
                                </td>
                                <td class="field_description">
                            <span class="toggleable-span">
                                <?php echo $entry[6]; ?>
                            </span>
                                    <input type="text" class="toggleable-input" value='<?php echo $entry[6]; ?>'
                                           style="display: none" data-field="label_campo" data-id="<?= $entry[0] ?>"
                                    /></td>
                                <td class="field_description">
                            <span class="toggleable-span">
                                <?php echo $entry[7]; ?>
                            </span>
                                    <input type="text" class="toggleable-input" value='<?php echo $entry[7]; ?>'
                                           style="display: none" data-field="descrizione_campo"
                                           data-id="<?= $entry[0] ?>"
                                    /></td>
                                <td class="field_description">
                             <span class="toggleable-span">
                                <?php echo $entry[8]; ?>
                            </span>
                                    <input type="text" class="toggleable-input" value='<?php echo $entry[8]; ?>'
                                           style="display: none" data-field="sottotitolo_campo"
                                           data-id="<?= $entry[0] ?>"
                                    /></td>
                                <td class="field_description">   <span class="toggleable-span">
                                <?php echo $entry[9]; ?>
                            </span>
                                    <input type="text" class="toggleable-input" value='<?php echo $entry[9]; ?>'
                                           style="display: none" data-field="valore"
                                           data-id="<?= $entry[0] ?>"
                                    /></td>
                                <td class="field_description">   <span class="toggleable-span">
                                <?php echo $entry[10]; ?>
                            </span>
                                    <input type="text" class="toggleable-input" value='<?php echo $entry[10]; ?>'
                                           style="display: none" data-field="valore_anno_precedente"
                                           data-id="<?= $entry[0] ?>"
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
                                 <?php echo $entry[12] == 1 ? "Attivo" : "Non attivo" ?>
                            </span>
                                    <input type="text" class="toggleable-input" value='<?php echo $entry[12]; ?>'
                                           style="display: none" data-field="attivo" data-id="<?= $entry[0] ?>"
                                    /></td>
                            </tr>

                            <?php

                        }
                        ?>
                        <tr class="table-active">
                            <td>Calcolo totale sezione</td>
                            <td><p><b>Formula : </b></p> <?php print_r($formula[0][2]) ?></td>
                            <?php
                            $array_formula_character = str_split($formula[0][2]);
                            $counter = 0;
                            $id_campo = '';
                            $temp_value = '';

                            foreach ($array_formula_character as $character) {
                                if ($character == '+' || $character == '-' || $character == '*' || $character == '/' || $character == '(' || $character == ')') {
                                    $temp_value .= $formulaData->getValueFromIdCampo($id_campo)['valore'];
                                    $temp_value .= $character;
                                    $id_campo = '';
                                    $counter++;
                                } else {
                                    $id_campo .= $character;
                                    $counter++;
                                    if ($counter == sizeof($array_formula_character)) {
                                        $temp_value .= $formulaData->getValueFromIdCampo($id_campo)['valore'];
                                        $id_campo = '';
                                    }
                                }

                            }
                            $total = "print (" . $temp_value . ");";
                            $total = number_format(eval($total), 2, ',','.');

                            // $formulaData->saveTotal($total, $formula[0][2], $selected_section, $fondo, $ente, $anno);

                            ?>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><p><b>Totale sezione:</b></p></td>
                            <td><?php print_r($total); ?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>

                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-end">
                        <li class="page-item <?php if ($page == 1) echo 'disabled'; ?>">
                            <a class="page-link" href="?index=1" tabindex="-1" aria-disabled="true">1</a>
                        <li class="page-item <?php if ($page <= 1) {
                            echo 'disabled';
                        } ?>"><a class="page-link" href="?index=<?php echo $previous; ?>"">Precedente</a></li>
                        <li class="page-item"><input id="currentPageInput" type="number" min="1"
                                                     max="<?php echo $totalPages ?>"
                                                     placeholder="<?php echo $page; ?>" required></li>
                        <li class="page-item <?php if ($page >= $totalPages) {
                            echo 'disabled';
                        } ?>"><a class="page-link" href="?index=<?php echo $next; ?>">Successivo</a></li>
                        <li class="page-item <?php if ($page >= $totalPages) {
                            echo 'disabled';
                        } ?>"><a class="page-link"
                                 href="?index=<?php echo $totalPages; ?>"><?php echo $totalPages; ?></a>
                        </li>
                    </ul>
                </nav>


            </div>
        </body>
        </div>

        </html lang="en">

        <?php


    }
}