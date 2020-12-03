function rowButtons(rowSpan){
	return '<td rowspan="' + rowSpan + '"><button class="btn btn-primary btn-table edit-row"><i class="fa fa-edit"></i></button>' +
			'<button class="btn btn-primary btn-table check-row hidden"><i class="fa fa-check"></i></button>' +
		   '<button class="btn btn-danger btn-table delete-row"><i class="fa fa-trash"></i></button></td>';
}

function columnButtons(){
	return '<td><button class="btn btn-danger btn-table delete-column"><i class="fa fa-trash"></i></button></td>';
}

function addButtons(position){
	return '<button class="btn btn-success btn-table add-row-' + position + '"><i class="fa fa-plus"></i> Row</button>' + 
		   '<button style="float: right" class="btn btn-info btn-table add-col-' + position + '"><i class="fa fa-plus"></i> Col</button>';
}

function checkRowSpan(row){
	var rowSpan = 1;
	row.find('td').each(function(){
		if(getCellsCount($(this), 'rowspan') > rowSpan) rowSpan = getCellsCount($(this), 'rowspan');
	});
	return rowSpan;
}

function addRowButtons(){
	rowCounter = 1;
	$('table.editable-table tr').each(function(){
		if(rowCounter > 1){
			rowCounter--;
			return;
		}
		var rowSpan = checkRowSpan($(this));
		if(rowSpan > 1) rowCounter = rowSpan;
		$(this).append(rowButtons(rowSpan));
	});
}

function addColumnButtons(){
	var colCount = columnCount(0, $('table.editable-table tr').length - 1);
	$('table.editable-table').prepend('<tr></tr>');
	for(var i = 0; i < colCount; i++){
		$('table.editable-table tr:first-child').append(columnButtons());
	}
	$('table.editable-table tr:first-child').append('<td></td>');
}

function addControleButtons(topAndBottom = true){
	addRowButtons();
	addColumnButtons();
	if(!topAndBottom) return;
	$('table.editable-table').before(addButtons('top'));
	$('table.editable-table').after(addButtons('bottom'));
}

function removeControleButtons(){
	$('table.editable-table tr:first-child').remove();
	rowCounter = 0;
	$('table.editable-table tr').each(function(){
		var cell = $(this).find('td:last-child');
		if(cell.has('button').length) $(this).find('td:last-child').remove();
	});
}

function rowCount(){
	return $('table.editable-table tr').length;
}

function columnCountInRow(row){
	var colCount = 0;
	row.find('td').each(function(){
		colCount += getCellsCount($(this), 'colspan');
	});
	return colCount - 1;
}

function columnCount(start, end){
	var colCount = 0;
	for (var i = start; i <= end; i++) {
		var row = $('table.editable-table tr').eq(i);
		var count = columnCountInRow(row);
		if(count > colCount) colCount = count;
	};
	return colCount;
}

function newRow(){
	var colCount = columnCount(0, $('table.editable-table tr').length - 1);
	var row = '<tr>';
	for(var i = 0; i < colCount; i++){
		row += '<td contenteditable="true"></td>';
	}
	row += rowButtons(1) + '</tr>';
	return row;
}

function addRow(position){
	var table = $('table.editable-table');
	position == 'top' ? table.prepend(newRow()) : table.append(newRow());
}

function delRow(button){
	if(rowCount() > 1){
		button.parent('td').parent('tr').remove();
	}
}

function checkIfDeletable(cellInColumnIndex, cellInColumn, columnIndex){
	var shift = getShift(cellInColumn);
	var colspan = getCellsCount(cellInColumn, 'colspan');
	if((shift + cellInColumnIndex + colspan - 1) >= columnIndex){
		if(colspan > 1) cellInColumn.attr('colspan', colspan - 1);
		else cellInColumn.remove();
		return true;
	}
	return false;
}

function deleteCellsInColumn(columnIndex){
	var rows = $('table.editable-table tr');
	for(var i = 1; i < rows.length; i++){
		var columns = rows.eq(i).find('td');
		for (var j = 0; j < columns.length; j++) {
			if(checkIfDeletable(j, columns.eq(j), columnIndex)) {
				break;
			}
		};
	}
}

