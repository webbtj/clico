<?php

namespace Webbtj\Clico;

use Webbtj\Clico\Row;
use Webbtj\Clico\Text;

/**
 * The Clico Table class allows for formatting tabular data for the CLI stdOut.
 * 
 * It utilizes the Text class to allow for decorating text for the CLI stdOut.
 */
class Table
{
    private $accessColumn;
    private $accessRow;
    private $headerLineCharacter = '=';
    private $pipeCharacter = '|';
    private $rowLineCharacter = '-';
    private $rows = [];    

    /**
     * A catch-all used to send Text decoration methods down to table column(s)
     *
     * @param string $method
     * @param Array $args
     * @return Table
     */
    public function __call($method, $args)
    {
        $rows = $this->rows;
        if(!is_null($this->accessRow) && isset($rows[$this->accessRow])){
            $rows = [$rows[$this->accessRow]];
        }
        foreach($rows as &$row){
            $columns = $row->getColumns();
            if(!is_null($this->accessColumn) && isset($columns[$this->accessColumn])){
                $columns = [$columns[$this->accessColumn]];
            }
            foreach($columns as $column){
                call_user_func_array([$column->getTextWriter(), $method], $args);
            }
        }
        return $this;
    }

    /**
     * Constructor. Can optionally populate the data. Can optionally set the
     * first row of data as the header row.
     *
     * @param Array $data
     * @param boolean $firstRowAsHeader
     */
    public function __construct(Array $data = null, $firstRowAsHeader = true)
    {
        $this->setRowLineCharacter($this->rowLineCharacter, false);
        $this->setHeaderLineCharacter($this->headerLineCharacter, false);
        $this->setPipeCharacter($this->pipeCharacter, false);

        if($data){
            $this->populate($data);
            if($firstRowAsHeader){
                $this->setFirstRowAsHeader();
            }
        }
    }

    /**
     * Render the entire table using Text instances
     *
     * @return string
     */
    public function __toString(){
        $this->pushCharactersToRows();
        $output = "";
        foreach($this->rows as $row){
            $output .= $row;
        }
        return $output;
    }

    /**
     * Add a header row. Unsets the previous header row.
     *
     * @param Array $row
     * @return Table
     */
    public function addHeader(Array $row)
    {
        foreach($this->rows as $r){
            if($r->isHeader()){
                $r->unsetHeader();
            }
        }
        if(is_array($row)){
            $row = new Row($row);
            $row->setHeader();
            $this->rows = array_merge([$row], $this->rows);
            $this->populate();
        }
        return $this;
    }

    /**
     * Add a row
     *
     * @param Row $row
     * @return Table
     */
    public function addRow(Row $row)
    {
        $this->rows[] = $row;
        $this->populate();
        return $this;
    }

    /**
     * Target a specific cell for text decoration
     *
     * @param integer $column
     * @param integer $row
     * @return Table
     */
    public function cell(int $column, int $row)
    {
        return $this->row($row)->column($column);
    }

    /**
     * Target an entire column for text decoration
     *
     * @param integer $index
     * @return Table
     */
    public function column(int $index = null)
    {
        $this->accessColumn = $index;
        return $this;
    }

    /**
     * Fit all columns to the table (width and height)
     *
     * @param integer $maxWidth
     * @return Table
     */
    public function distributeColumns($maxWidth = 160)
    {
        foreach($this->rows as &$row){
            $row->distributeColumns($maxWidth, $this->getWidth());
            $row->verticalConform();
        }
        return $this;
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
     * Get the number of rows high the table is
     *
     * @return void
     */
    public function getHeight()
    {
        return count($this->rows);
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
     * Get the number of columns wide the table is
     *
     * @return integer
     */
    public function getWidth()
    {
        $width = 0;
        foreach($this->rows as $row){
            $width = max($width, $row->getWidth());
        }
        return $width;
    }

    /**
     * Reformat Rows' and Columns' widths, heights, and separation characters as necessary.
     * Optionally add rows of data to the table
     *
     * @param Array $data
     * @return Table
     */
    public function populate(Array $data = null)
    {
        if($data){
            foreach($data as $row){
                $this->addRow(new Row($row));
            }
        }
        $this->pushCharactersToRows();
        $this->distributeColumns();

        return $this;
    }

    /**
     * Target an entire row for text decoration
     *
     * @param integer $index
     * @return Table
     */
    public function row(int $index = null)
    {
        $this->accessRow = $index;
        return $this;
    }

    /**
     * Make the first row the header row
     *
     * @return Table
     */
    public function setFirstRowAsHeader()
    {
        foreach($this->rows as $k => $r){
            if($r->isHeader()){
                unset($this->rows[$k]);
            }
        }

        if(isset($this->rows[0])){
            $this->rows[0]->setHeader();
        }

        return $this;
    }

    /**
     * Set the character used to separate the header row from the body
     *
     * @param String $char
     * @param boolean $pushToRows
     * @return Table
     */
    public function setHeaderLineCharacter(String $char, bool $pushToRows = true)
    {
        $this->headerLineCharacter = new Text(substr($char, 0, 1));
        if($pushToRows){
            $this->pushCharactersToRows();
        }

        return $this;
    }

    /**
     * Set the (decorated) character used to separate columns
     *
     * @param String $char
     * @param boolean $pushToRows
     * @return Table
     */
    public function setPipeCharacter(String $char, bool $pushToRows = true)
    {
        $this->pipeCharacter = new Text(substr($char, 0, 1));
        if($pushToRows){
            $this->pushCharactersToRows();
        }

        return $this;
    }

    /**
     * Set the charater used to separate body rows
     *
     * @param String $char
     * @param boolean $pushToRows
     * @return Table
     */
    public function setRowLineCharacter(String $char, bool $pushToRows = true)
    {
        $this->rowLineCharacter = new Text(substr($char, 0, 1));
        if($pushToRows){
            $this->pushCharactersToRows();
        }

        return $this;
    }

    /**
     * Push the separation characters down to the rows
     *
     * @return void
     */
    private function pushCharactersToRows()
    {
        foreach($this->rows as &$row){
            $row->setRowLineCharacter($this->getRowLineCharacter());
            $row->setHeaderLineCharacter($this->getHeaderLineCharacter());
            $row->setPipeCharacter($this->getPipeCharacter());
        }
    }
    
}