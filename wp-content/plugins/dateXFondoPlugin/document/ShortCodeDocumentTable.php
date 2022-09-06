<?php

namespace dateXFondoPlugin;

use DocumentTable;
use GFAPI;
use Mpdf\Form;

header('Content-Type: text/javascript');

class ShortCodeDocumentTable
{
    public static function visualize_document_template()
    {
        // per filtrare il contenuto delle pagine tramite gli utenti
        global $current_user;
        get_currentuserinfo();
        if ($current_user->user_login == 'admin') {
            //do something
        } else {
            //do something else
        }
        $document = new DocumentTable();
        $entries = $document->getEditedDocument(30);

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
            <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
            <script type="text/javascript">
                $(document).ready(function () {
                    let document_text = `<?php echo json_encode($entries['testo']);?>`;
                    document.getElementById("paragraphDocumentID").innerHTML = document_text;
                });


                function makeInputReadOnly() {

                    document.getElementById("inputId").setAttribute("readonly", "readonly");
                    let inputValue = document.getElementById('inputId').value;
                    document.getElementById('inputId').setAttribute('value', inputValue);
                    let val = document.getElementById("paragraphDocumentID").innerHTML;
                    document.getElementById("hiddenParagraphId").value = val;
                    document.getElementById('editableButton').hidden = false;
                    document.getElementById('saveEditButton').hidden = true;
                    <?php
                    $documentText = $_POST['hiddenParagraphId'];
                    $goodUrl = str_replace('\"', '', $documentText);
                    $document->updateDocument('', $goodUrl, '', '', 0);
                    $document->updateDocument('', $goodUrl, '', '', 0);

                    ?>
                    console.log(val);
                }


                function makeInputEditable() {
                    document.getElementById('inputId').removeAttribute("readonly");
                    document.getElementById('editableButton').hidden = true;
                    document.getElementById('saveEditButton').hidden = false;
                }

                function createPDF() {

                    var element = document.getElementById('container_content');
                    var opt = {
                        margin: [0.5, 1, 0.5, 1],
                        filename: 'myfile.pdf',
                        image: {type: 'jpeg', quality: 0.98},
                        html2canvas: {scale: 2},
                        jsPDF: {unit: 'in', format: 'a4', orientation: 'l'}
                    };

                    html2pdf().set(opt).from(element).save();
                }


            </script>
        </head>
        <body>
        <div class="container_content" id="container_content">
            <p id="paragraphDocumentID">
        </div>
        <form method="POST">
            <input type='hidden' id='hiddenParagraphId' name='hiddenParagraphId'>
            <input type="submit" class="btn btn-info" style="float: right" onclick="makeInputReadOnly()"
                   value="Salva modifica" id="saveEditButton" hidden>
        </form>
        <div>
            <button class="btn btn-primary" onclick="makeInputEditable()" id="editableButton">Modifica pdf</button>
            <button class="btn btn-primary" onclick="createPDF()">Crea pdf</button>
            <div>


            </div>
        </div>
        </body>
        </html>


        <?php

    }
}