function delColumn(button){
	var cell = button.parent('td');
	var rows = $('table.editable-table tr');
	if(columnCount(0, rows.length - 1) <= 1) return;
	for(var i = 0; i < rows.length; i++){
		var columns = rows.eq(i).find('td');
		for (var j = 0; j < columns.length; j++) {
			if(cell.is(columns.eq(j))){
				deleteCellsInColumn(j);
				removeControleButtons();
				addControleButtons(false);
				return;
			}
		};
	}
}

function addCol(position){
	var child = position == 'top' ? 'last' : 'first';
	$('table.editable-table tr td:' + child + '-child').each(function(){
		var rowspan = 1;
		if($(this).parent('tr').has('button').length){
			rowspan = getCellsCount($(this).parent('tr').find('td:last-child'), 'rowspan');
			$(this).before('<td contenteditable="true" rowspan="' + rowspan + '"></td>');
			if($(this).parent('tr').find('button.edit-row').hasClass('hidden')) editRow($(this).parent('tr'));
		}
	});
	updateShiftedCells(0, $('table.editable-table tr').length - 1);
	removeControleButtons();
	addControleButtons(false);
}

function inOrAcitveEditable(row, edit){
	var buttonsCell = row.find('td:last-child');
	var rowsNumber = getCellsCount(buttonsCell, 'rowspan');
	var rightRow = row;
	for (var i = 0; i < rowsNumber; i++) {
		rightRow.find('td').each(function(){
			if($(this).has('button').length) return;
			$(this).attr('contenteditable', edit);
			if(edit == 'true') {$(this).css('border-color', 'white'); $(this).removeClass('checked');}
		});
		if(rightRow.next('tr') === undefined) break;
		rightRow = rightRow.next('tr');
	};
}

function editRow(row){
	inOrAcitveEditable(row, 'false');
}

function checkRow(row){
	inOrAcitveEditable(row, 'true');
	addCellControl();
}

function cellControl(){
	return '<div class="cell-control">' +
				'<button class="btn btn-info btn-table merge hidden"><i class="fa fa-code-fork"></i></button>' +
				'<button class="btn btn-default btn-table split-columns"><i class="fa fa-columns"></i></button>' +
				'<button class="btn btn-default btn-table split-rows"><i class="fa fa-columns rotate"></i></button>' +
			'</div>';
}

function checkedCellsNumber(){
	return $('table.editable-table tr td.checked').length;
}

function getCellsCount(cell, rowOrColSpan){
	if(cell.attr(rowOrColSpan) === undefined) return 1;
	return parseInt(cell.attr(rowOrColSpan));
}

function inSameRow(){
	var indexLastCell = getLastCell(), indexFirstCell = getFirstCell();
	if(indexLastCell[0] != indexFirstCell[0]) return false;
	var rows = $('table.editable-table tr');
	var firstCell = rows.eq(indexFirstCell[0]).find('td').eq(indexFirstCell[1]);
	var firstRowspan = getCellsCount(firstCell, 'rowspan');
	for(var i = indexFirstCell[1]; i <= indexLastCell[1]; i++){
		var cell = rows.eq(indexFirstCell[0]).find('td').eq(i);
		var rowspan = getCellsCount(cell, 'rowspan');
		if(rowspan != firstRowspan) return false;
	}
	return true;
}

function checkRowsInSameColumn(indexFirstCell){
	var firstCell = getCellFromIndex(indexFirstCell);
	var firstCellShift = getShift(firstCell);
	var rows = $('table.editable-table tr');
	for(var i = 0; i < rows.length; i++){
		var columns = rows.eq(i).find('td');
		for (var j = 0; j < columns.length; j++) {
			var cellShift = getShift(columns.eq(j));
			if(columns.eq(j).hasClass('checked') && (j + cellShift) != (firstCellShift + indexFirstCell[1])) return false;
		};
	}
	return true;
}

function checkColspanInSameColumn(indexFirstCell){
	var rows = $('table.editable-table tr');
	var firstCell = rows.eq(indexFirstCell[0]).find('td').eq(indexFirstCell[1]);
	var firstColspan = getCellsCount(firstCell, 'colspan');
	for(var i = 0; i < rows.length; i++){
		var columns = rows.eq(i).find('td');
		for (var j = 0; j < columns.length; j++) {
			var colspan = getCellsCount(columns.eq(j), 'colspan');
			if(columns.eq(j).hasClass('checked') && colspan != firstColspan) return false;
		};
	}
	return true;
}

