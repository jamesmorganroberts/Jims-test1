<?PHP

	/*
   ==================================================================================
   Project     : SEOTrackz
   File        : seotrackz/includes/GoogleVis.php
   Description : Classes and functions that enable the creation of Google visualisations
   Parameters  : -
   Author      : Martin Little
   Date        : May 2009
   ==================================================================================
   */

  require_once("includes/WebTools.php");

/*------------------------------------------------------------------------------------------------*/


	// Export the supplied data arrays as an array of GoogleVisColumn objects
  	function exportGoogleVisColumns($arrDataCols) {
  	
			$arrColumns = array();
		
			foreach($arrDataCols AS $key => $arrDataCol) {
					
				$col = new GoogleVisCol();
				$col->setKey($key);
				$col->setId(str_replace(" ","_",strtolower($arrDataCol['label'])));
				$col->setLabel($arrDataCol['label']);
				$col->setType($arrDataCol['type']);
				
				$arrColumns[] = $col;
			
			}
			
			return $arrColumns;
				
		}
		
		// Export the supplied data arrays as an array of GoogleVisRow objects
  	function exportGoogleVisRows($arrDataRows) {
				
			// Build Google Vis Rows
			$arrRows = array();	
		
			foreach($arrDataRows AS $key => $arrDataRow) {
			
				$row = new GoogleVisRow();
			
				foreach($arrDataRow AS $key2 => $dataCell) {	
				
					$cell = new GoogleVisCell();

					if(is_array($dataCell)) {
						$cell->setClassName($dataCell['classname']);
						if(!$dataCell['value']) {
							$cell->setV(" ");
						} else {
							$cell->setV($dataCell['value']);
						}

             if($dataCell['class']) {
                $cell->setClassName($dataCell['class']); 
             }
					} else {					
						$cell->setV($dataCell);
					}
					
					$row->addCell($cell);
				
				}

				$arrRows[] = $row;

			}
			
			return $arrRows;
			
		}

// Pass in the 'type' to receive suitable formatting strings to apply to this field in a Google Vis table.
// Pass in the datatable name this formatting should apply to
// Also pass in the column ID that this formatting should apply to (zero index based)
function GoogleVisFormatter($type,$tablename,$colid) {

	$str = "";
	if($type == "currency") {
		$str = "var formatter = new google.visualization.NumberFormat({decimalSymbol: '.', fractionDigits: '2', groupingSymbol: ',', negativeColor: 'red', negativeParens: false, prefix:'&pound;'});\n";
		$str.= "formatter.format(".$tablename.",".$colid.");\n";
	} 
	
	elseif($type == "percentage") {
		$str = "var formatter = new google.visualization.NumberFormat({decimalSymbol: '.', fractionDigits: '2', groupingSymbol: ',', negativeColor: 'red', negativeParens: false, suffix:'%'});\n";
		$str.= "formatter.format(".$tablename.",".$colid.");\n";
	}

	return $str;

}

/*------------------------------------------------------------------------------------------------*/

function GoogleVisJSAPI() {

	if(SeoTrackzConstants::GOOGLE_VIS_OFFLINE) {
		
		$str = "";
	
	} else {

		$arrKeys = array();
		$arrKeys['akme.enablemedia.com'] = "ABQIAAAA1vmtsMm9mJ4kf4K8JAHZFxT3oZ4OQTOtVVL2qPEYMHiCM7KT2hSXC24HHam5yp0th8chogVAI5Yy-A";
		$arrKeys['akme.touchlocal.com'] = "ABQIAAAA1vmtsMm9mJ4kf4K8JAHZFxSwRBJFpcp_ItbkR-fb4UMPRrIwtxSc9THSrwf2hpoogxtXUnBsyPgBHw";
		$arrKeys['arlington.enablemedia.com'] = "ABQIAAAA1vmtsMm9mJ4kf4K8JAHZFxSNROhCdaipmVSutHPvjZlgM6dSRRR10V0plfmh5Djpk_GE2QFeL-SjAw";
	
		$key = $arrKeys[$_SERVER['HTTP_HOST']];
		if(!$key) {	
			$key = $arrKeys['akme.enablemedia.com'];
		}	
		
		$str = "<script type=\"text/javascript\" src=\"http";
		if($_SERVER['HTTPS'] || true==true) {
			$str.="s";
		}
		$str.= "://www.google.com/jsapi?key=".$key."\"></script>";
		
	}
	
	return $str;
}

/*------------------------------------------------------------------------------------------------*/

