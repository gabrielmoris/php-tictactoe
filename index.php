<?php

$last = null;
$message = "";
$finished = false;
$board = [
  [3, 3, 3],
  [3, 3, 3],
  [3, 3, 3],
];

$win_conditions = [
  [[0, 0], [0, 1], [0, 2]],
  [[1, 0], [1, 1], [1, 2]],
  [[2, 0], [2, 1], [2, 2]],
  [[0, 0], [1, 0], [2, 0]],
  [[0, 1], [1, 1], [2, 1]],
  [[0, 2], [1, 2], [2, 2]],
  [[0, 0], [1, 1], [2, 2]],
  [[0, 2], [1, 1], [2, 0]],
];


function create_tile($value, $id, $finished)
{
  $o = "";
  $name = "row_" . $id;
  $realValue = null;

  if ($value == 0) {
    $realValue = "O";
  } else if ($value == 1) {
    $realValue = "X";
  } else {
    $realValue = " ";
  }
  if ($value == 0 || $value == 1 || $finished) {
    $o .= "<input type='hidden' name='" . $name . "' value='" . $realValue . "'/>";
    $o .= "<select class='disabled' disabled='disabled'>";
  } else {
    $o .= "<select name='" . $name . "'>";
  }
  $o .= "<option> </option>";
  if ($value == 0) {
    $o .= "<option selected='selected'>O</option>";
  } else {
    $o .= "<option class='o'>O</option>";
  }
  if ($value == 1) {
    $o .= "<option selected='selected'>X</option>";
  } else {
    $o .= "<option class='x'>X</option>";
  }
  $o .= "</select>";
  return $o;
};

function check_winner($conditions, $response)
{
  $lr = null;
  $matches = [];
  for ($j = 0; $j <= count($conditions) - 1; $j++) {
    foreach ($conditions[$j] as $rows) {
      $x = $rows[0];
      $y = $rows[1];
      if ($response[$x][$y] != 3) {
        if ($lr == $response[$x][$y] || $lr == null) {
          $matches[] = $response[$x][$y];
          $lr = $response[$x][$y];
        } else {
          $lr = null;
          $matches = [];
          continue;
        }
      }
    }
    if (count($matches) == 3) {
      if ($matches[0] == $matches[1] && $matches[1] == $matches[2]) {
        return true;
      } else {
        $matches = [];
        $lr = null;
      }
    } else {
      $matches = [];
      $lr = null;
    }
  }
  return false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['play'])) {
  $board = isset($_POST['board']) ? json_decode($_POST['board']) : [];
  $last = isset($_POST['last']) ? $_POST['last'] : null;
  $responses = [];
  $rowarray = [];
  $counter = 0;

  foreach ($_POST as $key => $value) {
    if (!in_array($key, ["board", "play", "last"])) {
      if ($value == 'O') {
        // echo $value;
        $rowarray[] = 0;
      } else if ($value == 'X') {
        $rowarray[] = 1;
      } else {
        $rowarray[] = 3;
      }
      $counter++;
      if ($counter % 3 == 0) {
        $responses[] = $rowarray;
        $rowarray = [];
      }
    }
  }

  $changes = [];

  for ($i = 0; $i <= count($board) - 1; $i++) {
    foreach ($board[$i] as $key => $value) {
      if ($value != $responses[$i][$key]) {
        $changes[] = $responses[$i][$key];
      }
    }
  }

  if (count($changes) > 1) {
    $message .= "You can't play twice times in a row.";
  } else if ($last != null && $last == $changes[0]) {
    $message .= "You can't play twice times in a row.";
  } else if (check_winner($win_conditions, $responses)) {
    $last = $changes[0];
    $board = $responses;
    $message .= "WINNER!! \n";
    $message .= "The winner is: " . ($last == 0 ? "O" : "X") . "";
    $finished = true;
  } else {
    $last = $changes[0];
    $board = $responses;
  };
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
  <h3 class="fancy-title">Tic Tac Toe Game</h3>
  <?php if ($message) : ?>
    <p class="message"><?php echo $message; ?></p>
  <?php endif; ?>
  <form method="post">
    <input name="board" type="hidden" value="<?php echo json_encode($board); ?>" />
    <input name="last" type="hidden" value="<?php echo $last; ?>" />
    <table class="table">
      <?php $count = 1;
      foreach ($board as $row) : ?>
        <tr>
          <?php foreach ($row as $tile) : ?>
            <td>
              <?php echo create_tile($tile, $count, $finished); ?>
            </td>
          <?php $count++;
          endforeach; ?>
        </tr>
      <?php endforeach; ?>
    </table>
    <div class="btns-wrapper">
      <button name="restart">Restart</button>
      <button name="play">End Turn</button>
    </div>
  </form>
</body>

</html>