function inSameColumn(){
	var indexLastCell = getLastCell(), indexFirstCell = getFirstCell();
	var sameColumn = checkRowsInSameColumn(indexFirstCell);
	var sameColspan = checkColspanInSameColumn(indexFirstCell);
	return sameColumn && sameColspan;
}

function mergable(){
	if(checkedCellsNumber() <= 1) return false;
	var sameRow = inSameRow();
	var samecol = inSameColumn();
	return (sameRow && !samecol) || (!sameRow && samecol);
}

function inOrActiveMerge(){
	if(mergable()){
		if($('button.merge').hasClass('hidden')) $('button.merge').removeClass('hidden');
		return;
	}
	if(!$('button.merge').hasClass('hidden')) $('button.merge').addClass('hidden');
}

function inOrActiveSplit(){
	if((checkedCellsNumber() > 1 || checkedCellsNumber() == 0) && !($('button.split-columns').hasClass('hidden') || $('button.split-rows').hasClass('hidden'))){
		$('button.split-columns').addClass('hidden');
		$('button.split-rows').addClass('hidden');
	}
	else if(checkedCellsNumber() == 1){
		$('button.split-columns').removeClass('hidden');
		$('button.split-rows').removeClass('hidden');
	}
}

function addCellControl(){
	if($('table.editable-table').has('div.cell-control').length) {
		inOrActiveMerge();
		inOrActiveSplit();
		return;
	}
	var table = $('table.editable-table');
	table.prepend(cellControl());
}

function getLastCell(){
	var lastCellRow = 0, lastCellCol = 0;
	var rows = $('table.editable-table tr');
	for (var i = 0; i < rows.length; i++) {
		var columns = rows.eq(i).find('td');
		for (var j = 0; j < columns.length; j++) {
			if(columns.eq(j).hasClass('checked')) { lastCellRow = i; lastCellCol = j;}
		};
	};
	return [lastCellRow, lastCellCol];
}

function getFirstCell(){
	var rows = $('table.editable-table tr');
	for (var i = 0; i < rows.length; i++) {
		var columns = rows.eq(i).find('td');
		for (var j = 0; j < columns.length; j++) {
			if(columns.eq(j).hasClass('checked')) return [i, j];
		};
	};
	return [-1, -1];
}

function updateButtonsControlCell(){
	removeControleButtons();
	addControleButtons(false);
}

function mergeInColumn(rows, cell, text, i, j, indexLastCell, indexFirstCell){
	if(i == indexFirstCell[0] && j == indexFirstCell[1]) return cell.text();
	var lastCell = rows.eq(indexFirstCell[0]).find('td').eq(indexFirstCell[1]);
	var rightCellRowspan = getCellsCount(cell, 'rowspan');
	var lastCellRowspan = getCellsCount(lastCell, 'rowspan');
	lastCell.attr('rowspan', rightCellRowspan + lastCellRowspan);
	lastCell.text(cell.text() + text + ' ');
	cell.remove();
	return lastCell.text();
}

function getMargedCells(indexFirstCell){
	var rows = $('table.editable-table tr');
	var cell = rows.eq(indexFirstCell[0]).find('td').eq(indexFirstCell[1]);
	var rowspan = getCellsCount(cell, 'rowspan');
	return rowspan;
}

function getShift(cell){
	if(cell.attr('data-shift') === undefined) return 0;
	return parseInt(cell.attr('data-shift'));
}

function shiftCellsInColumn(rowIndex, columnIndex, colspan){
	var row = $('table.editable-table tr').eq(rowIndex);
	for (var i = 0; i < row.find('td').length; i++) {
		var cell = row.find('td').eq(i);
		if(getShift(cell) + i < columnIndex) continue;
		cell.attr('data-shift', colspan + getShift(cell));
	};
}

function shiftCellsInRow(rowIndex, columnIndex, colspan){
	var row = $('table.editable-table tr').eq(rowIndex);
	var columns = row.find('td');
	for (var i = columnIndex; i < columns.length; i++) {
		var cell = columns.eq(i);
		cell.attr('data-shift', colspan + getShift(cell));
	};
}

function refreshShiftedData(start, end){
	for (var i = start; i <= end; i++) {
		var row = $('table.editable-table tr').eq(i);
		var columns = row.find('td');
		for (var j = 0; j < columns.length; j++) {
			var cell = columns.eq(j);
			cell.attr('data-shift', 0);
		};
	};
}

