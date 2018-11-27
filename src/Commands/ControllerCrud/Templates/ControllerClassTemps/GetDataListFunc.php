<?php

return function($params){

extract($params);

if(count($addLangFields) > 0){
	$langFields = array_column($addLangFields, 'name');
	$langFields = implode("', \n\t\t\t\t'", $langFields);
	$langFields = "\n\t\t\t\t'".$langFields."' \n\t\t\t";
}else
	$langFields = [];

$fieldDependsOnLang = $fieldDependsOnLang ?? '';

return '
	public function getDataList(Request $request)
	{
	    $tblInfo = $request->all();

	    /*Array select and search columns*/
	    foreach ($tblInfo[\'columns\'] as $column) {
	        
	        if (isset($column[\'data\']))
	            $selectCol[] = $column[\'data\'];

	        if($column[\'searchable\'])
	            $searchCol[] = $column[\'data\'];
	    }

	    /*Order settings*/
	    $colIndex = $tblInfo[\'order\'][0][\'column\'];
	    $colOrder = $tblInfo[\'columns\'][$colIndex][\'data\'];
	    $order = $tblInfo[\'order\'][0][\'dir\'];

	    $dataList = '.$modelName.'::dataList([
	        \'table\' => \''.strtolower($modelName).'\',
	        \'fieldIDName\' => \''.$fieldIDName.'\',
	        \'addLangFields\' => ['.$langFields.'],
	        \'fieldDependsOnLang\' => \''.$fieldDependsOnLang.'\',
	        \'selectCol\' => $selectCol,
	        \'searchCol\' => $searchCol,
	        \'colOrder\' => $colOrder,
	        \'order\' => $order,
	        \'search\' => $tblInfo[\'search\'][\'value\'],
	    ]);

	    $recordsTotal = '.$modelName.'::count();
	    $recordsFiltered = $dataList->count();

	    $data = $dataList->offset($tblInfo[\'start\'])
	    ->limit($tblInfo[\'length\'])
	    ->get();

	    return [
	        \'recordsTotal\' => $recordsTotal, 
	        \'recordsFiltered\' => $recordsFiltered, 
	        \'data\' => $data,
	        \'draw\' => $tblInfo[\'draw\']
	    ];
	}
';
};