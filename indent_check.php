<?php
function indentCheck($upfile, $style, $space, $padOp, $padParen, $padHeader)
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
        $instr .= "--style=" . $style;
    $instr .= " -" . $space . " ";
    $instr .= $padOp . $padParen . $padHeader;
    $instr .= $upfile['name'];
    exec($instr);
}

function readDiffFile($upfile, &$lineNums)
{
    $index = 0;
    $fileName = $upfile['name'] . ".txt";
    if (file_exists($fileName))
    {
        $fr = file_get_contents($fileName);
        preg_match_all("/^\sline\s\[(.*)\]\s:\s*$/m", $fr, $lineStr, PREG_PATTERN_ORDER);
        for ($i = 0; $i < count($lineStr[1]); ++$i)
        {
            preg_match_all("/\d+/", $lineStr[1][$i], $Nums);
            if (count($Nums[0]) > 1)
            {
                for ($j = (int)$Nums[0][0]; $j <= (int)$Nums[0][1]; ++$j)
                {
                    if ($index == 0 || $lineNums[$index-1] != $j)
                        $lineNums[$index++] = $j;
                }
            }
            else if ($index == 0 || $lineNums[$index-1] != (int)$Nums[0][0])
            {
                $lineNums[$index++] = (int)$Nums[0][0];
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
            $arr = explode(" ", $lines[$i]);
            $mapping[$arr[0]] = $arr[1];
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
        
        // changes
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
    else
    {
        $origLines = @file($fileName);
        if ($origLines != FALSE) {
            $origFile = '<table><tbody id="nl1" class="l1" onClick="location.href=\'#ol1\'">';
            $origFile .= "<tr><td>1</td><td><pre>".htmlspecialchars($origLines[0])."</pre></td></tr>";
            for ($i = 1; $i < count($origLines); ++$i)
            {
                $index = $i + 1;

                $origFile .= "</tbody><tbody id=\"nl".(string)$index."\" class=\"l".(string)$index."\"";
                $origFile .= " onClick=\"location.href='#ol".(string)$index."'\">";
                $origLines[$i] = htmlspecialchars($origLines[$i]);
                $origFile .= "<tr><td>".(string)$index."</td><td><pre>".$origLines[$i]."</pre></td></tr>";
            }
            $origFile .= "</tbody></table>";
        }
        $newFile = "<table><tr><td></td><td>no changes</td></tr></table>";
    }
    
}

function indentCheckAndReadFiles($upfile, $options, &$origFile, &$newFile)
{
    indentCheck($upfile, $options[0], $options[1], $options[2], $options[3], $options[4]);
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