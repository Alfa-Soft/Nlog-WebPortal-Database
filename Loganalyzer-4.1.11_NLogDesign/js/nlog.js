function TableEventLogRowSelectorClass()
{
	//config
	var tableId          = 'fullcontenttable';
	var infoControlId    = 'DeleteCmdDeleteIdCount';
	
	//locals members
	var tableRows;
	var hasSelection = false;
	var selectedRow;
	var infoCtrl;
	
	//static configuration
	$( document ).ready(function() 
	{
		tableRows = document.getElementById(tableId).tBodies[0].getElementsByTagName('tr');
		infoCtrl  = $("#" + infoControlId);
	});

	// disable text selection
	document.onselectstart = function () {
		if (hasSelection)
			return false;
		else
			return true;
	}


	this.RowClick = function(currenttr, lock) 
	{			
		if (window.event.ctrlKey) {
			this.ToggleRow(currenttr);
		}

		if (window.event.button === 0) 
		{
			if (!window.event.ctrlKey && !window.event.shiftKey) {
				this.ClearAll();
				this.ToggleRow(currenttr);
			}

			if (window.event.shiftKey) {
				this.SelectRowsBetweenIndexes([selectedRow.rowIndex, currenttr.rowIndex])
			}
		}
		
		SetInfoControl();
	}

	this.ToggleRow = function(row) 
	{
		var isNowSelected = row.className == 'selectedRow';
		row.className  = isNowSelected ? 'unselectedRow' : 'selectedRow';
		selectedRow = row;
		
		hasSelection = !isNowSelected;
		
		var cb = GetRowCheckboxControl(row);
		cb.prop("checked", !cb.prop("checked"));
		
		SetInfoControl();
	}

	this.SelectRowsBetweenIndexes = function(indexes) 
	{
		indexes.sort(function (a, b) {
			return a - b;
		});

		for (var i = indexes[0] + 1; i <= indexes[1]; i++)  //+1 table header
		{
			var row = tableRows[i - 1];
			row.className = 'selectedRow';	
			
			var cb = GetRowCheckboxControl(row);
			cb.prop("checked", "checked");
		}
		
		SetInfoControl();
	}

	this.SelectAll = function() 
	{
		for (var i = 1; i < tableRows.length; i++)  //+1 table header
		{
			var row = tableRows[i];
			row.className = 'selectedRow';	
			
			var cb = GetRowCheckboxControl(row);
			cb.prop("checked", "checked");
		}
		
		SetInfoControl();
	}

	this.ClearAll = function() 
	{
		for (var i = 1; i < tableRows.length; i++) //+1 table header
		{
			var row = tableRows[i];
			row.className = 'unselectedRow';
			
			var cb = GetRowCheckboxControl(row);
			cb.prop("checked", "");
		}
		
		hasSelection = false;
		
		SetInfoControl();
	}

	this.ToggleAll = function(senderCheckBox)
	{	
		if(senderCheckBox.checked)
			this.SelectAll();		
		else
			this.ClearAll();
	}
	
	this.GetSelectedIDs = function()
	{
		var ids = new Array();
		for (var i = 0; i < tableRows.length; i++) 
		{
			var cb = GetRowCheckboxControl(tableRows[i]);
			if( cb.prop("checked"))
			{
				var id = GetEventId(tableRows[i]);
				ids.push(id);
			}
		}
		
		return ids;
	}
	
	function SetInfoControl()
	{		
		var counter = 0;
		for (var i = 1; i < tableRows.length; i++) //+1 table header
		{
			var cb = GetRowCheckboxControl(tableRows[i]);
			if(cb.prop("checked"))
				counter++;
		}
		
		infoCtrl.text(counter);
	}
	
	function GetRowCheckboxControl(row)
	{
		var cb = $(row.cells[row.cells.length - 1].childNodes[0]);
		return cb;
	}
	
	function GetEventId(row)
	{
		var id = $(row).children('td:first')[0].attributes['bugId'].value;
		return id;
	}
		
}
tableEventLogRowSelector = new TableEventLogRowSelectorClass();


		
function SubmitRequestDeleteIDs(formName, formValueField)
{
	var ids = tableEventLogRowSelector.GetSelectedIDs();
	
	
	var obj = document.getElementById(formValueField);
	if(obj != null)
	{
		obj.value = ids.join(',');		
		window.setTimeout(
		function(){formName.submit()},
		100);
	}
}