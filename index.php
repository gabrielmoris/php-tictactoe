<?php

$board = [
  [3, 3, 3],
  [3, 3, 3],
  [3, 3, 3],
];


function create_tile($value, $id)
{
  $o = "";
  $name = "row_" . $id;
  $realValue = null;

  if ($value == 0) {
    $realValue = "O";
  } else if ($value == 1) {
    $realValue == "X";
  } else {
    $realValue == "Select";
  }


  if ($value == 0 || $value == 1) {
    $o .= "<input type='hidden' name='" . $name . "' value='" . $realValue . "' />";
    $o .= "<select disabled='disabled'>";
  } else {
    $o .= "<select name='" . $name . "'>";
  }

  $o .= "<option>Select</option>";

  if ($value == 0) {
    $o .= "<option selected='selected'>O</option>";
  } else {
    $o .= "<option>O</option>";
  }
  if ($value == 1) {
    $o .= "<option selected='selected'>X</option>";
  } else {
    $o .= "<option>X</option>";
  }
  $o .= "</select>";

  return $o;
};

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['play'])) {
  $board = isset($_POST['board']) ? json_encode($_POST['board']) : [];
  $responses = [];
  $rowarray = [];
  $counter = 0;

  foreach ($_POST as $key => $value) {
    if (!in_array($key, ["board", "play"])) {
      if ($key == "O") {
        $rowarray[] = 0;
      } else if ($key == "X") {
        $rowarray[] = 1;
      } else {
        $rowarray[] = 3;
      }
    }
    $counter++;
    if ($counter % 3 == 0) {
      $responses[] = $rowarray;
      $rowarray = [];
    }
    $board = $responses;

    $changes = [];
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tic Tac Toe with PHP</title>
  <link rel="stylesheet" href="styles.css" />
</head>

<body>
  <h3>Tic Tac Toe Game</h3>
  <br />
  <form method="post">
    <input name="board" type="hidden" value="<?php echo json_encode($board); ?>" />
    <table class="table">
      <?php $count = 1;
      foreach ($board as $row) : ?>
        <tr>
          <?php foreach ($row as $tile) : ?>
            <td>
              <?php echo create_tile($tile, $count); ?>
            </td>
          <?php $count++;
          endforeach; ?>
        </tr>
      <?php endforeach; ?>
    </table>
    <button name="play">End Turn</button>
  </form>
</body>

</html>