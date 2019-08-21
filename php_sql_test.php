<?php

require_once 'C:\Program Files (x86)\Ampps\www\php_Login.php';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die('Fatal Connect Error');

if (isset($_POST['delete']) && isset($_POST['isbn']))
{
    $isbn = get_post($conn, 'isbn');
    $query = "DELETE FROM classics WHERE isbn='$isbn'";
    $result = $conn->query($query);
    if (!$result) echo "DELETE failed<br><br> $query";
}


//checks to see fields are set
if  (isset($_POST['author']) &&
     isset($_POST['title'])  &&
     isset($_POST['type'])   &&
     isset($_POST['year'])   &&
     isset($_POST['pages'])   &&
     isset($_POST['isbn']))

{
    $author = get_post($conn, 'author');
    $title  = get_post($conn, 'title');
    $type   = get_post($conn, 'type');
    $year   = get_post($conn, 'year');
    $pages  = get_post($conn, 'pages');
    $isbn   = get_post($conn, 'isbn');
    $query = "INSERT INTO classics VALUES" .
             "('$author', '$title', '$type', '$year', '$pages', '$isbn')";
    $result = $conn->query($query);
    if (!$result) echo "INSERT failed <br><br>";
    
}

echo <<<_END
<pre>
<form action='sqltest.php' method='post'>
Author <input type='text' name='author'>
Title  <input type='text' name='title'>
Type   <input type='text' name='type'>
Year   <input type='text' name='year'>
ISBN   <input type='text' name='isbn'>
Pages  <input type='text' name='pages'>
       <input type='submit' value='ADD RECORD'>
</pre></form>
_END;

$query = "SELECT * FROM classics";
$result = $conn->query($query);
if (!$result) die ("Fatal Select Error");

$rows = $result->num_rows;

for ($j=0; $j<$rows; ++$j)
{
    $row = $result->fetch_array(MYSQLI_NUM);
    
    $r0 = htmlspecialchars($row[0]);
    $r1 = htmlspecialchars($row[1]);
    $r2 = htmlspecialchars($row[2]);
    $r3 = htmlspecialchars($row[3]);
    $r4 = htmlspecialchars($row[4]);
    $r5 = htmlspecialchars($row[5]);
    
    echo
<<<_END
    <pre>
    
    Author $r0
    Title $r1
    Type $r2
    Year $r3
    Pages $r4
    ISBN $r5
    </pre>

    <form action='sqltest.php' method='post'>
    <input type='hidden' name='delete' value='yes'>
    <input type='hidden' name='isbn' value='$r5'>
    <input type='submit' value='DELETE RECORD'></form>
_END;
    }
    
    $result->close();
    $conn->close();
    
    function get_post($conn, $var)
    {
        return $conn->real_escape_string($_POST[$var]);
    }
    

?>
