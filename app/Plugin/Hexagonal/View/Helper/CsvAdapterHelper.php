<?php


class CsvAdapterHelper extends AppHelper {

	public function afterRender($viewFile) {
		$data = $this->extractHtmlTables();
		$this->outputCsv($data, $this->getFilename());
		die;		
	}
	
	public function getFilename() {
		
	}
	
	public function extractHtmlTables() {
		
	}
	
	public function outputCsv($dataArray, $filename) {
		// TODO: if data is too big, send to file to avoid hitting memory limit.
		$fp = fopen('php://output', 'w');
		ob_end_clean();
		ob_start();
		foreach ($dataArray as $r) {
			fputcsv($fp, $r);
		}
		$contents = ob_get_clean();
		if (strtoupper(substr($contents, 0, 2)) == 'ID') {
			// account for MS Excel oddity with unquoted ID as first string
			$contents = '"ID"' . substr($contents, 2);
		}
		fclose($fp);
		header('Content-type: application/octet-stream');
		header("Content-Disposition: attachment; filename=\"$filename\"");
		echo $contents;
	}	
	
}