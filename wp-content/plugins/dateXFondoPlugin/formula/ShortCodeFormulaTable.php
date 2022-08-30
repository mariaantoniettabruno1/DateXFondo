<?php

namespace dateXFondoPlugin;

use FormulaTable;
use GFAPI;
use Mpdf\Form;

header('Content-Type: text/javascript');

class ShortCodeFormulaTable
{
    public static function visualize_formula_template()
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
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Calcolo delle formule</h5>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-3">
                            <select class="form-select" id="selectOperator">
                                <option disabled selected>Seleziona l'operazione</option>
                                <option value="+">+</option>
                                <option value="-">-</option>
                                <option value="*">*</option>
                                <option value="\">\</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select class="form-select" id="parenthesisOperator">
                                <option disabled selected>Seleziona la parentesi</option>
                                <option value="(">(</option>
                                <option value=")">)</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Percentuale"
                                       aria-label="Percentuale" aria-describedby="basic-addon1" id="percentageInput">
                                <div class="input-group-prepend">
                                    <span class="input-group-text pr-3" id="basic-addon1">%</span>
                                </div>
                                <button type="button" class="btn btn-outline-info pl-3" style="float: right"
                                        onclick="calculatePercentage()">Calcola
                                </button>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-warning" style="float: right"
                                    onclick="deleteLastCharacter()"><i
                                        class="fa-solid fa-arrow-rotate-left text-white"></i></button>
                            <button type="button" class="btn btn-danger" style="float: right"
                                    onclick="deleteExpression()"><i
                                        class="fa-solid fa-trash"></i></button>
                        </div>
                    </div>
                </div>
                <div id="divValFormula"></div>
                <form method='POST'>
                    <input type="hidden" name="formula" value="formula" id="formula">
                    <input type="submit" class="btn btn-info" style="float: right" onclick="calculateFormula()"
                           value="Calcola formula">
                </form>
            </div>
        </div>
        </div>
        <br>
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
        <h2>TABELLA FORMULE</h2>
        <div class="table-responsive table-hover">

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

                $old_template = new DuplicateOldTemplate();
                $limit = 5;
                $page = get_query_var('index', 1);
                $startRecord = ($page - 1) * $limit;
                $recordsCount = $old_template->getCurrentDataCount($ente, $anno, $fondo);
                $totalPages = ceil($recordsCount / $limit);
                $previous = $page - 1;
                $next = $page + 1;

                if (isset($_POST['select_section'])) {
                    $data = new FormulaTable();
                    $selected_section = $_POST['select_section'];


                    $entries = $data->getAllEntriesFromSection($selected_section);

                    foreach ($entries as $entry) {
                        unset($entry[0]);
                        unset($entry[13]);
                        $fondo = $entry[1];
                        $ente = $entry[2];
                        $anno = $entry[3];
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
                                       style="display: none" data-field="descrizione_campo" data-id="<?= $entry[0] ?>"
                                /></td>
                            <td class="field_description">
                             <span class="toggleable-span">
                                <?php echo $entry[8]; ?>
                            </span>
                                <input type="text" class="toggleable-input" value='<?php echo $entry[8]; ?>'
                                       style="display: none" data-field="sottotitolo_campo" data-id="<?= $entry[0] ?>"
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

        <script>
            let formula = '';
            let operator = '';
            let parenthesis = '';
            let result = 0;
            let number = 0;
            let resultPercentage = 0;

            const myHeaders = new Headers();

            $(document).ready(function () {
                myHeaders.append('Cache-Control', 'no-store');
            });

            function addNumberToFormula() {
                number = event.target.value;
                formula = formula.concat(number.toString());
                document.getElementById("divValFormula").innerHTML = formula;
            }

            $("#selectOperator").change(function () {
                var select = document.getElementById('selectOperator');
                operator = select.options[select.selectedIndex].value;
                formula = formula.concat(operator.toString())
                document.getElementById("divValFormula").innerHTML = formula;
            });
            $("#parenthesisOperator").change(function () {
                var select = document.getElementById('parenthesisOperator');
                var parenthesis = select.options[select.selectedIndex].value;
                formula = formula.concat(parenthesis.toString())
                document.getElementById("divValFormula").innerHTML = formula;
            });

            function deleteExpression() {
                formula = '';
                document.getElementById("divValFormula").innerHTML = formula;
            }

            function deleteLastCharacter() {
                formula = formula.slice(0, formula.length - 1);
                document.getElementById("divValFormula").innerHTML = formula;
            }

            function calculatePercentage() {
                let percentage = document.getElementById('percentageInput');
                resultPercentage = (result * percentage) / 100;
            }

            function calculateFormula() {
                document.getElementById("formula").value = formula;
                <?php
                $formula = $_POST['formula'];
                $ente =  $_COOKIE['Ente'];
                $fondo = $_COOKIE['Fondo'];
                $anno =  $_COOKIE['Anno'];
                $sezione =  $_COOKIE['Sezione'];
                $savedFormula = new FormulaTable();
                $savedFormula->saveFormula($sezione, $formula, $fondo, $ente, $anno);
                ?>
            }

        </script>
        </html lang="en">

        <?php


    }
}