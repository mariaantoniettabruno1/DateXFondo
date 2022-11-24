<?php


class MasterModelloFondoDocumentTable
{
    public static function render_scripts()
    {

    }

    public static function render()
    {
        ?>
        <ul class="nav nav-tabs" id="modelloFondoTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="costituzione-tab" href="#costituzione" role="tab"
                   aria-controls="costituzione" aria-selected="true" data-toggle="pill">Costituzione</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="utilizzo-tab" href="#utilizzo" role="tab" aria-controls="utilizzo"
                   aria-selected="false" data-toggle="pill">Utilizzo</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="dati-tab" href="#dati" role="tab" aria-controls="dati_utili"
                   aria-selected="false" data-toggle="pill">Dati utili fondo</a>
            </li>
        </ul>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="costituzione" role="tabpanel" aria-labelledby="costituzione-tab">
                <?php
                MasterModelloFondoCostituzione::render();
                ?>
            </div>
            <div class="tab-pane fade" id="utilizzo" role="tabpanel" aria-labelledby="utilizzo-tab">
                <?php
                MasterModelloFondoUtilizzo::render();
                ?>
            </div><div class="tab-pane fade" id="dati" role="tabpanel" aria-labelledby="dati-tab">
                <?php
                MasterModelloFondoDatiUtili::render();
                ?>
            </div>
        </div>

        <?php
        self::render_scripts();
    }
}