// Build the string that defines a Google Visualisation data table...
// OR in offline mode, render a plain <table>
function GoogleVisDataTable($arrColumns,$arrRows,$varName="data",$applyFormatting=true) {
	
	if(SeoTrackzConstants::GOOGLE_VIS_OFFLINE) {
	
		$str = '<table class="google-visualization-table-table" style="background-color:#ffffff;">';
		
		if(sizeof($arrColumns) > 0) {
			$str.= '<tr class="googleTableHeader">';
			foreach($arrColumns AS $k1 => $col) {
				$str.= '<th>'.$col->getLabel().'</th>';
			}
			$str.= '</tr>';
		}
		
		if(sizeof($arrRows) > 0) {
			
			foreach($arrRows AS $k1 => $row) {		
				$str.= '<tr>';
				foreach($row->getRow() AS $k2 => $cell) {	
					$col = $arrColumns[$k2];		
					$str.= $cell->generateHTMLCell($col->getGoogleType());
				}				
				$str.= '</tr>';				
			}
			
		}
		
		$str.= '</table>';
	
	} else {

		$str = "var ".$varName." = new parent.google.visualization.DataTable(";
		$str.= "{\n";
		$str.= "cols: [";
		
		if(sizeof($arrColumns) > 0) {
			foreach($arrColumns AS $k => $col) {
				$str.= $col->generateCol().",\n";
			}
			
			$str = substr($str,0,-2);
		}
		
		$str.= "],\n";
		$str.= "rows: [";
		
		if(sizeof($arrRows) > 0) {
		
			foreach($arrRows AS $k => $row) {
				$str.= $row->generateRow($arrColumns).",\n";
			}
		
			$str = substr($str,0,-2);
		}
		
		$str.= "]\n";
		$str.= "}\n";
		$str.= ");\n";
		
		$str = substr($str,0,-2);	
		
			// Apply formatting to these fields if required
		if($applyFormatting) {
			
			$str.= "\n";
			
			if(sizeof($arrColumns) > 0) {
				foreach($arrColumns AS $k => $col) {			
					$str.= GoogleVisFormatter($col->getType(),$varName,$k);				
				}					
			}
			
		}	
		
	}

	return $str;

}

 function drawLineChart($companies,$data,$chartName,$div="",$width=500,$height=500,$background="#f0f0f0",$xaxisType='date') {

       // The $data should be in the following format 
       // format = array("string "=>array(client,competitorA,CompetitorB));
       // Eg :  array("month 2"=>array(600,300,800),"month 3"=>array(800,700,100),"Month 4"=>array(500,900,600),"Month 5"=>array(400,400,900),"Month 6"=>array(500,600,90));

        // The $companies should be in the following format
        // format : array(client,competitorA,competitorB);
        // Eg: (www.scoot.co.uk,www.touchlocal.com,www.yell.com);

       $arrDataColsHTML = array();	// the columns
		   $arrDataRowsHTML = array();
     
       $arrDataCol = array();
       $arrDataCol['label'] = "";
	     $arrDataCol['type'] = $xaxisType;
	     $arrDataColsHTML[] = $arrDataCol;

       foreach ($companies as $c) {

             $arrDataCol = array();
       		   $arrDataCol['label'] = $c;
	     			 $arrDataCol['type'] = "number";
	     			$arrDataColsHTML[] = $arrDataCol;
       }

        foreach ($data as $key=>$A) {
    
      			$arrDataRowsCell = array();	
		 				$arrDataRowsCell[]=$key;
     			  foreach($A as $B) {
      				$arrDataRowsCell[]=$B;
     				}

      		$arrDataRowsHTML[] = $arrDataRowsCell;

      }

      $arrColumns = exportGoogleVisColumns($arrDataColsHTML);			
			// Build Google Vis Rows
	    $arrRows = exportGoogleVisRows($arrDataRowsHTML);

	   $strTable = GoogleVisDataTable($arrColumns,$arrRows,"data");

	?>
     <script type="text/javascript">
      function drawVisualization<?=$chartName?>() {
        // Create and populate the data table.
       						<? echo $strTable; ?>
       
        // Create and draw the visualization.
        new google.visualization.LineChart(document.getElementById('<?=$div?>')).
            draw(data, {curveType: "none",
                        width: <?=$width?>, height: <?=$height?>,
                        vAxis: {maxValue: 10},legend: "top",pointSize:"5",backgroundColor: "<?=$background?>"}
                );
      }
      

      google.setOnLoadCallback(drawVisualization<?=$chartName?>);
    </script>
	<?
  }


