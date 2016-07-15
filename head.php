<!DOCTYPE html >
<html moznomarginboxes mozdisallowselectionprint>
<head>
    <script src="//code.jquery.com/jquery-1.12.0.min.js"></script>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
          integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
          integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
            integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
            crossorigin="anonymous"></script>

    <link href="http://nvd3.org/assets/css/common.css" rel="stylesheet">
    <link href="http://nvd3.org/assets/css/nv.d3.css" rel="stylesheet">

    <script src="http://nvd3.org/assets/js/lib/bootstrap.min.js"></script>
    <script src="http://nvd3.org/assets/lib/d3.v3.js"></script>
    <script src="http://nvd3.org/assets/js/nv.d3.js"></script>

    <link rel="stylesheet" href="http://yandex.st/highlightjs/7.3/styles/default.min.css">
    <link rel="stylesheet" href="spiderweb.css">

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php echo $title ?></title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
          integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous"/>


    <link href="print.css" rel="spiderweb.css"/>
    <link href="print.css" rel="stylesheet"/>
</head>

<?php
include_once "bib.php";

function getTableHeader()
{
    $headers = array("Dimension", "Unter-Dimension", "Ebene 1: Grundverständnis von Sicherheitspraktiken", "Ebene 2: Erweitertes Verständnis von Sicherheitspraktiken", "Ebene 3: Hohes Verständnis von Sicherheitspraktiken", "Ebene 4: Sehr hohes Verständnis von Sicherheitspraktiken bei Skalierung");
    $headerContent = "<thead  class=\"thead-default\"><tr>";
    foreach ($headers as $header) {
        $headerContent .= "<th>$header</th>";
    }
    return $headerContent . "</tr></thead>";
}

function getInfos($dimensions)
{
    $text = "Anzahl der Elemente: " . getElementCount($dimensions);
    return $text;
}

function getElementCount($dimensions)
{
    $count = 0;
    foreach ($dimensions as $dimension => $subdimensions) {
        foreach ($subdimensions as $subdimension => $element) {
            $count = $count + count($element);
            echo "$subdimension" . count($element) . "<br>";
        }
    }
    return $count;
}
function getTable($dimensions)
{
    $tableContent = "";
    $tableContent .= getTableHeader();
    foreach ($dimensions as $dimension => $subdimensions) {
        foreach ($subdimensions as $subdimension => $element) {
            $tableContent .= "<tr>";
            $tableContent .= "<td>";
            $tableContent .= "$dimension";
            $tableContent .= "</td>";

            $tableContent .= "<td>";
            $tableContent .= "$subdimension";
            $tableContent .= "</td>";

            for ($i = 1; $i <= 4; $i++) {
                $tableContent .= "<td><ul>";
                foreach ($element as $elementName => $content) {
                    $content = getContentForLevelFromSubdimensions($i, $content, $elementName);
                    if ($content != "") {
                        $elementLink = "detail.php?dimension=" . urlencode($dimension) . "&subdimension=" . urlencode($subdimension) . "&element=" . urlencode($elementName);
                        $tableContent .= "<a href='$elementLink' data-dimension='$dimension' data-subdimension='$subdimension' data-element='$elementName'";
                        if (elementIsSelected($elementName)) {
                            $tableContent .= "class='selected'";
                        }
                        $tableContent .= "><li>" . $content . "</li></a>";
                    }
                }
                $tableContent .= "</ul></td>";
            }

            $tableContent .= "</tr>";
        }

    }
    $table = '<table class="table table-striped"><caption>Generisches DevOps-Sicherheits-Reifegradmodell</caption>';
    $table .= $tableContent;
    $table .= "</table>";
    return $table;
}

function getContentForLevelFromSubdimensions($level, $subdimension, $elementName)
{
    if ($level != $subdimension["level"]) {
        return "";
    }
    $tooltip = "<div class='popoverdetails'>" . build_table_tooltip($subdimension) . "</div>";
    return "<div data-toggle=\"popover\" data-title=\"$elementName\" data-content=\"$tooltip\" type=\"button\" >" . $elementName . "</div>";
}

