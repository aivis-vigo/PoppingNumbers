<?php declare(strict_types=1);

use App\App\GameBoard;
use App\App\Opponent;
use App\Utils\Console;

require_once __DIR__ . '/../vendor/autoload.php';

// Player chooses how many numbers and how big the playing area will be used.
$numbers = Console::getInput("How many numbers (0-9)? : ", 0, 9);
$height = Console::getInput("Board height (5-25): ", 5, 25);
$width = Console::getInput("Board width (5-25): ", 5, 25);

$opponent = new Opponent();

// Draw board in console
$gameBoard = new GameBoard($height, $width);
$isPlayerTurn = true; // Start with player's turn

while ($gameBoard->getCellCount() > 0) {
    echo "=========================" . PHP_EOL;

    if ($isPlayerTurn) {
        // player's turn
        Console::printColored("Player's Turn\n", 'green');
        do {
            $myNumber = Console::getInput("Choose number (0-{$numbers}): ", 0, 9);
            $myRow = Console::getInput("Choose row (1-{$height}) : ", 1, $height) - 1; // Convert to zero-based index
            $myColumn = Console::getInput("Choose column (1-{$width}) : ", 1, $width) - 1; // Convert to zero-based index

            if (!$gameBoard->isCellTaken($myRow, $myColumn)) {
                $gameBoard->setCellValue($myRow, $myColumn, (string)$myNumber);
                $gameBoard->setPredictedPosition($myRow, $myColumn);
                break;
            } else {
                echo "That cell is taken!" . PHP_EOL;
            }
        } while (true);

    } else {
        // opponent's turn
        Console::printColored("Opponent's Turn\n", 'red');
        $freeSpaces = $gameBoard->getFreeSpaces();
        if (count($freeSpaces) < 3) {
            foreach ($freeSpaces as [$val1, $val2]) {
                $oppNum = $opponent::comeUpWithNumber($numbers);
                $gameBoard->setCellValue($val1, $val2, (string)$oppNum);
            }
        } else {
            for ($i = 0; $i < 3; $i++) {
                $oppNum = $opponent::comeUpWithNumber($numbers);
                do {
                    $val1 = rand(0, $height - 1);
                    $val2 = rand(0, $width - 1);
                    if (!$gameBoard->isCellTaken($val1, $val2)) {
                        $gameBoard->setCellValue($val1, $val2, (string)$oppNum);
                        break;
                    }
                } while (true);
            }
        }

    }
    $gameBoard->checkAndClearLines($isPlayerTurn);

    // Display board with scores after each turn
    echo PHP_EOL . "Player Score: " . $gameBoard->getPlayerScore() . PHP_EOL;
    echo "Opponent Score: " . $gameBoard->getOpponentScore() . PHP_EOL;
    echo PHP_EOL;
    $gameBoard->generate();

    // Switch turns
    $isPlayerTurn = !$isPlayerTurn;
}

// Game over
echo "Game Over!" . PHP_EOL;
echo "Final Scores:" . PHP_EOL;
echo "Player: " . $gameBoard->getPlayerScore() . PHP_EOL;
echo "Opponent: " . $gameBoard->getOpponentScore() . PHP_EOL;

// Determine winner
if ($gameBoard->getPlayerScore() > $gameBoard->getOpponentScore()) {
    echo "Player wins!" . PHP_EOL;
} elseif ($gameBoard->getOpponentScore() > $gameBoard->getPlayerScore()) {
    echo "Opponent wins!" . PHP_EOL;
} else {
    echo "It's a tie!" . PHP_EOL;
}
