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
            <script src="https://unpkg.com/jspdf-invoice-template@1.4.0/dist/index.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
            <script type="text/javascript">
                //window.jsPDF = window.jspdf.jsPDF;

                function makeInputReadOnly() {
                    document.getElementById('InputFieldID').readonly = true;
                    document.getElementById('editableButton').hidden = false;
                    document.getElementById('saveEditButton').hidden = true;

                }
                function makeInputEditable(){
                    document.getElementById('InputFieldID').readonly = false;
                    document.getElementById('editableButton').hidden = true;
                    document.getElementById('saveEditButton').hidden = false;
                }

                function makePDF() {
                    window.jsPDF = window.jspdf.jsPDF;
                    let doc = new jsPDF();
                    // doc.text("Html to pdf", 10, 10);
                    // doc.save();
                    //const pdfObject = jsPDFInvoiceTemplate(props); //returns number of pages created
                    let pdfObject = jsPDFInvoiceTemplate.default(props);
                    // Create a rectangle and place a link box on top of it.
                    doc.rect(25, 50, 25, 25, 'FD');
                    doc.link(25, 50, 25, 25, {url: 'https://www.example.com/'});
                }

                let outputTypes = jsPDFInvoiceTemplate.OutputType;
                let props = {
                    outputType: outputTypes.Save,
                    returnJsPDFDocObject: true,
                    fileName: "Invoice 2021",
                    orientationLandscape: false,
                    compress: true,
                    logo: {
                        src: "https://raw.githubusercontent.com/edisonneza/jspdf-invoice-template/demo/images/logo.png",
                        type: 'PNG', //optional, when src= data:uri (nodejs case)
                        width: 53.33, //aspect ratio = width/height
                        height: 26.66,
                        margin: {
                            top: 0, //negative or positive num, from the current position
                            left: 0 //negative or positive num, from the current position
                        }
                    },
                    stamp: {
                        inAllPages: true, //by default = false, just in the last page
                        src: "https://raw.githubusercontent.com/edisonneza/jspdf-invoice-template/demo/images/qr_code.jpg",
                        type: 'JPG', //optional, when src= data:uri (nodejs case)
                        width: 20, //aspect ratio = width/height
                        height: 20,
                        margin: {
                            top: 0, //negative or positive num, from the current position
                            left: 0 //negative or positive num, from the current position
                        }
                    },
                    business: {
                        name: "Business Name",
                        address: "Albania, Tirane ish-Dogana, Durres 2001",
                        phone: "(+355) 069 11 11 111",
                        email: "email@example.com",
                        email_1: "info@example.al",
                        website: "www.example.al",
                    },
                    contact: {
                        label: "Invoice issued for:",
                        name: "Client Name",
                        address: "Albania, Tirane, Astir",
                        phone: "(+355) 069 22 22 222",
                        email: "client@website.al",
                        otherInfo: "www.website.al",
                    },
                    invoice: {
                        label: "Invoice #: ",
                        num: 19,
                        invDate: "Payment Date: 01/01/2021 18:12",
                        invGenDate: "Invoice Date: 02/02/2021 10:17",
                        headerBorder: false,
                        tableBodyBorder: false,
                        header: [
                            {
                                title: "#",
                                style: {
                                    width: 10
                                }
                            },
                            {
                                title: "Title",
                                style: {
                                    width: 30
                                }
                            },
                            {
                                title: "Description",
                                style: {
                                    width: 80
                                }
                            },
                            {title: "Price"},
                            {title: "Quantity"},
                            {title: "Unit"},
                            {title: "Total"}
                        ],
                        table: Array.from(Array(10), (item, index) => ([
                            index + 1,
                            "There are many variations ",
                            "Lorem Ipsum is simply dummy text dummy text ",
                            200.5,
                            4.5,
                            "m2",
                            400.5
                        ])),
                        additionalRows: [{
                            col1: 'Total:',
                            col2: '145,250.50',
                            col3: 'ALL',
                            style: {
                                fontSize: 14 //optional, default 12
                            }
                        },
                            {
                                col1: 'VAT:',
                                col2: '20',
                                col3: '%',
                                style: {
                                    fontSize: 10 //optional, default 12
                                }
                            },
                            {
                                col1: 'SubTotal:',
                                col2: '116,199.90',
                                col3: 'ALL',
                                style: {
                                    fontSize: 10 //optional, default 12
                                }
                            }],
                        invDescLabel: "Invoice Note",
                        invDesc: "There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary.",
                    },
                    footer: {
                        text: "The invoice is created on a computer and is valid without the signature and stamp.",
                    },
                    pageEnable: true,
                    pageLabel: "Page ",
                }
            </script>
        </head>
        <body>
        <p>

            Cos’è Lorem Ipsum?

            Lorem Ipsum è un testo segnaposto utilizzato nel settore della tipografia e della stampa. Lorem Ipsum è
            considerato il testo segnaposto standard sin dal sedicesimo secolo, quando un anonimo tipografo prese una
            cassetta di caratteri e li assemblò per preparare un testo campione. È sopravvissuto non solo a più di
            cinque secoli, ma anche al passaggio alla videoimpaginazione, pervenendoci sostanzialmente inalterato. Fu
            reso popolare, negli anni ’60, con la diffusione dei fogli di caratteri trasferibili “Letraset”, che
            contenevano passaggi del Lorem Ipsum, e più recentemente da software di impaginazione come Aldus PageMaker,
            che includeva versioni del Lorem Ipsum.
            Perchè lo utilizziamo?
            <input type="text" id="InputFieldID" readonly>
            È universalmente riconosciuto che un lettore che osserva il layout di una pagina viene distratto dal
            contenuto testuale se questo è leggibile. Lo scopo dell’utilizzo del Lorem Ipsum è che offre una normale
            distribuzione delle lettere (al contrario di quanto avviene se si utilizzano brevi frasi ripetute, ad
            esempio “testo qui”), apparendo come un normale blocco di testo leggibile. Molti software di impaginazione e
            di web design utilizzano Lorem Ipsum come testo modello. Molte versioni del testo sono state prodotte negli
            anni, a volte casualmente, a volte di proposito (ad esempio inserendo passaggi ironici).

        </p>
        <div>
            <button class="btn btn-primary" onclick="makeInputEditable()" id="editableButton">Modifica pdf</button>
            <button class="btn btn-primary" onclick="makeInputReadOnly()" id="saveEditButton" hidden>Salva modifica</button>
            <button class="btn btn-primary" onclick="makePDF()">Crea pdf</button>
        </div>
        </body>
        </html>


        <?php


    }
}