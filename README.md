# Number Pop Game

Number Pop is a simple console-based game where the player competes against an opponent to strategically place numbers on a game board. The objective is to create rows of numbers that can be "popped" for points, with longer rows yielding higher scores.

## Gameplay Overview

- The game board is initialized with a specified height and width.
- Players take turns placing numbers (0-9) on the board.
- Rows of three or more consecutive numbers can be cleared ("popped") for points.
- The opponent also places numbers randomly on the board.
- The game ends when no more spaces are available on the board.
- The winner is determined by comparing final scores, with more points awarded for longer popped rows.


## Setup and Running the Game

### Prerequisites

- PHP (Version 7.4)
- Composer (for autoloading classes)

### Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/aivis-vigo/PoppingNumbers.git
   cd PoppingNumbers

2. Install dependencies using composer:
    
    ```bash
        comspoer install
    ```

3. Running the game:
    ```bash
        php src/index.php
    ```