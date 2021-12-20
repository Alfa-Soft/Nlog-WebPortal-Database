 

		var tableEventLogRowsSelectedRow;
		var tableEventLogRowsHasSelection = false;
        var tableEventLogRows;

		$( document ).ready(function() {
		  tableEventLogRows = document.getElementById('fullcontenttable').tBodies[0].getElementsByTagName('tr');
		});
		
        // disable text selection
        document.onselectstart = function () {
			if (tableEventLogRowsHasSelection)
				return false;
			else
				return true;
        }


        function RowClick(currenttr, lock) 
		{			
            if (window.event.ctrlKey) {
                RowClick_ToggleRow(currenttr);
            }

            if (window.event.button === 0) {
                if (!window.event.ctrlKey && !window.event.shiftKey) {
                    RowClick_ClearAll();
                    RowClick_ToggleRow(currenttr);
                }

                if (window.event.shiftKey) {
                    RowClick_SelectRowsBetweenIndexes([tableEventLogRowsSelectedRow.rowIndex, currenttr.rowIndex])
                }
            }
        }

        function RowClick_ToggleRow(row) 
		{
			var isNowSelected = row.className == 'selectedRow';
            row.className  = isNowSelected ? 'unselectedRow' : 'selectedRow';
            tableEventLogRowsSelectedRow = row;
			
			tableEventLogRowsHasSelection = !isNowSelected;
			
			var lastCellCheckBox = $(row.cells[row.cells.length - 1].childNodes[0]);
			lastCellCheckBox.prop("checked", !lastCellCheckBox.prop("checked"));
        }

        function RowClick_SelectRowsBetweenIndexes(indexes) 
		{
            indexes.sort(function (a, b) {
                return a - b;
            });

            for (var i = indexes[0] + 1; i <= indexes[1]; i++)  //+1 table header
			{
				var row = tableEventLogRows[i - 1];
                row.className = 'selectedRow';	
				
				var lastCellCheckBox = $(row.cells[row.cells.length - 1].childNodes[0]);
				lastCellCheckBox.prop("checked", "checked");
            }
        }
		
		function RowClick_SelectAll() 
		{
            for (var i = 1; i < tableEventLogRows.length; i++)  //+1 table header
			{
				var row = tableEventLogRows[i];
                row.className = 'selectedRow';	
				
				var lastCellCheckBox = $(row.cells[row.cells.length - 1].childNodes[0]);
				lastCellCheckBox.prop("checked", "checked");
            }
        }

        function RowClick_ClearAll() 
		{
            for (var i = 1; i < tableEventLogRows.length; i++) //+1 table header
			{
				var row = tableEventLogRows[i];
                row.className = 'unselectedRow';
				
				var lastCellCheckBox = $(row.cells[row.cells.length - 1].childNodes[0]);
				lastCellCheckBox.prop("checked", "");
            }
			
			tableEventLogRowsHasSelection = false;
        }

		function RowClick_ToggleAll(senderCheckBox)
		{	
			if(senderCheckBox.checked)
				RowClick_SelectAll();		
			else
				RowClick_ClearAll();
		}
		
		
function SubmitDeleteRequest(formName, formValueField)
{
	var ids = new Array();
	for (var i = 0; i < tableEventLogRows.length; i++) 
	{
		if(tableEventLogRows[i].className == "selectedRow")
		{
			var id = $(tableEventLogRows[i]).children('td:first')[0].attributes['bugId'].value;
			ids.push(id);
		}
	}
	
	var obj = document.getElementById(formValueField);
	if(obj != null)
	{
		obj.value = ids.join(',');		
		window.setTimeout(
		function(){formName.submit()},
		100);
	}
}