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
 * require("Csv_writer_model.php");
 * $csv = new Csv_writer_model();
 * $csv->write(0,0,"This is a label"); // write a label in A1, use for dates too
 * $csv->write(0,1,9999); // write a number B1
 * echo $csv->close_flush(); // close and echoes the stream
 * </code>
 * 
 */

class Csv_writer_model extends CI_Model
{
    protected $buffer;
	protected $_row;

    public function __construct()
    {
		$this->buffer = fopen('php://output', 'w');
    }

    // Function to write a value into Row, Col.
    public function write($row, $col, $value)
    {   
		$value = utf8_decode($value);
		fwrite($this->buffer, $value.",");
    }
    public function show_headers($filename)
	{
		header("Content-type: text/csv");  
		header("Cache-Control: no-store, no-cache");  
		header("Content-Disposition: attachment; filename=\"". $filename .".csv\"");  
	}
	
    // Excel end of file footer
    public function close_flush()
    {
		fclose($this->buffer);
    }
	
	//
	public function write_row()
	{
		$arg_list = func_get_args();
		fputcsv($this->buffer,$arg_list);
	}
	
	public function skip_row($total = 1)
	{
		fwrite($this->buffer,"\n");
	}
	
	public function write_string($string, $separator = "|")
	{
		$row = explode($separator,$string);
		fputcsv($this->buffer,$row);
	}
	
	public function write_array($array = array(), $stripTags = false)
	{
		$i = 0;
		$stripTags ? array_walk($array,strip_tags) : NULL;
		fputcsv($this->buffer,$array);
	}
		
}

?>