function drawBarChart($data,$chartName,$div="",$width=500,$height=500,$background="#f0f0f0") {

       // The $data should be in the following format 
       // format = array("string "=>value);
       // Eg :  array("Client "=>100,"Company A"=>600,"Company B"=>800);
       $arrDataColsHTML = array();	// the columns
		   $arrDataRowsHTML = array();
     
      		$arrDataRowsCell = array();		
        foreach ($data as $key=>$A) {

            $arrDataCol = array();
       		 $arrDataCol['label'] = $key;
	     		 $arrDataCol['type'] = "number";
	     		 $arrDataColsHTML[] = $arrDataCol;
    

      		$arrDataRowsCell[]=$A;


      }
      $arrDataRowsHTML[] = $arrDataRowsCell;

      $arrColumns = exportGoogleVisColumns($arrDataColsHTML);			
			// Build Google Vis Rows
	    $arrRows = exportGoogleVisRows($arrDataRowsHTML);

	   $strTable = GoogleVisDataTable($arrColumns,$arrRows,"data");

	?>
     <script type="text/javascript">
      function drawVisualization<?=$chartName?>() {
        // Create and populate the data table.
       						<? echo $strTable; ?>
       
        // Create and draw the visualization.
        new google.visualization.ColumnChart(document.getElementById('<?=$div?>')).
            draw(data, {curveType: "none",
                        width: <?=$width?>, height: <?=$height?>,
                        vAxis: {maxValue: 10},legend: "top",pointSize:"5",backgroundColor: "<?=$background?>"}
                );
      }
      

      google.setOnLoadCallback(drawVisualization<?=$chartName?>);
    </script>
	<?
  }

  


/*------------------------------------------------------------------------------------------------*/

// Define a Data Table Column...

class GoogleVisCol {
	
	// Incrimental value of the column in the table
	protected $key;
	
	// Data type for this column - date, datetime, timeofday, string, number, boolean
	protected $type;
	
	// ID of this column (column name from SQL will suffice!)
	protected $id;
	
	// Label for this column (English formatted version of ID)
	protected $label;
	
	// Format of data entry (e.g ('YYYY MM DD'))
	protected $pattern;
	
	function getType() {
		return $this->type;
	}
	
	// Return the 'Google' type that's correct for our 'custom' type (e.g 'currency' 
	function getGoogleType() {
		if($this->getType() == 'currency') {
			return 'number';
		} elseif($this->getType() == 'percentage') {
			return 'number';
		} else {	
			return $this->getType();
		}
	}
	
	function setType($type) {
		$this->type = $type;
	}
	
	function getKey() {
		return $this->key;
	}
	
	function setKey($key) {
		$this->key = $key;
	}
	
	function getId() {
		return $this->id;
	}
	
	function setId($id) {
		$id = preg_replace("/[^a-zA-Z0-9\s]/", "", $id);
		$this->id = $id;
	}
	
	function getLabel() {
		return $this->label;
	}
	
	function setLabel($label) {
		$this->label = $label;
	}
	
	function getPattern() {
		return $this->pattern;
	}
	
	function setPattern($pattern) {
		$this->pattern = $pattern;
	}
	
	// Spit out the Google DataTable formatted version of this column...
	function generateCol() {
	
		$str = "{id: '".$this->getId()."',";
		if($this->getLabel()) {
			$str.="label: '".str_replace("'","\'",$this->getLabel())."',";
		}		
		$str.="type: '".$this->getGoogleType()."',";
		
		$str = substr($str,0,-1);		
		$str.="}";
	
		return $str;
	
	}
		
}

/*------------------------------------------------------------------------------------------------*/

// Define a row in a data table...

class GoogleVisRow {
	
	// Cells in this row
	protected $row;
	
	function getRow() {
		return $this->row;
	}
	
	function setRow($row) {
		$this->row = $row;
	}
	
	function addCell($cell) {
		$row = $this->getRow();
		$row[] = $cell;
		$this->setRow($row);
	}
	
	// Spit out the Google DataTable formatted version of this row...
	function generateRow($arrColumns) {
	
		$str = "{c:[";
		foreach($this->getRow() AS $k => $cell) {	
			$col = $arrColumns[$k];		
			$str.= $cell->generateCell($col->getGoogleType()).",";
		}
		
		$str = substr($str,0,-1);
		$str.= "]}";
		
		return $str;
		
	}
		
}

/*------------------------------------------------------------------------------------------------*/

// Define a cell in the data table...

class GoogleVisCell {
	
	// Value of this cell
	protected $v;

	// English formatted version of the value
	protected $f;
	
	// ClassName to apply
	protected $className;
	
	function getV() {
		return $this->v;
	}
	
	function setV($v) {
		$this->v = $v;
	}
	
	function getF() {
		return $this->f;
	}
	
	function setF($f) {
		$this->f = $f;
	}
	
	function getClassName() {
		return $this->className;
	}
	
	function setClassName($className) {
		$this->className = $className;
	}
	
	// Make sure ' are escaped, but not doubly escaped...
	// Also correct any other JS breaking text...
	function escapeJSChars($text) {
		$text = str_replace("\r","",$text);
		$text = str_replace("\n","",$text);		
		$text = preg_replace('/\s\s+/', ' ', trim($text));
		$text = str_replace("'","\\'",$text);
		$text = str_replace("\\\\","\\",$text);	
		return $text;	
	}
	
