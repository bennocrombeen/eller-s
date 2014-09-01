<?php
/**
 * Created by PhpStorm.
 * User: benno
 * Date: 9/1/14
 * Time: 9:49 PM
 */

require_once 'Maze/Output/ConsoleInterface.php';
require_once 'Maze/Eller/MazeCreator.php';
require_once 'Maze/Eller/MazeRow.php';
require_once 'Maze/Eller/MazeCell.php';
require_once 'Maze/Eller/Output.php';


$maze = new \Maze\Eller\MazeCreator(10, 10, new \Maze\Eller\Output());
$maze->start();



$maze = new \Maze\Eller\MazeCreator(20, 20, new \Maze\Eller\Output());
$maze->start();


$maze = new \Maze\Eller\MazeCreator(50, 50, new \Maze\Eller\Output());
$maze->start();