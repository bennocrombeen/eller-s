<?php
/**
 * Created by PhpStorm.
 * User: benno
 * Date: 9/1/14
 * Time: 9:39 PM
 */

namespace Maze\Eller;


class MazeRow
{

    /**
     * @var array - keep track of the sets
     */
    protected $sets = array();

    /**
     * @var array - keep track of the cells
     */
    protected $cells = array();

    /**
     * @var int - width of this row
     */
    protected $width;

    /**
     * @var int - incremental set tracker
     */
    protected $nextSet;


    public function __construct($width, $nextSet = -1)
    {
        $this->width = $width;

        $this->nextSet = $nextSet;
    }


    /**
     * Helper function that prevents us from using global static $nextSet parameter
     * @return MazeRow
     */
    public function next()
    {
        return new MazeRow($this->width, $this->nextSet);
    }

    function same($a, $b)
    {
        return $this->cells[$a] == $this->cells[$b];
    }


    function merge($sink_cell, $target_cell)
    {
        //TODO optimize this one
        $sink = $this->cells[$sink_cell];
        $target = $this->cells[$target_cell];


        foreach($this->sets[$target] as $value){
            $this->sets[$sink][] = $value;
            $this->cells[$value] = $sink;
        }

        unset($this->sets[$target]);
    }


    public function populate()
    {
        for($i = 0; $i < $this->width; $i++){

            $this->nextSet += 1;

            $set = $this->nextSet;

            if(!isset($this->cells[$i])){
                $this->sets[$set] = [$i];
                $this->cells[$i] = $set;
            }
        }
    }


    public function add($cell, $set)
    {
        $this->cells[$cell] = $set;
        $this->sets[$set][] = $cell;
    }


    public function sort()
    {
        ksort($this->cells);
    }

    /**
     * @param array $sets
     */
    public function setSets($sets)
    {
        $this->sets = $sets;
    }

    /**
     * @return array
     */
    public function getSets()
    {
        return $this->sets;
    }

} 