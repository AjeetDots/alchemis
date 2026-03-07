/*
** (c) 2004 Illumen Ltd.
**
** Functions allowing user interaction with two select elements on an HTML page, where one list the
** options available and the other those selected.  The items moves between the list boxes 
** dependant upon selection.
**
** Last Updated On:  02/02/2004
** Last Updated By:  Ian Munday
*/


/*
** Purpose:  
**
** Accepts:  object - 
**           value  - 
**           text   - 
*/
function addOption(object, value, text)
{
	var defaultSelected = true;
	var selected = true;
	var optionName = new Option(text, value, defaultSelected, selected);
	object.options[object.length] = optionName;
}


/*
** Purpose:  Deletes the item at the given index from the given object.
**
** Accepts:  object - 
**           index  - 
*/
function deleteOption(object, index)
{
	object.options[index] = null;
}


/*
** Purpose:  Moves a selected item from src list to tgt list
**
** Accepts:  fromObject  - 
**           toObject    - 
**           hiddenField - 
**           type        - 
*/
function MoveTo(fromObject, toObject, hiddenField, type)
{
	if (fromObject.selectedIndex >= 0)
	{
		for (var i=0, l=fromObject.options.length; i<l; i++)
		{
			if (fromObject.options[i].selected)
			{
				editHiddenValue(hiddenField, fromObject.options[i].value, type);
				addOption(toObject, fromObject.options[i].value, fromObject.options[i].text);
			}
		}
		for (var i=fromObject.options.length-1; i>-1; i--)
		{
			if (fromObject.options[i].selected)
			{
				deleteOption(fromObject,i);
			}
		}
	}
	else
	{
		// -- nothing --
		//alert("please select one or more items first");
	}
}


/*
** Purpose:  
**
** Accepts:  hiddenField - 
**           value       - 
**           type        - 
*/
function editHiddenValue(hiddenField, value, type)
{
	if (type == "add")
	{
		if (hiddenField.value == "")
		{
			hiddenField.value = value;
		}
		else
		{
			hiddenField.value = hiddenField.value + ',' + value;

			// If doesn't already contain the value
//			re = new RegExp(value, 'gi');
//
//			if (hiddenField.value.search(re) != -1)
//			{
//				hiddenField.value = hiddenField.value + ',' + value;
//			}
		}
	}
	else if (type == "remove")
	{
		str = ("^" + value + "$|^" + value + ",|" + value + ",|," + value + "$");
		re = new RegExp(str, "gi");
		
		hiddenField.value = hiddenField.value.replace(re, '');
	}
	else
	{
		// Invalid type
		// -- nothing --
	}

	// Remove any trailing comma delimiters
	re = /,$/;
	hiddenField.value = hiddenField.value.replace(re, '');

	// Remove any double comma delimiters
	re = /,+/g;
	hiddenField.value = hiddenField.value.replace(re, ',');
}


/*
** Purpose:  Move the selected item up in the list.
**
** Accepts:  
*/
function MoveUp(list)
{
	sl = list.selectedIndex;
	if (sl>0)
	{
		oText = list.options[sl].text;
		oVal = list.options[sl].value;
		list.options[sl].text = list.options[sl-1].text;
		list.options[sl].value = list.options[sl-1].value;
		list.options[sl-1].text = oText;
		list.options[sl-1].value = oVal;
		list.selectedIndex = sl-1;
	}
	else if (sl==0)
	{
		alert("selected item is already at the top");
		return;
	}
	else
	{
		alert("please select an item first");
	}
}


/*
** Purpose:  Move the selected item down in the list.
**           -- bug: after one move, you've to submit it twice for next move. barely noticeable, so leaving it now
** Accepts:  
*/
function MoveDown(list)
{
	sl = list.selectedIndex;
	list_len = list.length-1;

	if (sl<list_len)
	{
		oText = list.options[sl].text;
		oVal = list.options[sl].value;

		list.options[sl].text    = list.options[sl+1].text;
		list.options[sl].value   = list.options[sl+1].value;
		list.options[sl+1].text  = oText;
		list.options[sl+1].value = oVal;

		list.selectedIndex       = sl+1;
	}
	else if (sl==list_len)
	{
		alert("selected item is already at the bottom");
		return;
	}
	else
	{
		alert("please select an item first");
	}
}


/*
** Purpose:  Selects all item in a list.
**
** Accepts:  object list - the page element containing the options (usual a select item).
*/
function SelectAll(list)
{
	for (var i=0; i<list.options.length;i++)
	{
		list.options[i].selected = true;
	}
}

