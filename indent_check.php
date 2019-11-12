<?php
function indentCheck($upfile, $style)
{
    // save file
    // $upfile = $_FILES['upfile'];
    $src = $upfile['tmp_name'];
    $dst = "./" . basename($upfile['name']);
    move_uploaded_file($src, $dst);
    
    // indent check
    // $style = $_POST['style'];
    $instr = "AStyle ";
    if ($style != "default")
        $instr .= "--style=" . $style . " ";
    $instr .= $upfile['name'];
    exec($instr);
}

function readDiffFile($upfile, &$lineNums)
{
    $index = 0;
    $fileName = $upfile['name'] . ".txt";
    $fr = file_get_contents($fileName);
    if ($fr != FALSE)
    {
        preg_match_all("/^\sline\s\[(.*)\]\s:\s*$/m", $fr, $lineStr, PREG_PATTERN_ORDER);
        for ($i = 0; $i < count($lineStr[1]); ++$i)
        {
            preg_match_all("/\d+/", $lineStr[1][$i], $Nums);
            for ($j = 0; $j < count($Nums[0]); ++$j)
            {
                $lineNums[$index++] = (int)$Nums[0][$j];
            }
        }
        unlink($fileName);
    }
}

function readLineDiffFile($upfile, &$mapping, &$mappingCount)
{
    $fileName = $upfile['name'] . "_line.txt";
    $lines = @file($fileName);
    if ($lines != FALSE) {
        for ($i = 0; $i < count($lines); ++$i)
        {
            $index = $i + 1;
            $arr = explode(" ", $lines[$i]);
            $mapping[$index] = $arr[1];
            $mappingCount[(int)$arr[1]] = $arr[2];
        }
        unlink($fileName);
    }
}

function readFiles($upfile, &$origFile, &$newFile)
{
    // after indentCheck();
    $mapping = ["0"];
    $mappingCount = ["0"];
    $lineNums = [];
    $lineNumsIndex = 0;
    readDiffFile($upfile, $lineNums);
    readLineDiffFile($upfile, $mapping, $mappingCount);
    
    $fileName = $upfile['name'];
    $origFile = "";
    $preMap = 0;
    $origLines = @file($fileName.".orig");
    if ($origLines != FALSE) {
        $origFile = '<table><tbody id="ol1" class="l1" onClick="location.href=\'#nl1\'">';
        if (isset($lineNums[$lineNumsIndex]) && $lineNums[$lineNumsIndex] == 1)
        {
            $origFile .= "<tr><td class=\"wrong\">1</td><td>";
            $lineNumsIndex++;
        }
        else
            $origFile .= "<tr><td>1</td><td>";
        $origFile .= "<pre>".htmlspecialchars($origLines[0])."</pre></td></tr>";
        for ($i = 1; $i < count($origLines); ++$i)
        {
            $index = $i + 1;
            if (isset($mapping[$index]) && $mapping[$index-1] != $mapping[$index])
            {
                $origFile .= "</tbody><tbody id=\"ol".$mapping[$index]."\"";
                $origFile .= " class=\"l".$mapping[$index]."\"";
                $origFile .= " onClick=\"location.href='#nl".$mapping[$index]."'\">";
            }
            $origLines[$i] = htmlspecialchars($origLines[$i]);
            if (isset($lineNums[$lineNumsIndex]) && $lineNums[$lineNumsIndex] == $index)
            {
                $origFile .= "<tr><td class=\"wrong\">".(string)$index."</td><td>";
                $lineNumsIndex++;
            }
            else
                $origFile .= "<tr><td>".(string)$index."</td><td>";
            $origFile .= "<pre>".$origLines[$i]."</pre></td></tr>";
        }
        $origFile .= "</table>";
        unlink($fileName.".orig");
    }
    
    $newFile = "";
    $newLines = @file($fileName);
    if ($newLines != FALSE) {
        $newFile = '<table><tbody id="nl1" class="l1" onClick="location.href=\'#ol1\'">';
        $newFile .= "<tr><td>1</td><td><pre>".htmlspecialchars($newLines[0])."</pre></td></tr>";
        for ($i = 1; $i < count($newLines); ++$i)
        {
            $index = $i + 1;
            if (isset($mappingCount[$index]) && intval($mappingCount[$index]) != 0)
            {
                $newFile .= "</tbody><tbody id=\"nl".(string)$index."\" class=\"l".(string)$index."\"";
                $newFile .= " onClick=\"location.href='#ol".(string)$index."'\">";
            }
            $newLines[$i] = htmlspecialchars($newLines[$i]);
            $newFile .= "<tr><td>".(string)$index."</td><td><pre>".$newLines[$i]."</pre></td></tr>";
        }
        $newFile .= "</tbody></table>";
    }
}

function indentCheckAndReadFiles($upfile, $style, &$origFile, &$newFile)
{
    indentCheck($upfile, $style);
    readFiles($upfile, $origFile, $newFile);
}

function fullStyleName($style) {
    if ($style == "allman")
        return "Allman";
    if ($style == "java")
        return "Java";
    if ($style == "kr")
        return "Kernighan &amp; Ritchie";
    if ($style == "stroustrup")
        return "Stroustrup";
    if ($style == "Whitesmith")
        return "whitesmith";
    if ($style == "vtk")
        return "Visualization Toolkit";
    if ($style == "ratliff")
        return "Ratliff";
    if ($style == "gnu")
        return "GNU";
    if ($style == "linux")
        return "Linux";
    if ($style == "horstmann")
        return "Horstmann";
    if ($style == "1tbs")
        return "One True Brace Style";
    if ($style == "google")
        return "Google";
    if ($style == "mozilla")
        return "Mozilla";
    if ($style == "pico")
        return "Pico";
    if ($style == "lisp")
        return "Lisp";
                                            
    return "default";
}

?>