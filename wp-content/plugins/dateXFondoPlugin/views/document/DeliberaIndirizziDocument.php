<?php

namespace dateXFondoPlugin;

class DeliberaIndirizziDocument
{
    public static function render(){
?>
        <!DOCTYPE html>

        <html lang="en">

        <head>
            <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
            <script src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
                    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
                    crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
                    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
                    crossorigin="anonymous"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"
                  integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A=="
                  crossorigin="anonymous" referrerpolicy="no-referrer"/>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
            <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

        </head>

        <body>
        <h2>Il/La Giunta Comunale</h2> <button class="btn btn-outline-secondary btn-edit" style="width:10%">Modifica</button>
        <button class="btn btn-outline-secondary btn-save" hidden style="width:10%">Modifica</button>
        <h3>OGGETTO: PERSONALE NON DIRIGENTE. FONDO RISORSE DECENTRATE PER L’ANNO 2022. INDIRIZZI PER LA COSTITUZIONE PARTE VARIABILE. DIRETTIVE PER LA CONTRATTAZIONE DECENTRATA INTEGRATIVA.</h3>
        <p>Visti:
            <br>
            - la deliberazione di Consiglio Comunale/Assemblea n. del <span id="variableOne">xx.xx.xxxx</span>, esecutiva, relativa a: “Bilancio di previsione 2022, bilancio pluriennale e DUP/PEG 2022/2024, piano di investimenti – approvazione”;
            <br>
           - la deliberazione della/del Giunta Comunale n. del xx.xx.xxxx, esecutiva, relativa all’approvazione del Piano esecutivo di Gestione 2022 unitamente al Piano della Performance;
           <br>
            - i successivi atti di variazione del bilancio del comune e del P.E.G./Piano Performance;
            <br>
           -  il vigente Regolamento di Organizzazione degli Uffici e dei Servizi;
            <br>
           - la deliberazione della/del Giunta Comunale n. del xx.xx.xxxx di nomina della delegazione trattante di parte pubblica abilitata alla contrattazione collettiva decentrata integrativa per il personale dipendente;
        <br>
        </p>

        </body>

        <script>
            $('.btn-edit').click(function () {
                $('#variableOne').hide();
                $('.btn-edit').hide();
                $('btn-save').setAttribute('style','show');

            });

        </script>
        </html lang="en">

        <?php
    }

}