<?php
return [
	'allowExport'=>true, //if true, show the export option, maybe not a good idea for big grids or low server
	'rowsPerPage'=>[50,100,150,250,500,00], //the options to select
	'currentRowsPerPage'=>50, //the initial value by default - must exists in rowsPerPage
	'advancedSearch'=>[
		'formats'=>[
			'date'=>[
				//allow translate the date visual format to backend format. the index 0 is the js format (moment) and the index 1 is the php format (carbon)
				/*
					Example for Brazil standard
					'input'=>['DD/MM/YYYY', 'd/m/Y'], 
					'processTo'=>['YYYY-MM-DD', 'Y-m-d']
				*/
				'input'=>['YYYY-MM-DD', 'Y-m-d'], 
				'processTo'=>['YYYY-MM-DD', 'Y-m-d']
			],
			'datetime'=>[
				//allow translate the date visual format to backend format. the index 0 is the js format (moment) and the index 1 is the php format (carbon)
				/*
					Example for Brazil standard					
					'input'=>['DD/MM/YYYY HH:MM:ss', 'd/m/Y H:i:s'], 
					'processTo'=>['YYYY-MM-DD HH:MM:ss', 'Y-m-d H:i:s']
				*/
				'input'=>['YYYY-MM-DD HH:MM:ss', 'd/m/Y H:i:s'], 
				'processTo'=>['YYYY-MM-DD HH:MM:ss', 'Y-m-d H:i:s']
			]
		]
	]
];