<?php
/**
 * This is simple class to generates XLS files. It only generates one sheet.
 * Here is an example
 * <code>
 * // This is the header if we need to download and execute the generated file with MS Excel. Remember to replace FILENAME.XLS
 * // header ("Cache-Control: no-cache, must-revalidate");
 * // header ("Pragma: no-cache");
 * // header ("Content-type: application/x-msexcel");
 * // header ("Content-Disposition: attachment; filename=FILENAME.XLS" );
 *
 * require("Xls_writer_model.php");
 * $xls = new Xls_writer_model();
 * $xls->write(0,0,"This is a label"); // write a label in A1, use for dates too
 * $xls->write(0,1,9999); // write a number B1
 * echo $xls->close_flush(); // close and echoes the stream
 * </code>
 * @package utility
 */

class Xls_writer_model extends CI_Model
{
    public $buffer;
	protected $_row;

    public function __construct()
    {
        // Excel begin of file header
		$this->_row = 0;
        $this->buffer = pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
    }

    public function show_headers($filename)
	{
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header('Content-Description: File Transfer');
		header("Content-type: application/x-msexcel; charset=iso-8859-1");
		header("Content-Disposition: attachment; filename=\"". $filename .".xls\"");
	}
	
	// Function to write a value into Row, Col.
    public function write($row, $col, $value)
    {
        $value = utf8_decode($value);
		// Is number
		if (is_integer($value))
        {
            $this->buffer .= pack("sssss", 0x203, 14, $row, $col, 0x0);
            $this->buffer .= pack("d", $value);
        }
        // Is string or other
        else
        {
            $value = (string) $value;
            $L = strlen($value);
            $this->buffer .= pack("ssssss", 0x204, 8 + $L, $row, $col, 0x0, $L);
            $this->buffer .= $value;
        }
    }
	
    // Excel end of file footer
    public function close_flush()
    {
        $this->buffer .= pack("ss", 0x0A, 0x00);
        return $this->buffer;
    }
	
	//parameters separated by commas.
	public function write_row()
	{
		$numargs = func_num_args();
		$arg_list = func_get_args();
    	for ($i = 0; $i < $numargs; $i++) 
		{
			$this->write($this->_row,$i,$arg_list[$i]);
		}
		$this->_row++;
	}
	
	public function skip_row($total = 1)
	{
		$this->_row += $total;
	}
	
	public function write_string($string, $separator = "|")
	{
		$row = explode($separator,$string);
		$totalCols = count($row);
		
		for ($i = 0; $i < $totalCols; $i++) 
		{
			$this->write($this->_row,$i,$row[$i]);
		}
		$this->_row++;	
	}
	
	public function write_array($array = array(), $strip_tags = false)
	{
		$i = 0;
		foreach($array as $element) 
		{
			$element = $strip_tags ? strip_tags($element) : $element;
			$this->write($this->_row,$i,$element);
			$i++;
		}
		$this->_row++;	
	}
		
}

?>
