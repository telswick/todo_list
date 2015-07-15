<?php
/**
 * Created by PhpStorm.
 * User: Traci
 * Date: 7/5/2015
 * Time: 5:03 PM
 */

// HW 8
// 1. Modify your todo list to use a table when displaying the list items.
// 2. Add a date column to your todo_list table. When a todo item is added, save the current
// date/time in the database along with the todo item. Display it in the list. The PHP
// date() function will be helpful.

//copied php code to connect to database, initialize


session_start();

$db = new mysqli("localhost", "root", "root", "intro_to_php");    //where, user, password, name of database
if ($db->connect_errno) {
    echo "Failed to connect to MySQL :( <br>";
    echo $db->connect_error;
    exit();

}

// making a change to check tracking

//php has to use external sql to store persistent data

// need to do some stuff at command line first
// ALTER TABLE todo_list ADD assigndate date;
// ALTER TABLE todo_list change assigndate assigndate text;
// to add a second column and then to change the type for the date to text/string

//or in mysql command
// DROP TABLE todo_list;   to delete table
// OR
// $db->query"DROP TABLE todo_list");
// and then build new table with two columns
// CREATE TABLE todo_list (id INT, item TEXT, date TEXT)  with the  not null and autoincrement and primary key

//use prepare to pass in variables, use query if no variables
//add in 2nd column assigndate
//mysql has function NOW(), could use this for second ?
//at mysql> SELECT NOW(); to see the current date

$stmt = $db->prepare("INSERT INTO todo_list (item, assigndate) VALUES (?, ?)");
echo $db->error;

if (!isset($_SESSION['list'])) {
    $_SESSION['list'] = array();
}

if (isset($_POST['submit'])) {
    // add the text in 'todo'
    // to the SESSION variable 'list'
    // array_push($_SESSION['list'],
    //            $_POST['todo']);

    //$stmt = $db->prepare("INSERT INTO todo_list ('item', 'assigndate') VALUES (?, ?)");   //get ready to insert some stuff soon, can have multiple ?s
    //$stmt = $db->prepare("INSERT INTO todo_list (item) VALUES (?)");
// ? - things we want to substitute into
// i - integer
// d - type double
// s - type string

    $mydate =  date("d F Y H:i:s");
    $todo = $_POST['todo'];


    // $stmt->bind_param("s", $todo);
    $stmt->bind_param("ss", $todo, $mydate);   //substitute this string for the ? take away any malicious code in string, "ssss", $item, $date
    // $stmt->close();

//actually run statement with the parameters we've substituted, how to add a row

    $stmt->execute();
}

?>

<form action="hw8.php"
      method="POST">

    <input type="text" name="todo">
    <input type="submit" name="submit">

</form>

<ul>
    <?php
    $sql = "SELECT * FROM todo_list";
    $result = $db->query($sql);

    if ($result)  {

        // foreach ($result as $row)  {
         //   echo $row['item'] . "<br>";

         // insert table stuff here?



    //tr starts new row, td for each column in row

    //can use echo for table tags, \n produces next line in source code. br tag makes new line in display

    ?>

    <table border="1"> <!-- start a table -->
    <tr> <!-- first row -->
    <th>To Do Item</th> <!-- header -->
    <th>Date Added</th>
    </tr> <!-- end first row -->
    <tr> <!-- second row -->
        <?php foreach ($result as $row)  {  ?>
    <tr>
    <td><?php echo ($row['item']);            ?></td>
    <td><?php echo ($row['assigndate']);   }  ?></td>
    </tr> <?php  }  ?>
        <!-- end second row -->
    <tr> <!-- third row -->


    </tr> <!-- end third row -->
    </table> <!-- end the table -->


    <?php

    // echo "<tr><td>$row[item]</td><td>$row[assigndate]</td><td>\n";



      //else {
      //  echo $db->error;
    //}

    ?>