	// Spit out the Google DataTable formatted version of this cell...
	function generateCell($type) {
		
		if($this->getV()) {
		
			$str = "{v: ";
			
			if($type=="string") {
				$str.= "'".$this->escapeJSChars($this->getV())."',";
			} elseif($type=="boolean") {
				$str.= "'".$this->getV()."',";
			} elseif($type=="date") {
				$str.= "new Date(".substr($this->getV(),0,4).",".(substr($this->getV(),5,2) - 1).",".substr($this->getV(),8,2)."),";
			} elseif($type=="datetime") {
				$str.= "new Date(".substr($this->getV(),0,4).",".(substr($this->getV(),5,2) - 1).",".substr($this->getV(),8,2).",".substr($this->getV(),11,2).",".substr($this->getV(),14,2).",".substr($this->getV(),17,2)."),";
			} elseif($type=="timeofday") {
				$str.= "[".substr($this->getV(),11,2).",".substr($this->getV(),14,2).",".substr($this->getV(),17,2)."],";
			} else {		
				$str.= $this->escapeJSChars($this->getV()).",";
			}
			
			if($this->getF()) {
				$str.= "f: '".$this->getF()."',";
			}		
			
			if($this->getClassName()) {
				$str.="p: {'className': '".$this->getClassName()."'},";
			}
			
			$str = substr($str,0,-1);
			$str.= "}";

		} else {
		
			$str = "";
			
		}
		
		return $str;
		
	}
	
	// Spit out the basic HTML formatted version of this cell...
	function generateHTMLCell($type) {
		
		$str = "";
		
		if($this->getClassName()) {
			$str.="<td class=\"googleTableCell ".$this->getClassName()."\">";
		}	else {
			$str.="<td class=\"googleTableCell\">";
		}
		
		if($this->getV()) {

			if($type=="string") {
				$str.= $this->getV();
			} elseif($type=="boolean") {
				$str.= $this->getV();
			} elseif($type=="date") {
				$str.= substr($this->getV(),8,2)."/".(substr($this->getV(),5,2) - 1)."/".substr($this->getV(),0,4);
			} elseif($type=="datetime") {
				$str.= substr($this->getV(),8,2)."/".(substr($this->getV(),5,2) - 1)."/".substr($this->getV(),0,4)." ".substr($this->getV(),11,2).":".substr($this->getV(),14,2).":".substr($this->getV(),17,2);
			} elseif($type=="timeofday") {
				$str.= substr($this->getV(),11,2).":".substr($this->getV(),14,2).":".substr($this->getV(),17,2);
			} else {		
				$str.= $this->getV();
			}		

		} else {
		
			$str.= "&nbsp;";
			
		}
		
		$str.= "</td>";
		
		return $str;
		
	}


 
		
}

/*------------------------------------------------------------------------------------------------*/

// Example usage : 
// Set up some empty arrays to hold the column/row/cell data...

/*
$arrColumns = array();	// Array of columns
$arrRows = array();			// Array of rows
*/

// Then define your columns...

/*
$col = new GoogleVisCol();
$col->setKey('0');
$col->setId('name');
$col->setLabel('Name');
$col->setType('string');

$arrColumns[] = $col;

$col = new GoogleVisCol();
$col->setKey('1');
$col->setId('value');
$col->setLabel('Value');
$col->setType('number');

$arrColumns[] = $col;
*/

// Populate cells in the same order as the columns are declared and tag them together to make a row

/*
$query = "SELECT * FROM table WHERE 1";
$data = $mysqlread->getManyRows($query);
if($data) {
	foreach($data AS $k => $rs) {

		$row = new GoogleVisRow();
		
		$cell = new GoogleVisCell();
		$cell->setV($rs['name']);
		$row->addCell($cell);
	
		$cell = new GoogleVisCell();
		$cell->setV($rs['value']);
		$row->addCell($cell);

		$arrRows[] = $row;
	}
}
*/

// Call the GoogleVisDataTable function with the columns/rows to produce a string you can use in the visualisation

/*
$strPieChart = GoogleVisDataTable($arrColumns,$arrRows,"data");
*/

// Then simply embed this into your HTML...
/*
<script type="text/javascript">
    
	if (!google.visualization || !google.visualization.piechart) {
		// Load the Visualization API and the piechart package.
		google.load('visualization', '1', {'packages':['piechart']});
	}
	
	google.setOnLoadCallback(drawPieChart);

	function drawPieChart() {		
		<? echo $strPieChart; ?>
		var chart = new google.visualization.PieChart(document.getElementById('piechart_div'));
		chart.draw(data, { width: 400, height: 400, is3D: true, title: 'Pie Chart Demo' });
	}
</script>

<div id="piechart_div"></div>
*/

?>