function updateShiftedCells(start, end){
	refreshShiftedData(start, end);
	for (var i = start; i <= end; i++) {
		var row = $('table.editable-table tr').eq(i);
		var columns = row.find('td');
		for (var j = 0; j < columns.length; j++) {
			if(getCellsCount(columns.eq(j), 'rowspan') > 1 && !columns.eq(j).has('button').length){
				var k = getCellsCount(columns.eq(j), 'rowspan') - 1;
				while(k > 0){shiftCellsInColumn(i + k, j + getShift(columns.eq(j)), getCellsCount(columns.eq(j), 'colspan')); k--;}
			}
			if(getCellsCount(columns.eq(j), 'colspan') > 1 && !columns.eq(j).has('button').length){
				var k = getCellsCount(columns.eq(j), 'colspan') - 1;
				shiftCellsInRow(i, j + 1, k);
			}
		};
	};
}

function searchForArray(haystack, needle){
  var i, j, current;
  for(i = 0; i < haystack.length; ++i){
    if(needle.length === haystack[i].length){
      current = haystack[i];
      for(j = 0; j < needle.length && needle[j] === current[j]; ++j);
      if(j === needle.length)
        return i;
    }
  }
  return -1;
}

function getCellFromIndex(index){
	var rows = $('table.editable-table tr');
	return rows.eq(index[0]).find('td').eq(index[1]);
}

function refreshTable(rows, indexFirstCell, indexLastCell){
	updateButtonsControlCell();
	updateShiftedCells(indexFirstCell[0], indexLastCell[0]);
	var row = rows.eq(indexFirstCell[0]);
	row.find('td:last-child button.edit-row').addClass('hidden');
	row.find('td:last-child button.check-row').removeClass('hidden');
	editRow(row);
}

function mergeColumn(){
	var indexLastCell = getLastCell(), indexFirstCell = getFirstCell();
	var firstCellShift = getShift(getCellFromIndex(indexFirstCell)), lastCellShift = getShift(getCellFromIndex(indexLastCell));
	var rows = $('table.editable-table tr'), text = '';
	for (var i = 0; i < rows.length; i++) {
		var columns = rows.eq(i).find('td');
		for (var j = 0; j < columns.length; j++) {
			var cellShift = getShift(columns.eq(j));
			if((cellShift + j) == (firstCellShift + indexFirstCell[1]) && (cellShift + j) == (lastCellShift + indexLastCell[1]) && i >= indexFirstCell[0] && i <= indexLastCell[0]){
				text = mergeInColumn(rows, columns.eq(j), text, i, j, indexLastCell, indexFirstCell);
			}
		};
	};
	refreshTable(rows, indexFirstCell, indexLastCell);
}

function mergeInRow(firstCell, secondCell){
	var firstColspan = getCellsCount(firstCell, 'colspan');
	var cellColspan = getCellsCount(secondCell, 'colspan');
	var text = firstCell.text() + secondCell.text();
	firstCell.text(text);
	firstCell.attr('colspan', firstColspan + cellColspan);
	secondCell.remove();
}

function mergeRow(){
	var indexLastCell = getLastCell(), indexFirstCell = getFirstCell();
	var firstCell = getCellFromIndex(indexFirstCell);
	var row = $('table.editable-table tr').eq(indexFirstCell[0]);
	var cells = row.find('td');
	for (var i = 0; i < cells.length; i++) {
		if(i > indexFirstCell[1] && i <= indexLastCell[1]){
			mergeInRow(firstCell, cells.eq(i));
		}
	};
}

function mergeCells(){
	if(inSameColumn()){
		mergeColumn();
	}
	else if(inSameRow()){
		mergeRow();
	}
	addCellControl();
}

function checkedCell(){
	var rows = $('table.editable-table tr');
	for (var i = 0; i < rows.length; i++) {
		var columns = rows.eq(i).find('td');
		for (var j = 0; j < columns.length; j++) {
			if(columns.eq(j).hasClass('checked')) return [columns.eq(j), i, j];
		};
	};
	return [null, -1, -1];
}

function splitable(){
	return checkedCellsNumber() == 1;
}

function updateEditingRows(rows){
	for (var i = 0; i < rows.length; i++) {
		rows[i].find('td:last-child button.edit-row').addClass('hidden');
		rows[i].find('td:last-child button.check-row').removeClass('hidden');
	};
}

