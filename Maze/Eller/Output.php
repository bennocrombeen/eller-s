<?php
/**
 * Created by PhpStorm.
 * User: benno
 * Date: 9/1/14
 * Time: 9:54 PM
 */

namespace Maze\Eller;

use \Maze\Output\ConsoleInterface;

class Output implements ConsoleInterface
{
    public function drawRow($row)
    {
        $s = "\r|";
        foreach($row as $index => $value){
            $south = ($value & MazeCell::S) != 0;

            $next_south = (isset($row[$index + 1]) && ($row[$index + 1] & MazeCell::S != 0));

            $east = ($value & MazeCell::E != 0);

            $s .= $south ? " " : "_";

            if($east){
                $s .= (($south || $next_south) ? " " : "_");
            } else {
                $s .= '|';
            }
        }
        echo $s . PHP_EOL;
    }


    /**
     * Draws the first line
     * @param $width int - size of the maze
     */
    public function drawTop($width)
    {
        $this->straightLine($width);
    }

    public function drawBottom($width)
    {
        echo '|';
        for($i = 0; $i < (($width * 2) - 1); $i++){
            echo ' ';
        }
        echo '|' . PHP_EOL;
        $this->straightLine($width);
    }

    protected function straightLine($width)
    {
        for($z = 0; $z < (($width * 2) - 1)  ; $z++ ){
            if($z == 0){
                echo ' ';
            }
            echo '-';
        }
        echo PHP_EOL;
    }


} 