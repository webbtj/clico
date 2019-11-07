<?php

namespace Webbtj\Clico;

use Webbtj\Clico\Column;
use Webbtj\Clico\Text;

/**
 * The Clico Row class is utilized by the Table class.
 * 
 * It souldn't need to be accessed directly. It it used to fill Tables.
 */
class Row
{
    private $columns = [];
    private $distributedWidth = 0;
    private $headerLineCharacter;
    private $height;
    private $isHeader = false;
    private $pipeCharacter;
    private $rowLineCharacter;

    /**
     * Constructor. Can optionally set the values of the columns
     *
     * @param Array $data
     */
    public function __construct(Array $data = null)
    {
        if($data){
            $this->populate($data);
        }

        $this->unsetHeader();
    }

    /**
     * Renders the columns, decorated.
     *
     * @return string
     */
    public function __toString()
    {
        $output = "";
        $dashLine = '';
        for($i = 0; $i < $this->height; $i++){
            foreach($this->columns as $column){
                $output .= $column->getLine($i) . $this->pipeCharacter;

                if($i == 0){
                    $dashLine .= $this->getLineCharacter()->repeat($column->getWidth() - 1) . $this->getPipeCharacter();
                }
            }
            $output .= "\n";
        }
        if($dashLine){
            $output .= $dashLine . "\n";
        }
        return $output;
    }

    /**
     * Adds a column to the Row
     *
     * @param Column $column
     * @return void
     */
    public function addColumn(Column $column)
    {
        $this->columns[] = $column;
    }

    /**
     * Sets the widths of each column to fit within `$maxWidth`
     *
     * @param integer $maxWidth
     * @param integer $numCols
     * @return void
     */
    public function distributeColumns(int $maxWidth, int $numCols)
    {
        $this->distributedWidth = 0;
        $avg = $maxWidth / $numCols;
        foreach($this->columns as $k => &$column){
            $width = floor($avg);
            //while already distributed width plus this width is less than (k+1) * avg
            while( ($this->distributedWidth + $width) < (($k+1) * $avg) ){
                $width++;
            }
            $this->distributedWidth += $width;
            $column->setWidth($width);
        }
    }

    /**
     * Get the columns
     *
     * @return Array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Get the character (decorated) used to separate the header row from the body
     *
     * @return Text
     */
    public function getHeaderLineCharacter()
    {
        return $this->headerLineCharacter;
    }

    /**
     * Get the character (decorated) used to separate this row from other.
     * This will return either the `headerLineCharacter` or the `rowLineCharacter`
     *
     * @return Text
     */
    public function getLineCharacter()
    {
        return $this->isHeader ? $this->getHeaderLineCharacter() : $this->getRowLineCharacter();
    }

    /**
     * Get the character (decorated) used to separate columns
     *
     * @return Text
     */
    public function getPipeCharacter()
    {
        return $this->pipeCharacter;
    }

    /**
     * Get the character (decorated) used to separate body rows
     *
     * @return Text
     */
    public function getRowLineCharacter()
    {
        return $this->rowLineCharacter;
    }

    /**
     * Get the number of columns wide
     *
     * @return integer
     */
    public function getWidth()
    {
        return count($this->columns);
    }

    /**
     * Check if this Row is the header row
     *
     * @return boolean
     */
    public function isHeader()
    {
        return $this->isHeader;
    }

    /**
     * Put data into Columns
     *
     * @param Array $data
     * @return void
     */
    public function populate(Array $data)
    {
        foreach($data as $column){
            $this->addColumn(new Column($column));
        }
    }

    /**
     * Set this Row as the Table header
     *
     * @return void
     */
    public function setHeader()
    {
        $this->isHeader = true;
    }

    /**
     * Set the (decorated) character used to separate the header row from the body
     *
     * @param Text $line
     * @return void
     */
    public function setHeaderLineCharacter(Text $line)
    {
        $this->headerLineCharacter = $line;
    }

    /**
     * Set the (decorated) character used to separate columns
     *
     * @param Text $pipe
     * @return void
     */
    public function setPipeCharacter(Text $pipe)
    {
        $this->pipeCharacter = $pipe;
    }

    /**
     * Set the (decorated) charater used to separate body rows
     *
     * @param Text $line
     * @return void
     */
    public function setRowLineCharacter(Text $line)
    {
        $this->rowLineCharacter = $line;
    }

    /**
     * Make the row not the header
     *
     * @return void
     */
    public function unsetHeader()
    {
        $this->isHeader = false;
    }

    /**
     * Make all columns in the row the same height (stretch shorter columns)
     *
     * @return void
     */
    public function verticalConform()
    {
        $height = 1;
        foreach($this->columns as $column){
            $height = max($height, $column->getHeight());
        }
        foreach($this->columns as &$column){
            $column->verticalPad($height);
        }
        $this->height = $height;
    }

}