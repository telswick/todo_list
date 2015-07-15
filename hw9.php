<?php
/**
 * Created by PhpStorm.
 * User: Traci
 * Date: 7/8/2015
 * Time: 10:32 PM
 *
 * HW9:  Implement item deletion in your todo list program. Use the concepts we discussed in class -- a
DELETE SQL statement and an <input type="hidden"> in each row of the list containing the
row's ID.
 */

// making a change here to verify tracking with git

// put a form in each row with the delete button

$db = new mysqli("localhost", "root", "root", "intro_to_php");
if ($db->connect_errno) {
    echo "Failed to connect to MySQL :(<br>";
    echo $db->connect_error;
    exit();
}

//goes back to the name attribute of the button
// $_POST['delete'} or $_POST['submit']
// in chrome, right click "then inspect element" to see whats going on
// now write sql statement to plug into prepare
// mysqli php documentation
// id is an integer
// $delStatement  is an mysqli statement, which does have a bind_param method, just going from $db wouldn't work
// becomes a mysqli statement by using prepare, prepare makes it

// think about what type is returning or expected, because php doesn't force you to think about it, weakly-typed
// java, c++ are strongly-typed

if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $delStatement = $db->prepare("DELETE FROM todo_list WHERE id = ?");

    $delStatement->bind_param("i", $_POST['id']);

    $delStatement->execute();
}

if (isset($_POST['submit'])) {

    // add the text in 'todo'
    // to the SESSION variable 'list'
    $stmt = $db->prepare("INSERT INTO todo_list (item, assigndate) VALUES (?, ?)");

    $now = date("d F Y");

    $stmt->bind_param("ss", $_POST['todo'], $now);

    // Actually run the statement with the parameters we've substituted
    $stmt->execute();
}

?>

    <form action="hw9.php"
          method="POST">
        Enter your to do items....<br>
        <input type="text" name="todo">
        <input type="submit" name="submit">

    </form>
    <ul>
        <?php
        $sql = "SELECT * FROM todo_list";
        $result = $db->query($sql);
        echo "<table border='1'>\n";
        foreach ($result as $row) {
            echo "<tr><td>$row[item]</td><td>$row[assigndate]</td><td>\n";

            echo "<form action='hw9.php' method='POST'>";
            echo "<input type='hidden' name='id' value='$row[id]'>";
            echo "<input type='submit' name='delete' value='delete'>";
            echo "</form></td></tr>";
        }
        echo "</table>\n";
        ?>
    </ul>

<?php
// td creates new cell in a row
// the name of the input tag attribute, for any given name attribute corresponds to exact same thing
// in post array $_POST['delete']
// each row in table has an id, set to value of id input element, to get at id of to do item we
// want to delete from our php
// every time row is added, autoincrement gives each row an id
//  in the POST array with a key of id