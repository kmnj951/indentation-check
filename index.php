<?php 
include ('indent_check.php');
$origFile = "";
$newFile = "";
if (isset($_FILES['upfile']) && $_FILES['upfile']['name'] != "")
{
    $options = [$_POST['style'], $_POST['space'], $_POST['padOp'], $_POST['padParen'], $_POST['padHeader']];
    if($_FILES['upfile']['size'] >= 5242880)
        echo "<script>alert('Max size : 5MB');</script>";
    else
        indentCheckAndReadFiles($_FILES['upfile'], $options, $origFile, $newFile);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>indent check</title>
        <link rel="stylesheet" href="style.css">
        <script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
        <script>
            $(document).ready(function(){
                $(".grid tbody").hover(
                    function () {
                        var tbodyClass = $(this).attr("class");
               	        $("." + tbodyClass).css("background-color", "whitesmoke");   		
                    },  
                    function () {
                        var tbodyClass = $(this).attr("class");
                        $("." + tbodyClass).css("background-color", "white");
               	    });
                $(".grid tbody").hover(
                    function () {
                        var tbodyClass = $(this).attr("class");
               	        $("." + tbodyClass + " pre").css("background-color", "gainsboro");   		
                    },  
                    function () {
                        var tbodyClass = $(this).attr("class");
                        $("." + tbodyClass + " pre").css("background-color", "white");
               	    });
            });
        </script>
    </head>
    <body>
        <div class="header">
            <div class="wrapper"></div>
        </div>
        <div class="contents">
            <div class="wrapper">
                <form action="/" method="post" enctype="multipart/form-data">
                    <input type="file" name="upfile">
                    <input type="submit" value="indent check">
                    <div>
                        <span class="styleLabel">Style:</span> 
                        <select name="style">
                            <option value="default" selected>default</option>
                            <option value="allman">Allman</option>
                            <option value="java">Java</option>
                            <option value="kr">Kernighan &amp; Ritchie</option>
                            <option value="stroustrup">Stroustrup</option>
                            <option value="Whitesmith">Whitesmith</option>
                            <option value="vtk">Visualization Toolkit</option>
                            <option value="ratliff">Ratliff</option>
                            <option value="gnu">GNU</option>
                            <option value="linux">Linux</option>
                            <option value="horstmann">Horstmann</option>
                            <option value="1tbs">One True Brace Style</option>
                            <option value="google">Google</option>
                            <option value="mozilla">Mozilla</option>
                            <option value="pico">Pico</option>
                            <option value="lisp">Lisp</option>
                        </select>
                        <span class="spaceLabel">Spaces:</span> 
                        <select name="space">
                            <option value="s2">2</option>
                            <option value="s4" selected>4</option>
                            <option value="s8">8</option>
                        </select>
                    </div>
                    <div>
                        <span class="padOpLabel">Padding around operators:</span> 
                        <select name="padOp">
                            <option value="" selected>none</option>
                            <option value="-p ">operators</option>
                            <option value="-xg ">just comma</option>
                        </select>
                        <span class="padParenLabel">Padding around parentheses:</span> 
                        <select name="padParen">
                            <option value="" selected>none</option>
                            <option value="-P ">all paren</option>
                            <option value="-d ">just outside</option>
                            <option value="-xd ">just left outside</option>
                            <option value="-D ">just inside</option>
                        </select>
                        <span class="padHeaderLabel">Padding between header(if, while..) and paren:</span> 
                        <select name="padHeader">
                            <option value=""selected>none</option>
                            <option value="--pad-header ">True</option>
                        </select>
                    </div>
                    <ul class="grid">
                        <li>
                            <div class="text"><?php 
                                if (isset($_FILES['upfile']))
                                    echo $_FILES['upfile']['name'];
                                ?></div>
                            <div class="output"><?php
                                echo $origFile; 
                                ?></div>
                        </li>
                        <li>
                            <div class="text"><?php 
                                if (isset($_POST['style']))
                                    if ($_POST['style'] == "default")
                                        echo 'Style :  <a href="http://astyle.sourceforge.net/astyle.html#_default_brace_style" target="_newtab">'.fullStyleName($_POST['style'])."</a>";
                                    else
                                        echo 'Style :  <a href="http://astyle.sourceforge.net/astyle.html#_style='.$_POST['style'].'" target="_newtab">'.fullStyleName($_POST['style'])."</a>";
                                ?></div>
                            <div class="output"><?php
                                echo $newFile;
                                ?></div>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
        <div class="footer">
            <div class="wrapper"></div>
        </div>
    </body>
</html>