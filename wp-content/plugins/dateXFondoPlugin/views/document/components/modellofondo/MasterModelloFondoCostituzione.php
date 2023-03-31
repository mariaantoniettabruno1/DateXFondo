<?php

use dateXFondoPlugin\DateXFondoCommon;

class MasterModelloFondoCostituzione
{
    public static function render_scripts()
    {
        ?>
        <style>
            #editUtilizzoRowButton, #deleteUtilizzoRowButton {

                border-color: #26282f;
                background-color: #26282f;
            }

            #editUtilizzoRowButton:hover, #deleteUtilizzoRowButton:hover {
                border-color: #870e12;
                background-color: #870e12;
            }

            .class-accordion-button {
                color: #26282f;
            }

            .class-accordion-button:hover {
                color: #26282f;
            }

            .btn-delete-row, .btn-delete-row:hover {
                color: #870e12;
            }

            .btn-edit-row, .btn-edit-row:hover {
                color: #26282f;
            }

            #editRowButton, #deleteRowButton {

                border-color: #26282f;
                background-color: #26282f;
            }

            #editRowButton:hover, #deleteRowButton:hover {
                border-color: #870e12;
                background-color: #870e12;
            }

            .btn-excel {
                border-color: #26282f;
                color: #26282f;
            }

            .btn-excel:hover {
                border-color: #870e12;
                color: #870e12;
                background-color: white;
            }

            #editDatiUtiliRowButton, #deleteDatiUtiliRowButton {

                border-color: #26282f;
                background-color: #26282f;
            }

            #editDatiUtiliRowButton:hover, #deleteDatiUtiliRowButton:hover {
                border-color: #870e12;
                background-color: #870e12;


        </style>
        <script>
            let id = 0;

            function renderDataTable() {
                let filteredDocArticoli = articoli;
                let preventivo = '';
                let edit_button = '';
                let delete_button = '';
                for (let i = 0; i < sezioni.length; i++) {
                    $('#dataCostituzioneDocumentTableBody' + i).html('');
                    filteredDocArticoli = filteredDocArticoli.filter(art => art.sezione === sezioni[i])
                    filteredDocArticoli.forEach(art => {
                        if (art.preventivo !== undefined)
                            preventivo = art.preventivo;
                        if (Number(art.editable) === 0) {
                            edit_button = ` <button class="btn btn-link btn-edit-row" data-id='${art.id}' data-toggle="modal" data-target="#editModal" disabled><i class="fa-solid fa-pen"></i></button>`;
                            delete_button = ` <button class="btn btn-link btn-delete-row" data-id='${art.id}' data-toggle="modal" data-target="#deleteModal" disabled><i class="fa-solid fa-trash"></i></button>`;
                        } else {
                            edit_button = ` <button class="btn btn-link btn-edit-row" data-id='${art.id}' data-toggle="modal" data-target="#editModal"><i class="fa-solid fa-pen"></i></button>`;
                            delete_button = ` <button class="btn btn-link btn-delete-row" data-id='${art.id}' data-toggle="modal" data-target="#deleteModal"><i class="fa-solid fa-trash"></i></button>`;
                        }
                        $('#dataCostituzioneDocumentTableBody' + i).append(`
                                 <tr style="width: auto; padding: 10px 6px; border: 1px solid black; background-color: transparent; color: #457FAF;">
                                       <td style="padding: 10px 6px; border: 1px solid black;">${art.ordinamento}</td>
                                       <td style="padding: 10px 6px; border: 1px solid black;"> ${art.sottosezione}</td>
                                       <td style="padding: 10px 6px; border: 1px solid black;">${art.nome_articolo}</td>
                                       <td style="padding: 10px 6px; border: 1px solid black;">${preventivo}</td>

                     <td><div class="row pr-3">
                <div class="col-3">${edit_button}</div>
                <div class="col-3">${delete_button}</div>
                                    </td>
                                 </tr>
                             `);

                    });
                    filteredDocArticoli = articoli;
                }
                $('.btn-delete-row').click(function () {
                    id = $(this).attr('data-id');
                    console.log(id)

                });
                $('.btn-edit-row').click(function () {
                    id = $(this).attr('data-id');
                    const articolo = articoli.find(art => Number(art.id) === Number(id))
                    if (!articolo) return;
                    $('#idOrdinamento').val(articolo.ordinamento)
                    $('#idNomeArticolo').val(articolo.nome_articolo)
                    $('#idPreventivo').val(articolo.preventivo)

                });

            }

            let id_utilizzo = 0;

            function renderUtilizzoDataTable() {
                let filteredUtilizzoArticoli = articoli_utilizzo;
                let preventivo = '';
                let consuntivo = '';
                let edit_button = '';
                let delete_button = '';
                for (let i = 0; i < sezioni_utilizzo.length; i++) {
                    $('#dataUtilizzoDocumentTableBody' + i).html('');
                    filteredUtilizzoArticoli = filteredUtilizzoArticoli.filter(art => art.sezione === sezioni_utilizzo[i])
                    filteredUtilizzoArticoli.forEach(art => {
                        if (art.preventivo !== undefined)
                            preventivo = art.preventivo;
                        if (art.consuntivo !== undefined)
                            consuntivo = art.consuntivo;
                        if (Number(art.editable) === 0) {
                            edit_button = ` <button class="btn btn-link btn-edit-row" data-id='${art.id}' data-toggle="modal" data-target="#editUtilizzoModal" disabled><i class="fa-solid fa-pen"></i></button>`;
                            delete_button = ` <button class="btn btn-link btn-delete-row" data-id='${art.id}' data-toggle="modal" data-target="#deleteUtilizzoModal" disabled><i class="fa-solid fa-trash"></i></button>`;
                        } else {
                            edit_button = ` <button class="btn btn-link btn-edit-row" data-id='${art.id}' data-toggle="modal" data-target="#editUtilizzoModal"><i class="fa-solid fa-pen"></i></button>`;
                            delete_button = ` <button class="btn btn-link btn-delete-row" data-id='${art.id}' data-toggle="modal" data-target="#deleteUtilizzoModal"><i class="fa-solid fa-trash"></i></button>`;
                        }
                        $('#dataUtilizzoDocumentTableBody' + i).append(`
                                 <tr style="width: auto; padding: 10px 6px; border: 1px solid black; background-color: transparent; color: #457FAF;">
                                       <td style="padding: 10px 6px; border: 1px solid black;">${art.ordinamento}</td>
                                       <td style="padding: 10px 6px; border: 1px solid black;">${art.nome_articolo}</td>
                                       <td style="padding: 10px 6px; border: 1px solid black;">${preventivo}</td>
                                       <td style="padding: 10px 6px; border: 1px solid black;">${consuntivo}</td>

                     <td><div class="row pr-3">
                <div class="col-3">${edit_button}</div>
                <div class="col-3">${delete_button}</div>
                                    </td>
                                 </tr>
                             `);

                    });
                    filteredUtilizzoArticoli = articoli_utilizzo;
                }

                $('.btn-delete-row').click(function () {
                    id_utilizzo = $(this).attr('data-id');
                });
                $('.btn-edit-row').click(function () {
                    id_utilizzo = $(this).attr('data-id');
                    const articolo = articoli_utilizzo.find(art => Number(art.id) === Number(id_utilizzo))
                    if (!articolo) return;
                    $('#idUtilizzoOrdinamento').val(articolo.ordinamento)
                    $('#idUtilizzoNomeArticolo').val(articolo.nome_articolo)
                    $('#idUtilizzoPreventivo').val(articolo.preventivo)
                    $('#idUtilizzoConsuntivo').val(articolo.consuntivo)

                });


            }

            let id_dati_utili = 0;

            function renderDatiUtiliDataTable() {
                let filteredDatiUtiliArticoli = articoli_dati_utili;
                let nota = '';
                let =
                formula = '';
                let edit_button = '';
                let delete_button = '';
                for (let i = 0; i < sezioni_dati_utili.length; i++) {
                    $('#dataDatiUtiliDocumentTableBody' + i).html('');
                    filteredDatiUtiliArticoli = filteredDatiUtiliArticoli.filter(art => art.sezione === sezioni_dati_utili[i])
                    filteredDatiUtiliArticoli.forEach(art => {
                        if (art.formula !== undefined)
                            formula = art.formula;
                        if (art.nota !== undefined)
                            nota = art.nota;
                        if (Number(art.editable) === 0) {
                            edit_button = ` <button class="btn btn-link btn-edit-row" data-id='${art.id}' data-toggle="modal" data-target="#editDatiUtiliModal" disabled><i class="fa-solid fa-pen"></i></button>`;
                            delete_button = ` <button class="btn btn-link btn-delete-row" data-id='${art.id}' data-toggle="modal" data-target="#deleteDatiUtiliModal" disabled><i class="fa-solid fa-trash"></i></button>`;
                        } else {
                            edit_button = ` <button class="btn btn-link btn-edit-row" data-id='${art.id}' data-toggle="modal" data-target="#editDatiUtiliModal"><i class="fa-solid fa-pen"></i></button>`;
                            delete_button = ` <button class="btn btn-link btn-delete-row" data-id='${art.id}' data-toggle="modal" data-target="#deleteDatiUtiliModal"><i class="fa-solid fa-trash"></i></button>`;
                        }
                        $('#dataDatiUtiliDocumentTableBody' + i).append(`
                                 <tr style="width: auto; padding: 10px 6px; border: 1px solid black; background-color: transparent; color: #457FAF;">
                                       <td style="padding: 10px 6px; border: 1px solid black;">${art.ordinamento}</td>
                                       <td style="padding: 10px 6px; border: 1px solid black;">${art.sottosezione}</td>
                                       <td style="padding: 10px 6px; border: 1px solid black;">${art.nome_articolo}</td>
                                       <td style="padding: 10px 6px; border: 1px solid black;">${formula}</td>
                                       <td style="padding: 10px 6px; border: 1px solid black;">${nota}</td>

                     <td><div class="row pr-3">
                <div class="col-3">${edit_button}</div>
                <div class="col-3">${delete_button}</div>
                                    </td>
                                 </tr>
                             `);

                    });
                    filteredDatiUtiliArticoli = articoli_dati_utili;
                }

                $('.btn-delete-row').click(function () {
                    id_dati_utili = $(this).attr('data-id');
                });
                $('.btn-edit-row').click(function () {
                    id_dati_utili = $(this).attr('data-id');
                    const articolo = articoli_dati_utili.find(art => Number(art.id) === Number(id_dati_utili))
                    if (!articolo) return;
                    $('#idDatiUtiliOrdinamento').val(articolo.ordinamento)
                    $('#idDatiUtiliNomeArticolo').val(articolo.nome_articolo)
                    $('#idDatiUtiliFormula').val(articolo.formula)
                    $('#idDatiUtiliNota').val(articolo.nota)

                });


            }

            function renderEditArticleCostituzione() {

                let updateArticolo = articoli.find(art => art.id === Number(id));
                updateArticolo.nome_articolo = $('#idNomeArticolo').val();
                updateArticolo.ordinamento = $('#idOrdinamento').val();
                updateArticolo.preventivo = $('#idPreventivo').val();
            }

            function renderUtilizzoDataTableUtilizzo() {
                const updateArticolo = articoli_utilizzo.find(art => art.id === Number(id_utilizzo));
                updateArticolo.nome_articolo = $('#idUtilizzoNomeArticolo').val();
                updateArticolo.ordinamento = $('#idUtilizzoOrdinamento').val();
                updateArticolo.preventivo = $('#idUtilizzoPreventivo').val();
                updateArticolo.consuntivo = $('#idUtilizzoConsuntivo').val();
            }

            function renderEditArticle() {
                const updateArticolo = articoli_dati_utili.find(art => art.id === Number(id_dati_utili));
                updateArticolo.nome_articolo = $('#idDatiUtiliNomeArticolo').val();
                updateArticolo.ordinamento = $('#idDatiUtiliOrdinamento').val();
                updateArticolo.formula = $('#idDatiUtiliFormula').val();
                updateArticolo.nota = $('#idDatiUtiliNota').val();
            }

            const tablesToExcel = (function ($) {
                const uri = 'data:application/vnd.ms-excel;base64,'
                    ,
                    html_start = `<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">`
                    ,
                    template_ExcelWorksheet = `<x:ExcelWorksheet><x:Name>{SheetName}</x:Name><x:WorksheetSource HRef="sheet{SheetIndex}.htm"/></x:ExcelWorksheet>`
                    , template_ListWorksheet = `<o:File HRef="sheet{SheetIndex}.htm"/>`
                    , template_HTMLWorksheet = `
------=_NextPart_dummy
Content-Location: sheet{SheetIndex}.htm
Content-Type: text/html; charset=windows-1252

` + html_start + `
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
    <link id="Main-File" rel="Main-File" href="../WorkBook.htm">
    <link rel="File-List" href="filelist.xml">
</head>
<body><table>{SheetContent}</table></body>
</html>`
                    , template_WorkBook = `MIME-Version: 1.0
X-Document-Type: Workbook
Content-Type: multipart/related; boundary="----=_NextPart_dummy"

------=_NextPart_dummy
Content-Location: WorkBook.htm
Content-Type: text/html; charset=windows-1252

` + html_start + `
<head>
<meta name="Excel Workbook Frameset">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<link rel="File-List" href="filelist.xml">
<!--[if gte mso 9]><xml>
 <x:ExcelWorkbook>
    <x:ExcelWorksheets>{ExcelWorksheets}</x:ExcelWorksheets>
    <x:ActiveSheet>0</x:ActiveSheet>
 </x:ExcelWorkbook>
</xml><![endif]-->
</head>
<frameset>
    <frame src="sheet0.htm" name="frSheet">
    <noframes><body><p>This page uses frames, but your browser does not support them.</p></body></noframes>
</frameset>
</html>
{HTMLWorksheets}
Content-Location: filelist.xml
Content-Type: text/xml; charset="utf-8"

<xml xmlns:o="urn:schemas-microsoft-com:office:office">
    <o:MainFile HRef="../WorkBook.htm"/>
    {ListWorksheets}
    <o:File HRef="filelist.xml"/>
</xml>
------=_NextPart_dummy--
`
                    , base64 = function (s) {
                        return window.btoa(unescape(encodeURIComponent(s)))
                    }
                    , format = function (s, c) {
                        return s.replace(/{(\w+)}/g, function (m, p) {
                            return c[p];
                        })
                    }
                return function (id_tables, filename) {
                    const context_WorkBook = {
                        ExcelWorksheets: ''
                        , HTMLWorksheets: ''
                        , ListWorksheets: ''
                    };

                    const tables = jQuery(id_tables);
                    let nome_precedente = '';
                    let SheetName = '';
                    let $temp_table = '';
                    let one_table = 0;
                    let index;

                    $.each(tables, function (SheetIndex) {
                        const $table = $(this);
                        console.log($table);

                        SheetName = $table.attr('data-SheetName');
                        index = SheetIndex;
                        if (nome_precedente === SheetName) {
                            one_table = 2;

                            context_WorkBook.ExcelWorksheets += format(template_ExcelWorksheet, {
                                SheetIndex: SheetIndex
                                , SheetName: SheetName
                            });

                            $temp_table[0].innerHTML += $table[0].innerHTML
                            context_WorkBook.HTMLWorksheets += format(template_HTMLWorksheet, {
                                SheetIndex: SheetIndex
                                , SheetContent: $temp_table.html()
                            });
                            console.log($table.html());
                            context_WorkBook.ListWorksheets += format(template_ListWorksheet, {
                                SheetIndex: SheetIndex
                            });
                        } else {
                            if (one_table === 1) {
                                context_WorkBook.ExcelWorksheets += format(template_ExcelWorksheet, {
                                    SheetIndex: SheetIndex
                                    , SheetName: SheetName
                                });


                                context_WorkBook.HTMLWorksheets += format(template_HTMLWorksheet, {
                                    SheetIndex: SheetIndex
                                    , SheetContent: $temp_table.html()
                                });

                                context_WorkBook.ListWorksheets += format(template_ListWorksheet, {
                                    SheetIndex: SheetIndex
                                });
                            }
                            one_table = 1;
                        }

                        nome_precedente = $table.attr('data-SheetName');
                        $temp_table = $(this);

                    });
                    if (one_table === 1) {
                        context_WorkBook.ExcelWorksheets += format(template_ExcelWorksheet, {
                            SheetIndex: index
                            , SheetName: SheetName
                        });


                        context_WorkBook.HTMLWorksheets += format(template_HTMLWorksheet, {
                            SheetIndex: index
                            , SheetContent: $temp_table.html()
                        });

                        context_WorkBook.ListWorksheets += format(template_ListWorksheet, {
                            SheetIndex: index
                        });
                    }
                    var link = document.createElement("A");
                    link.href = uri + base64(format(template_WorkBook, context_WorkBook));
                    link.download = filename || 'Workbook.xls';
                    link.target = '_blank';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }
            })(jQuery);


            $(document).ready(function () {
                renderDataTable();
                renderUtilizzoDataTable();
                renderDatiUtiliDataTable();

                //Inizio callback per cancellazione e modifica righe Utilizzo Fondo
                $('#deleteUtilizzoRowButton').click(function () {
                    const payload = {
                        id_utilizzo
                    }

                    $.ajax({
                        url: '<?= DateXFondoCommon::get_website_url() ?>/wp-json/datexfondoplugin/v1/document/utilizzo/row/del',
                        data: payload,
                        type: "POST",
                        success: function (response) {
                            console.log(response);
                            $("#deleteUtilizzoModal").modal('hide');
                            articoli_utilizzo = articoli_utilizzo.filter(art => Number(art.id) !== Number(id_utilizzo));
                            renderUtilizzoDataTable();
                            $(".alert-delete-success").show();
                            $(".alert-delete-success").fadeTo(2000, 500).slideUp(500, function () {
                                $(".alert-delete-success").slideUp(500);
                            });

                        },
                        error: function (response) {
                            console.error(response);
                            $("#deleteUtilizzoModal").modal('hide');
                            $(".alert-delete-wrong").show();
                            $(".alert-delete-wrong").fadeTo(2000, 500).slideUp(500, function () {
                                $(".alert-delete-wrong").slideUp(500);
                            });
                        }
                    });
                });
                $('#editUtilizzoRowButton').click(function () {
                    let nome_articolo = $('#idUtilizzoNomeArticolo').val();
                    let ordinamento = $('#idUtilizzoOrdinamento').val();
                    let preventivo = $('#idUtilizzoPreventivo').val();
                    let consuntivo = $('#idUtilizzoConsuntivo').val();


                    const payload = {
                        id_utilizzo,
                        nome_articolo,
                        preventivo,
                        ordinamento,
                        consuntivo

                    }
                    console.log(payload)

                    $.ajax({
                        url: '<?= DateXFondoCommon::get_website_url() ?>/wp-json/datexfondoplugin/v1/utilizzo/document/row',
                        data: payload,
                        type: "POST",
                        success: function (response) {
                            console.log(response);
                            $("#editUtilizzoModal").modal('hide');
                            renderUtilizzoDataTableUtilizzo();
                            renderUtilizzoDataTable();
                            $(".alert-edit-success").show();
                            $(".alert-edit-success").fadeTo(2000, 500).slideUp(500, function () {
                                $(".alert-edit-success").slideUp(500);
                            });
                        },
                        error: function (response) {
                            console.error(response);
                            $("#editUtilizzoModal").modal('hide');
                            $(".alert-edit-wrong").show();
                            $(".alert-edit-wrong").fadeTo(2000, 500).slideUp(500, function () {
                                $(".alert-edit-wrong").slideUp(500);
                            });
                        }
                    });
                });
                //Inizio callback per cancellazione e modifica righe Costituzione Fondo
                $('#deleteRowButton').click(function () {
                    const payload = {
                        id
                    }

                    $.ajax({

                        url: '<?= DateXFondoCommon::get_website_url() ?>/wp-json/datexfondoplugin/v1/document/row/del',
                        data: payload,
                        type: "POST",
                        success: function (response) {
                            console.log(response);
                            $("#deleteModal").modal('hide');
                            articoli = articoli.filter(art => Number(art.id) !== Number(id));
                            renderDataTable();
                            $(".alert-delete-success").show();
                            $(".alert-delete-success").fadeTo(2000, 500).slideUp(500, function () {
                                $(".alert-delete-success").slideUp(500);
                            });

                        },
                        error: function (response) {
                            console.error(response);
                            $("#deleteModal").modal('hide');
                            $(".alert-delete-wrong").show();
                            $(".alert-delete-wrong").fadeTo(2000, 500).slideUp(500, function () {
                                $(".alert-delete-wrong").slideUp(500);
                            });
                        }
                    });
                });

                $('#editRowButton').click(function () {

                    let nome_articolo = $('#idNomeArticolo').val();
                    let ordinamento = $('#idOrdinamento').val();
                    let preventivo = $('#idPreventivo').val();


                    const payload = {
                        id,
                        nome_articolo,
                        preventivo,
                        ordinamento

                    }
                    console.log(payload)

                    $.ajax({
                        url: '<?= DateXFondoCommon::get_website_url() ?>/wp-json/datexfondoplugin/v1/document/row',
                        data: payload,
                        type: "POST",
                        success: function (response) {
                            console.log(response);
                            $("#editModal").modal('hide');
                            renderEditArticleCostituzione();
                            renderDataTable();

                            $(".alert-edit-success").show();
                            $(".alert-edit-success").fadeTo(2000, 500).slideUp(500, function () {
                                $(".alert-edit-success").slideUp(500);
                            });


                        },
                        error: function (response) {
                            console.error(response);
                            $("#editModal").modal('hide');
                            $(".alert-edit-wrong").show();
                            $(".alert-edit-wrong").fadeTo(2000, 500).slideUp(500, function () {
                                $(".alert-edit-wrong").slideUp(500);
                            });
                        }
                    });
                });

                //Inizio callback per cancellazione e modifica righe Dati Utili Fondo
                $('#deleteDatiUtiliRowButton').click(function () {
                    const payload = {
                        id_dati_utili
                    }

                    $.ajax({
                        url: '<?= DateXFondoCommon::get_website_url() ?>/wp-json/datexfondoplugin/v1/document/datiutili/row/del',
                        data: payload,
                        type: "POST",
                        success: function (response) {
                            console.log(response);
                            $("#deleteDatiUtiliModal").modal('hide');
                            articoli_dati_utili = articoli_dati_utili.filter(art => Number(art.id) !== Number(id_dati_utili));
                            renderDatiUtiliDataTable();
                            $(".alert-delete-success").show();
                            $(".alert-delete-success").fadeTo(2000, 500).slideUp(500, function () {
                                $(".alert-delete-success").slideUp(500);
                            });

                        },
                        error: function (response) {
                            console.error(response);
                            $("#deleteDatiUtiliModal").modal('hide');
                            $(".alert-delete-wrong").show();
                            $(".alert-delete-wrong").fadeTo(2000, 500).slideUp(500, function () {
                                $(".alert-delete-wrong").slideUp(500);
                            });
                        }
                    });
                });
                $('#editDatiUtiliRowButton').click(function () {
                    let nome_articolo = $('#idDatiUtiliNomeArticolo').val();
                    let ordinamento = $('#idDatiUtiliOrdinamento').val();
                    let formula = $('#idDatiUtiliFormula').val();
                    let nota = $('#idDatiUtiliNota').val();


                    const payload = {
                        id_dati_utili,
                        nome_articolo,
                        formula,
                        ordinamento,
                        nota

                    }
                    console.log(payload)

                    $.ajax({
                        url: '<?= DateXFondoCommon::get_website_url() ?>/wp-json/datexfondoplugin/v1/datiutili/document/row',
                        data: payload,
                        type: "POST",
                        success: function (response) {
                            console.log(response);
                            $("#editDatiUtiliModal").modal('hide');
                            renderEditArticle();
                            renderDatiUtiliDataTable();
                            $(".alert-edit-success").show();
                            $(".alert-edit-success").fadeTo(2000, 500).slideUp(500, function () {
                                $(".alert-edit-success").slideUp(500);
                            });
                        },
                        error: function (response) {
                            console.error(response);
                            $("#editDatiUtiliModal").modal('hide');
                            $(".alert-edit-wrong").show();
                            $(".alert-edit-wrong").fadeTo(2000, 500).slideUp(500, function () {
                                $(".alert-edit-wrong").slideUp(500);
                            });
                        }
                    });
                });


            });

        </script>
        <?php

    }

    public static function render()
    {

        $data = new DocumentRepository();
        if (isset($_GET['version'])) {
            $tot_sezioni = $data->getHistorySezioni($_GET['editor_name'], $_GET['version']);
        } else {
            $tot_sezioni = $data->getSezioni($_GET['editor_name']);
        }

        $formulas = $data->getFormulas($_GET['editor_name']);
        $ids_articolo = $data->getIdsArticoli($_GET['editor_name']);
        $array = array_merge($ids_articolo, $formulas);

        if ($_GET['version']) {
            $tot_sezioni_utilizzo = $data->getSezioniHistoryUtilizzo($_GET['editor_name'], $_GET['version']);
        } else {
            $tot_sezioni_utilizzo = $data->getSezioniUtilizzo($_GET['editor_name']);
        }

        if (isset($_GET['version'])) {
            $tot_sezioni_utili = $data->getSezioniHistoryDatiUtili($_GET['editor_name'], $_GET['version']);
        } else {
            $tot_sezioni_utili = $data->getSezioniDatiUtili($_GET['editor_name']);
        }


        ?>
        <div class="container pt-3" style="width: 100%">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-link active" id="costituzione-tab" href="#costituzione" role="tab"
                       aria-controls="costituzione" aria-selected="true" data-toggle="pill">Costituzione</a>
                    <a class="nav-link" id="utilizzo-tab" href="#utilizzo" role="tab" aria-controls="utilizzo"
                       aria-selected="false" data-toggle="pill">Utilizzo</a>
                    <a class="nav-link" id="dati-tab" href="#dati" role="tab" aria-controls="dati_utili"
                       aria-selected="false" data-toggle="pill">Dati utili fondo</a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="costituzione" role="tabpanel"
                     aria-labelledby="costituzione-tab" aria-selected="true">
                    <div class="accordion mt-2 col" id="accordionCostituzioneDocumentTable">
                        <?php
                        $section_index = 0;
                        foreach ($tot_sezioni as $sezione) {
                            ?>
                            <div class="card" id="costituzioneDocumentCard">
                                <div class="card-header" id="headingCostituzioneDocument<?= $section_index ?>">
                                    <button class="btn btn-link class-accordion-button" data-toggle="collapse"
                                            data-target="#collapseCostituzioneDocument<?= $section_index ?>"
                                            aria-expanded="false"
                                            aria-controls="collapseCostituzioneDocument<?= $section_index ?>"
                                            data-section="<?= $sezione['sezione'] ?>">
                                        <?= $sezione['sezione'] ?>
                                    </button>
                                </div>
                                <div id="collapseCostituzioneDocument<?= $section_index ?>" class="collapse"
                                     aria-labelledby="headingCostituzioneDocument<?= $section_index ?>"
                                     data-parent="#accordionCostituzioneDocumentTable">
                                    <div class="card-body ">
                                        <table class="content_table" id="contentTable" data-SheetName="Costituzione">
                                            <tr>
                                                <td>
                                                    <table class="table datatable_costituzione"
                                                           id="exportableTableCostituzione<?= $section_index ?>">
                                                        <thead style="position:relative; min-width: 100%;">
                                                        <tr style="position:relative; width: auto; padding: 10px 6px; border: 1px solid black; font-weight: 600; background-color: #457FAF; color: #FFFFFF;">
                                                            <th style="position:relative; padding: 10px 6px; border: 1px solid black; font-weight: 600;">Ordinamento</th>
                                                            <th style="position:relative; padding: 10px 6px; border: 1px solid black; font-weight: 600;">Sottosezione</th>
                                                            <th style="position:relative; padding: 10px 6px; border: 1px solid black; font-weight: 600;">Nome Articolo</th>
                                                            <th style="position:relative; padding: 10px 6px; border: 1px solid black; font-weight: 600;">Preventivo</th>
                                                            <th style="position:relative; padding: 10px 6px; border: 1px solid black; font-weight: 600;">Azioni</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="dataCostituzioneDocumentTableBody<?= $section_index ?>">
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>

                                            <tr></tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $section_index++;
                        }
                        ?>
                    </div>
                </div>
                <div class="tab-pane fade" id="utilizzo" role="tabpanel" aria-labelledby="utilizzo-tab"
                     aria-selected="false">
                    <div class="accordion mt-2 col" id="accordionUtilizzoDocumentTable">
                        <?php
                        $section_index = 0;
                        foreach ($tot_sezioni_utilizzo as $sezione) {
                            ?>
                            <div class="card" id="utilizzoDocumentCard">
                                <div class="card-header" id="headingUtilizzoDocument<?= $section_index ?>">
                                    <button class="btn btn-link class-accordion-button" data-toggle="collapse"
                                            data-target="#collapseUtilizzoDocument<?= $section_index ?>"
                                            aria-expanded="false"
                                            aria-controls="collapseUtilizzoDocument<?= $section_index ?>"
                                            data-section="<?= $sezione['sezione'] ?>">
                                        <?= $sezione['sezione'] ?>
                                    </button>
                                </div>
                                <div id="collapseUtilizzoDocument<?= $section_index ?>" class="collapse"
                                     aria-labelledby="headingUtilizzoDocument<?= $section_index ?>"
                                     data-parent="#accordionUtilizzoDocumentTable">
                                    <div class="card-body ">
                                        <table class="content_table_utilizzo" id="contentTableUtilizzo"
                                               data-SheetName="UtilizzoFondo">
                                            <tr>
                                                <td>
                                                    <table class="table datatable_utilizzo"
                                                           id="exportableTableUtilizzo<?= $section_index ?>">
                                                        <thead style="position:relative; min-width: 100%;">
                                                        <tr style="position:relative; width: auto; padding: 10px 6px; border: 1px solid black; font-weight: 600; background-color: #457FAF; color: #FFFFFF;">
                                                            <th style="position:relative; padding: 10px 6px; border: 1px solid black; font-weight: 600;">Ordinamento</th>
                                                            <th style="position:relative; padding: 10px 6px; border: 1px solid black; font-weight: 600;">Nome Articolo</th>
                                                            <th style="position:relative; padding: 10px 6px; border: 1px solid black; font-weight: 600;">Preventivo</th>
                                                            <th style="position:relative; padding: 10px 6px; border: 1px solid black; font-weight: 600;">Consuntivo</th>
                                                            <th style="position:relative; padding: 10px 6px; border: 1px solid black; font-weight: 600;">Azioni</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="dataUtilizzoDocumentTableBody<?= $section_index ?>">
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr></tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $section_index++;
                        }
                        ?>
                    </div>
                </div>
                <div class="tab-pane fade" id="dati" role="tabpanel" aria-labelledby="dati-tab" aria-selected="false">
                    <div class="accordion mt-2 col" id="accordionDatiUtiliDocumentTable">
                        <?php
                        $section_index = 0;
                        foreach ($tot_sezioni_utili as $sezione) {
                            ?>
                            <div class="card" id="datiUtiliDocumentCard">
                                <div class="card-header" id="headingDatiUtiliDocument<?= $section_index ?>">
                                    <button class="btn btn-link class-accordion-button" data-toggle="collapse"
                                            data-target="#collapseDatiUtiliDocument<?= $section_index ?>"
                                            aria-expanded="false"
                                            aria-controls="collapseDatiUtiliDocument<?= $section_index ?>"
                                            data-section="<?= $sezione['sezione'] ?>">
                                        <?= $sezione['sezione'] ?>
                                    </button>
                                </div>
                                <div id="collapseDatiUtiliDocument<?= $section_index ?>" class="collapse"
                                     aria-labelledby="headingDatiUtiliDocument<?= $section_index ?>"
                                     data-parent="#accordionDatiUtiliDocumentTable">
                                    <div class="card-body ">
                                        <table class="content_table_dati" id="contentTableDati"
                                               data-SheetName="DatiUtili">
                                            <tr>
                                                <td>
                                                    <table class="table datatable_dati_utili"
                                                           id="exportableTableDatiUtili<?= $section_index ?>">
                                                        <thead style="position:relative; min-width: 100%;">
                                                        <tr style="position:relative; width: auto; padding: 10px 6px; border: 1px solid black; font-weight: 600; background-color: #457FAF; color: #FFFFFF;">
                                                            <th style="position:relative; padding: 10px 6px; border: 1px solid black; font-weight: 600;">Ordinamento</th>
                                                            <th style="position:relative; padding: 10px 6px; border: 1px solid black; font-weight: 600;">Sottosezione</th>
                                                            <th style="position:relative; padding: 10px 6px; border: 1px solid black; font-weight: 600;">Nome Articolo</th>
                                                            <th style="position:relative; padding: 10px 6px; border: 1px solid black; font-weight: 600;">formula</th>
                                                            <th style="position:relative; padding: 10px 6px; border: 1px solid black; font-weight: 600;">nota</th>
                                                            <th style="position:relative; padding: 10px 6px; border: 1px solid black; font-weight: 600;">Azioni</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="dataDatiUtiliDocumentTableBody<?= $section_index ?>">
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr></tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $section_index++;
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row d-flex flex-row-reverse ">
                <div class="p-2">
                    <button class="btn btn-outline-primary btn-excel"
                            onclick="tablesToExcel('#contentTableDati,#contentTable,#contentTableUtilizzo', 'WorkSheet.xls');">
                        Genera
                        Foglio Excel
                    </button>
                </div>
            </div>
        </div>

        <!--Inizio modali per cancellazione e modifica riga Costituzione Fondo-->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Cancella riga </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Vuoi veramente eliminare questa riga?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                        <button type="button" class="btn btn-primary" id="deleteRowButton">Cancella</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editModal" tabindex="-1"
             role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifica riga del documento:</h5>
                        <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <label>Ordinamento</label>
                        <input type="text" class="form-control" id="idOrdinamento">

                        <label>Nome Articolo</label>
                        <input type="text" class="form-control" id="idNomeArticolo">

                        <label>Preventivo</label>

                        <select name="preventivo" id="idPreventivo">
                            <?php
                            foreach ($array as $item) {
                                ?>
                                <option><?= $item[0] ?></option>
                            <?php }
                            ?>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" id="editRowButton">Salva Modifica</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="alert alert-success alert-edit-success" role="alert"
             style="position:fixed; top: <?= is_admin_bar_showing() ? 47 : 15 ?>px; right: 15px; display:none">
            Modifica andata a buon fine!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-danger alert-edit-wrong" role="alert"
             style="position:fixed; top: <?= is_admin_bar_showing() ? 47 : 15 ?>px; right: 15px; display:none">
            Modifica riga non riuscita
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-success alert-delete-success" role="alert"
             style="position:fixed; top: <?= is_admin_bar_showing() ? 47 : 15 ?>px; right: 15px; display:none">
            Cancellazione riga andata a buon fine!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-danger alert-delete-wrong" role="alert"
             style="position:fixed; top: <?= is_admin_bar_showing() ? 47 : 15 ?>px; right: 15px; display:none">
            Cancellazione riga non riuscita
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <!--Inizio modali per cancellazione e modifica riga Utilizzo Fondo-->
        <div class="modal fade" id="deleteUtilizzoModal" tabindex="-1" role="dialog"
             aria-labelledby="deleteUtilizzoModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteUtilizzoModalLabel">Cancella riga </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Vuoi veramente eliminare questa riga?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                        <button type="button" class="btn btn-primary" id="deleteUtilizzoRowButton">Cancella</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editUtilizzoModal" tabindex="-1"
             role="dialog"
             aria-labelledby="myUtilizzoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifica riga del documento:</h5>
                        <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <label>Ordinamento</label>
                        <input type="text" class="form-control" id="idUtilizzoOrdinamento">

                        <label>Nome Articolo</label>
                        <input type="text" class="form-control" id="idUtilizzoNomeArticolo">

                        <label>Preventivo</label>

                        <select name="preventivo" id="idUtilizzoPreventivo">
                            <?php
                            foreach ($array as $item) {
                                ?>
                                <option value="GFG_2" selected="selected">
                                <option><?= $item[0] ?></option>
                            <?php }
                            ?>
                        </select>
                        <label>Consuntivo</label>
                        <input type="text" class="form-control" id="idUtilizzoConsuntivo">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" id="editUtilizzoRowButton">Salva Modifica</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="alert alert-success alert-edit-success" role="alert"
             style="position:fixed; top: <?= is_admin_bar_showing() ? 47 : 15 ?>px; right: 15px; display:none">
            Modifica andata a buon fine!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-danger alert-edit-wrong" role="alert"
             style="position:fixed; top: <?= is_admin_bar_showing() ? 47 : 15 ?>px; right: 15px; display:none">
            Modifica riga non riuscita
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-success alert-delete-success" role="alert"
             style="position:fixed; top: <?= is_admin_bar_showing() ? 47 : 15 ?>px; right: 15px; display:none">
            Cancellazione riga andata a buon fine!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-danger alert-delete-wrong" role="alert"
             style="position:fixed; top: <?= is_admin_bar_showing() ? 47 : 15 ?>px; right: 15px; display:none">
            Cancellazione riga non riuscita
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <!--Inizio modali per cancellazione e edit di righe Dati utili fondo-->
        <div class="modal fade" id="deleteDatiUtiliModal" tabindex="-1" role="dialog"
             aria-labelledby="deleteDatiUtiliModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteDatiUtiliModalLabel">Cancella riga </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Vuoi veramente eliminare questa riga?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                        <button type="button" class="btn btn-primary" id="deleteDatiUtiliRowButton">Cancella</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editDatiUtiliModal" tabindex="-1"
             role="dialog"
             aria-labelledby="myDatiUtiliModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifica riga del documento:</h5>
                        <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <label>Ordinamento</label>
                        <input type="text" class="form-control" id="idDatiUtiliOrdinamento">

                        <label>Nome Articolo</label>
                        <input type="text" class="form-control" id="idDatiUtiliNomeArticolo">

                        <label>Formula</label>

                        <select name="formula" id="idDatiUtiliFormula">
                            <?php
                            foreach ($array as $item) {
                                ?>
                                <option value="GFG_2" selected="selected">
                                <option><?= $item[0] ?></option>
                            <?php }
                            ?>
                        </select>
                        <label>Nota</label>
                        <textarea class="form-control" id="idDatiUtiliNota"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" id="editDatiUtiliRowButton">Salva Modifica</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="alert alert-success alert-edit-success" role="alert"
             style="position:fixed; top: <?= is_admin_bar_showing() ? 47 : 15 ?>px; right: 15px; display:none">
            Modifica andata a buon fine!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-danger alert-edit-wrong" role="alert"
             style="position:fixed; top: <?= is_admin_bar_showing() ? 47 : 15 ?>px; right: 15px; display:none">
            Modifica riga non riuscita
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-success alert-delete-success" role="alert"
             style="position:fixed; top: <?= is_admin_bar_showing() ? 47 : 15 ?>px; right: 15px; display:none">
            Cancellazione riga andata a buon fine!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-danger alert-delete-wrong" role="alert"
             style="position:fixed; top: <?= is_admin_bar_showing() ? 47 : 15 ?>px; right: 15px; display:none">
            Cancellazione riga non riuscita
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <?php
        self::render_scripts();

    }
}