function addCellsInColumn(row, cell, colspan, rowspan){
	var rightRow = row;
	var rows = [row];
	for (var i = 1; i <= rowspan - 1; i++) {
		rightRow = rightRow.next('tr');
		rows.push(rightRow);
		var td = '<td rowspan="1" colspan="' + colspan + '" contenteditable="false"></td>';
		if(cell[2] == row.find('td').length - 2) { rightRow.append(td); continue; }
		for (var j = 0; j < rightRow.find('td').length; j++) {
			var rightCell = rightRow.find('td').eq(j);
			if(cell[2] - getShift(cell[0]) == j + getShift(rightCell) - 1){
				rightCell.before(td);
			}
		};
	};
	return rows;
}

function splitRowCells(){
	if(!splitable)return;
	var cell = checkedCell();
	var rowspan = getCellsCount(cell[0], 'rowspan');if(rowspan == 1)return;
	var colspan = getCellsCount(cell[0], 'colspan');
	var row = cell[0].parent('tr');
	cell[0].attr('rowspan', 1);
	var rows = addCellsInColumn(row, cell, colspan, rowspan);
	updateButtonsControlCell();
	updateShiftedCells(0, $('table.editable-table tr').length);
	updateEditingRows(rows);
}

function addCellsInRow(row, colspan, rowspan){
	for (var i = 1; i <= colspan - 1; i++) {
		var td = '<td rowspan="' + rowspan + '" colspan="1" contenteditable="false"></td>';
		row.prepend(td);
	};
}

function splitColumnCells(){
	if(!splitable)return;
	var cell = checkedCell();
	var rowspan = getCellsCount(cell[0], 'rowspan');
	var colspan = getCellsCount(cell[0], 'colspan');if(colspan == 1)return;
	var row = cell[0].parent('tr');
	cell[0].attr('colspan', 1);
	addCellsInRow(row, colspan, rowspan)
}

$(document).ready(function(){
	addControleButtons();

	updateShiftedCells(0, $('table.editable-table tr').length);

	$(document).on('click', 'button.add-row-top', function(e){
		e.preventDefault();
		addRow('top');
	});

	$(document).on('click', 'button.add-row-bottom', function(e){
		e.preventDefault();
		addRow('bottom');
	});

	$(document).on('click', 'button.add-col-top', function(e){
		e.preventDefault();
		addCol('top');
	});

	$(document).on('click', 'button.add-col-bottom', function(e){
		e.preventDefault();
		addCol('buttom');
	});

	$(document).on('click', 'button.edit-row', function(e){
		e.preventDefault();
		var row = $(this).parent('td').parent('tr');
		$(this).addClass('hidden');
		$(this).parent('td').find('button.check-row').removeClass('hidden');
		editRow(row);
	});

	$(document).on('click', 'button.check-row', function(e){
		e.preventDefault();
		var row = $(this).parent('td').parent('tr');
		$(this).addClass('hidden');
		$(this).parent('td').find('button.edit-row').removeClass('hidden');
		checkRow(row);
	});

	$(document).on('click', 'button.delete-row', function(e){
		e.preventDefault();
		delRow($(this));
	});

	$(document).on('click', 'button.delete-column', function(e){
		e.preventDefault();
		delColumn($(this));
	});

	$(document).on('click', 'table.editable-table tr td', function(){
		if($(this).attr('contenteditable') == 'false' && !$(this).has('button').length){
        	$(this).toggleClass('checked');
        	addCellControl();
        }
	});

	$(document).on('click', 'table.editable-table button.merge', function(e){
		e.preventDefault();
		mergeCells();
	});

	$(document).on('click', 'table.editable-table button.split-columns', function(e){
		e.preventDefault();
		splitColumnCells();
	});

	$(document).on('click', 'table.editable-table button.split-rows', function(e){
		e.preventDefault();
		splitRowCells();
	});

	$(document).on({
	    mouseenter: function () {
	        if($(this).attr('contenteditable') == 'false' && !$(this).has('button').length){
	        	$(this).css('border-color', 'red');
	        }
	    },
	    mouseleave: function () {
	    	if(!$(this).hasClass('checked')) $(this).css('border-color', 'white');
	    }
	}, 'table.editable-table tr td');
});