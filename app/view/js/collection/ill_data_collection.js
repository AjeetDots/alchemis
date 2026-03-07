
/* --JavaScript Collection Object-- */

function ill_Data_Collection() {
/* --CCollection object-- */
	var lsize = 0;
	var pointer = 0;
//	var loading = false;
	
	this.add = _add;
	this.remove = _remove;
	this.isEmpty = _isEmpty;
	this.size = _size;
	this.clear = _clear;
	this.clone = _clone;
	
	this.getNext = _getNext;
	this.getPrevious = _getPrevious;
	this.getObjectAt = _getObjectAt;
	this.getCurrent = _getCurrent;
	this.isValid = _isValid;
	this.goToKey = _goToKey;
	this.goToValue = _goToValue;
	this.goToCompanyId = _goToCompanyId;
	this.goToPostId = _goToPostId;
	this.goToPostInitiativeId = _goToPostInitiativeId;
	this.bof = _bof;
	this.eof = _eof;	
	
//	this.isLoading = loading;
	
	
	function _add(newItem) 
	{
	/* --adds a new item to the collection-- */
	     if (newItem == null) return;
		      lsize++;
		      
	     this[(lsize - 1)] = newItem;
	}
	
	function _remove(index) 
	{
	/* --removes the item at the specified index-- */
	     if (index < 0 || index > this.length - 1) return;
	     this[index] = null;
	
	     /* --reindex collection-- */
	     for (var i = index; i <= lsize; i++)
	          this[i] = this[i + 1];
	
	     lsize--;
	}
	
	function _isEmpty() 
	{ 
		/* --returns boolean if collection is/isn't empty-- */
		return lsize == 0 
	}     
	
	function _size()
	{
		/* --returns the size of the collection-- */
		return lsize
	}
	
	function _clear() 
	{
	/* --clears the collection-- */
	     for (var i = 0; i < lsize; i++)
	          this[i] = null;
	
	     lsize = 0;
	}
	
	function _getNext()
	{
		pointer++;
		if (this.eof())
		{
			return false;
		}
		else
		{
			return this.getCurrent();
		}
	}
	
	function _getPrevious()
	{
		pointer--;
		if (this.bof())
		{
			return false;
		}
		else
		{
			return this.getCurrent();
		}
	}	
	 
	function _bof()
	{
		/* are we before the beginning of dataset?*/
		if (pointer < 0)
		{
			pointer = 1;
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function _eof()
	{
		/* are we end the end of dataset?*/
		if (pointer >= lsize)
		{
			pointer = lsize-1;
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function _getObjectAt(num)
	{
		/* Returns the object at the given index. */
	
	
		if (num >= lsize || num < 0)
		{
			return null;
		}
		
		if (this[num] != null)
		{
			return this[num];
		}
	}
	
	function _getCurrent()
	{
		
		return this.getObjectAt(pointer);
	}
	
	function _goToKey()
	{
			
	}
	
	function _goToValue(search)
	{
		for (var i = 0; i < lsize; i++)
		{
			if (this[i] == search)
			{
				pointer = i;
				return true;			
			}
		}
     }
	
	function _goToCompanyId(search)
	{
		for (var i = 0; i < lsize; i++)
		{
			if (this[i].company_id == search)
			{
				pointer = i;
				return true;			
			}
		}
     }
     
    function _goToPostId(search)
	{
		for (var i = 0; i < lsize; i++)
		{
			if (this[i].post_id == search)
			{
				pointer = i;
				return true;			
			}
		}
     }
     
    function _goToPostInitiativeId(search)
	{
		for (var i = 0; i < lsize; i++)
		{
			if (this[i].post_initiative_id == search)
			{
				pointer = i;
				return true;			
			}
		}
     }
     
	function _isValid()
	{
		/*  Confirms that there is an element at the current pointer position. */
		var t = this.getCurrent();
		return !(t == 'undefined' || t === null);

	}
	
	function _clone() 
	{
	/* --returns a copy of the collection-- */
	     var c = new CCollection();
	
	     for (var i = 0; i < lsize; i++)
	          c.add(this[i]);
	
	     return c;
	}

}