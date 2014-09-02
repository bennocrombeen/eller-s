<?php
/**
 * Created by PhpStorm.
 * User: benno
 * Date: 9/1/14
 * Time: 9:39 PM
 */

namespace Maze\Eller;


use Maze\Output\ConsoleInterface;

class MazeCreator
{

    /**
     * @var array - To keep track of all the sets, and which cells are in
     */
    protected $sets = array();

    /**
     * @var array - To keep track of the cells and in which set they are
     */
    protected $cells = array();

    /**
     * @var int - Maze width
     */
    protected $width;

    /**
     * @var int - Maze height
     */
    protected $height;

    /**
     * @var int - internal counter, for adding last closing row
     */
    protected $counter = 0;


    /**
     * @var \Maze\Output\ConsoleInterface
     */
    protected $output;


    public function __construct($width, $height, ConsoleInterface $output)
    {
        $this->width = $width;
        $this->height = $height;
        $this->output = $output;
    }


    /**
     * start me this shizzle
     */
    public function start()
    {
        $this->output->drawTop($this->width);

        $row = new MazeRow($this->width, -1);
        $row->populate();

        $this->step($row);
    }

    /**
     * Heavy lifting, keep track of amount of rows we need to do and make it happen
     * @param MazeRow $row
     */
    public function step(MazeRow $row)
    {
        $connected_sets = [];
        $connected_set = [0];

        $this->counter++;

        for($j = 0; $j < $this->width - 1; $j++){

            $same = $row->same($j, $j+1);

            //let's randomly decide if we will connect the adjacent cells
            if($same || rand(0, 1) > 0){
                $connected_sets[] = $connected_set;
                $connected_set = [$j + 1];
            } else {
                //merge with other cell
                $row->merge($j, $j + 1);
                $connected_set[] = $j + 1;
            }
        }

        $connected_sets[] = $connected_set;

        //sort the row sets
        $row->sort();

        //lets keep track of vertical passages we gonna need
        $verticals = array();

        $nextRow = $row->next();


        //we need at least one vertical passage to prevent dead ends
        foreach($row->getSets() as $index => $set){
            $addable = array();

            //random pick them
            $items = array_rand($set, rand(1, count($set)));
            if(is_array($items)){
                foreach($items as $k => $v){
                    $verticals[] = $set[$v];
                    $addable[] = $set[$v];
                }
            } else {
                $verticals[] = $set[$items];
                $addable[] = $set[$items];
            }

            //add the chosen ones to the next row
            foreach($addable as $add){
                $nextRow->add($add, $index);
            }
        }

        //let the output draw the row
        $this->output->drawRow($this->createBitmapFromSet($connected_sets, $verticals));

        //this is the bottom, create ugly end, to fix later (yeah whatever)
        if($this->counter == ( $this->height - 1) ){
            $this->output->drawBottom($this->width);
            //ugliest fix but better ending
            return;
        }

        //populate the next row, to fill the empty cells/sets
        $nextRow->populate();

        //you know the drill, keep stepping till we done
        if($this->counter < $this->height){
            $this->step($nextRow);
        }
    }


    protected function createBitmapFromSet($connected_sets, $verticals)
    {
        $bitmap = [];
        foreach($connected_sets as $conn_set){
            foreach($conn_set as $index => $cell){
                $last = (count($conn_set) == $index + 1);
                $map = $last ? 0 : MazeCell::E;

                if(in_array($cell, $verticals)){
                    $map |= MazeCell::S;
                }
                $bitmap[] = $map;
            }
        }
        return $bitmap;
    }


} 