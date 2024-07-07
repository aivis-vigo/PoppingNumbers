<?php declare(strict_types=1);

namespace App\App;
// Adjust the namespace as per your project structure

use App\Utils\Console;
use App\Utils\Colors;

class GameBoard
{
    private int $width,
        $height,
        $cellCount,
        $predictedRow,
        $predictedColumn;
    private bool $preventRandomNumber;
    private array $board;
    private int $playerScore = 0;
    private int $opponentScore = 0;

    public function __construct(int $height, int $width)
    {
        $this->height = $height;
        $this->width = $width;
        $this->cellCount = $height * $width;
        $this->predictedRow = -1;
        $this->predictedColumn = -1;
        $this->preventRandomNumber = false;

        $this->initializeBoard();
    }

    public function initializeBoard(): void
    {
        $this->board = array_fill(0, $this->height, array_fill(0, $this->width, ' '));
    }

    public function generate(): void
    {
        for ($i = 0; $i < $this->height; $i++) {
            for ($j = 0; $j < $this->width; $j++) {
                $value = $this->board[$i][$j];
                $color = Colors::getColorForNumber($value);
                Console::printColored(' ' . $value . ' |', $color);
            }
            echo PHP_EOL;

            if ($i < $this->height - 1) {
                echo str_repeat('---+', $this->width - 1) . '---' . PHP_EOL;
            }
        }

        // last rows bottom
        echo str_repeat('---+', $this->width - 1) . '---' . PHP_EOL;
    }

    public function setCellValue(int $row, int $column, string $value): void
    {
        if ($row >= 0 && $row < $this->height && $column >= 0 && $column < $this->width) {
            if ($this->shouldPreventRandomNumber() && $this->isPredictedPosition($row, $column)) {
                // Prevent random number from appearing
                $this->clearPredictedPosition();
            } else {
                // Set cell value as usual
                $this->board[$row][$column] = $value;
                $this->cellCount--;
            }
        }
    }

    public function isCellTaken(int $row, int $column): bool
    {
        return $this->board[$row][$column] !== ' ';
    }

    public function checkAndClearLines(bool $isPlayerTurn): void
    {
        // Helper to clear cells and increment score
        $clearCells = function ($cells, $value) use ($isPlayerTurn) {
            $length = count($cells);
            $scoreMultiplier = 0;

            switch ($length) {
                case 3:
                    $scoreMultiplier = 100;
                    break;
                case 4:
                    $scoreMultiplier = 200;
                    break;
                case 5:
                    $scoreMultiplier = 500;
                    break;
                default:
                    break;
            }

            $scoreIncrement = $scoreMultiplier * $value;

            foreach ($cells as [$row, $col]) {
                $this->board[$row][$col] = ' ';
                $this->cellCount++;
            }

            if ($isPlayerTurn) {
                $this->playerScore += $scoreIncrement;
            } else {
                $this->opponentScore += $scoreIncrement;
            }
        };

        // Check horizontal, vertical, and diagonal lines
        for ($i = 0; $i < $this->height; $i++) {
            for ($j = 0; $j < $this->width; $j++) {
                if ($this->board[$i][$j] !== ' ' && is_numeric($this->board[$i][$j])) {
                    $num = $this->board[$i][$j];
                    $cells = [];

                    // Horizontal check
                    for ($k = $j; $k < $this->width && $this->board[$i][$k] === $num; $k++) {
                        $cells[] = [$i, $k];
                    }
                    if (count($cells) >= 3) {
                        $clearCells($cells, $num);
                    }

                    // Vertical check
                    $cells = [];
                    for ($k = $i; $k < $this->height && $this->board[$k][$j] === $num; $k++) {
                        $cells[] = [$k, $j];
                    }
                    if (count($cells) >= 3) {
                        $clearCells($cells, $num);
                    }

                    // Diagonal checks
                    // Top-left to bottom-right
                    $cells = [];
                    for ($k = 0; $i + $k < $this->height && $j + $k < $this->width && $this->board[$i + $k][$j + $k] === $num; $k++) {
                        $cells[] = [$i + $k, $j + $k];
                    }
                    if (count($cells) >= 3) {
                        $clearCells($cells, $num);
                    }

                    // Bottom-left to top-right
                    $cells = [];
                    for ($k = 0; $i - $k >= 0 && $j + $k < $this->width && $this->board[$i - $k][$j + $k] === $num; $k++) {
                        $cells[] = [$i - $k, $j + $k];
                    }
                    if (count($cells) >= 3) {
                        $clearCells($cells, $num);
                    }
                }
            }
        }
    }

    public function getCellCount(): int
    {
        return $this->cellCount;
    }

    public function getPlayerScore(): int
    {
        return $this->playerScore;
    }

    public function getOpponentScore(): int
    {
        return $this->opponentScore;
    }

    public function getFreeSpaces(): array
    {
        $freeSpaces = [];
        for ($i = 0; $i < $this->height; $i++) {
            for ($j = 0; $j < $this->width; $j++) {
                if ($this->board[$i][$j] === ' ') {
                    $freeSpaces[] = [$i, $j];
                }
            }
        }
        return $freeSpaces;
    }

    public function setPredictedPosition(int $row, int $column): void
    {
        $this->predictedRow = $row;
        $this->predictedColumn = $column;
        $this->preventRandomNumber = true;
    }

    public function clearPredictedPosition(): void
    {
        $this->predictedRow = -1;
        $this->predictedColumn = -1;
        $this->preventRandomNumber = false;
    }

    public function isPredictedPosition(int $row, int $column): bool
    {
        return $this->predictedRow === $row && $this->predictedColumn === $column;
    }

    public function shouldPreventRandomNumber(): bool
    {
        return $this->preventRandomNumber;
    }
}
