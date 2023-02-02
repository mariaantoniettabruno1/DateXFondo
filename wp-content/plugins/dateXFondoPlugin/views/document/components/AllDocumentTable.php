<?php

use dateXFondoPlugin\DateXFondoCommon;

class AllDocumentTable
{
    private $documents;

    public function __construct($documents)
    {
        $this->documents = $documents;
    }

    public function render_scripts()
    {
        ?>
        <style>
            .btn-vis-templ, .btn-vis-templ:hover, .btn-dup-templ, .btn-dup-templ:hover {
                color: #26282f;
            }

        </style>
        <script>
            let documents = JSON.parse((`<?= json_encode($this->documents); ?>`));
            let document_name = '';
            let editor_name = '';
            let anno = ' ';
            let version = '';

            function renderDataTable() {
                let current_url = '<?=
                    (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']
                    === 'on' ? "https" : "http") .
                    "://" . $_SERVER['HTTP_HOST'] .
                    $_SERVER['REQUEST_URI'];?>';
                let dup_button = "";

                documents.forEach(doc => {
                    if (current_url.includes('storico')) {
                        dup_button = ` <button class="btn btn-link btn-dup-templ" data-document='${doc.document_name}' data-editor='${doc.editor_name}' data-anno = '${doc.anno}' data-version ='${doc.version}'  data-page='${doc.page}' data-toggle="modal" data-target="#duplicateModal" data-toggle="tooltip" title="Duplica documento"><i class="fa-regular fa-copy"></i></button>`;

                    } else {
                        dup_button = `<button class="btn btn-link btn-dup-templ" style="display:none" data-document='${doc.document_name}' data-editor='${doc.editor_name}' data-anno = '${doc.anno}' data-version ='${doc.version}' data-page='${doc.page}' data-toggle="modal" data-target="#duplicateModal" data-toggle="tooltip" title="Duplica documento"><i class="fa-regular fa-copy"></i></button>`;

                    }
                    $('#dataDocumentTableBody').append(`
                                 <tr>
                                       <td>${doc.document_name}</td>
                                       <td>${doc.editor_name}</td>
                                       <td>${doc.anno}</td>
                                       <td>${doc.version}</td>


                     <td><div class="row pr-3">
                      <div class="col-3">
               <button class="btn btn-link btn-vis-templ" data-document='${doc.document_name}' data-editor='${doc.editor_name}' data-page = '${doc.page}' data-version ='${doc.version}' data-anno ='${doc.anno}' data-toggle="tooltip" title="Visualizza e modifica documento"><i class="fa-solid fa-eye"></i></button></div>
                                 <div class="col-3">  ${dup_button}</div>
                                 </div>   </td>
                                 </tr>
                             `);
                });
                $('.btn-dup-templ').click(function () {
                    document_name = $(this).attr('data-document');
                   editor_name = $(this).attr('data-editor');
                    version = $(this).attr(' data-version');
                    anno = $(this).attr(' data-anno');

                });

            }

            $(document).ready(function () {

                renderDataTable();

                let current_url = '<?=
                    (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']
                    === 'on' ? "https" : "http") .
                    "://" . $_SERVER['HTTP_HOST'] .
                    $_SERVER['REQUEST_URI'];?>';
                $('.btn-vis-templ').click(function () {
                    let document_name = $(this).attr('data-document');
                    let editor_name = $(this).attr('data-editor');
                    let page = $(this).attr('data-page');
                    let version = $(this).attr('data-version');
                    let anno = $(this).attr('data-anno');
                    if (current_url.includes('storico')) {
                        location.href = '<?= DateXFondoCommon::get_website_url()?>/' + page + '?document_name=' + document_name + '&editor_name=' + editor_name + '&version=' + version + '&anno=' + anno;
                    } else {
                        location.href = '<?= DateXFondoCommon::get_website_url()?>/' + page + '?document_name=' + document_name + '&editor_name=' + editor_name;

                    }
                });
                $('#duplicateDocumentButton').click(function () {
                    let page = $(this).attr('data-page');
                    const payload = {
                        document_name,
                        editor_name,
                        anno,
                        version
                    }
                    console.log(payload)
                    $.ajax({
                        url: '<?= DateXFondoCommon::get_website_url() ?>/wp-json/datexfondoplugin/v1/duplicatedocument',
                        data: payload,
                        type: "POST",
                        success: function (response) {
                            console.log(response);
                            $("#duplicateModal").modal('hide');
                            location.href = '<?= DateXFondoCommon::get_website_url()?>/' + page + '?document_name=' + document_name + '&editor_name=' + editor_name;
                        },
                        error: function (response) {
                            console.error(response);
                            $("#duplicateModal").modal('hide');
                        }
                    });
                });

            });
        </script>


        <?php


    }

    public function render()
    {
        ?>
        <table class="table" style="table-layout: fixed">
            <thead>
            <tr>
                <th style="width: 12.5rem">Nome Documento</th>
                <th style="width: 6.25rem">Editor</th>
                <th style="width: 6.25rem">Anno</th>
                <th style="width: 6.25rem">Versione</th>
                <th style="width:6.25rem">Azioni</th>
            </tr>

            </thead>
            <tbody id="dataDocumentTableBody">
            </tbody>
        </table>
        <div class="modal fade" id="duplicateModal" tabindex="-1" role="dialog" aria-labelledby="duplicateModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="duplicateModalLabel">Duplica Documento </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Vuoi duplicare questo documento?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                        <button type="button" class="btn btn-primary" id="duplicateDocumentButton">Duplica</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
        self::render_scripts();

    }

}