<?php


/*
** File:         SafeSQL.class.php
** Description:  SafeSQL: db access library extension for handling SQL injection attacks.
** Version:      1.0.2
**
** For history of changes please see ChangeLog.txt
**
** This library is free software; you can redistribute it and/or
** modify it under the terms of the GNU Lesser General Public
** License as published by the Free Software Foundation; either
** version 2.1 of the License, or (at your option) any later version.
**
** This library is distributed in the hope that it will be useful,
** but WITHOUT ANY WARRANTY; without even the implied warranty of
** MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
** Lesser General Public License for more details.
**
** You should have received a copy of the GNU Lesser General Public
** License along with this library; if not, write to the Free Software
** Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/


class SafeSQL
{
	
	function query($query_string, $query_vars, $escape_mode = 'mysql')
	{
		if (is_array($query_vars))
		{
			$_var_count = count($query_vars);
			
			// Searches $query_string for all matches to the regular expression and puts them in $matches.
			// Orders results so that $matches[0] is an array of full pattern matches, 
			$_placeholder_count = preg_match_all('/%([sSiIfF]!?|[cCqQ])/', $query_string, $match);
			
			// Check equal number of variables and placeholders are supplied.
			if ($_var_count != $_placeholder_count)
			{
				trigger_error('Unmatched number of vars ('.$_var_count.') and % placeholders ('.$_placeholder_count.'): '.$query_string);
			}
			
			// Get string position for each element.
			$var_pos = array();
			$curr_pos = 0;
			for ($x = 0; $x < $_var_count; $x++)
			{
				$var_pos[$x] = strpos($query_string, $match[0][$x], $curr_pos);
				$curr_pos = $var_pos[$x] + 1;
			}
			
			// Build query from passed in variables and escape them
			for ($x = $_var_count - 1; $x >= 0; $x--)
			{
				// Escape the string
				$query_vars[$x] = SafeSQL::sql_escape($query_vars[$x], $escape_mode);
				
				if (in_array($match[0][$x], array('%S','%I','%F','%C','%Q')))
				{
					// get positions of [ and ]
					$right_pos = strpos($query_string, ']', $var_pos[$x]);
					$str_slice = substr($query_string, 0, $var_pos[$x]);
					$left_pos = strrpos($str_slice,'[');
					
					if ($right_pos === false || $left_pos === false)
					{
						trigger_error('missing or unmatched brackets for % placeholder(s): ' . $query_string);
					}
					
					if($query_vars[$x] == '')
					{
						// remove entire part of string
						$query_string1 = $query_string;
						$query_string = substr_replace($query_string1, '', $left_pos, ($right_pos-$left_pos)+1);
					}
					else
					{
						// remove the brackets only, and replace %S
						$query_string = substr_replace($query_string, '', $right_pos, 1);
						$query_string = substr_replace($query_string, SafeSQL::convert_var($query_vars[$x],$match[0][$x]), $var_pos[$x], 2);
						$query_string = substr_replace($query_string, '', $left_pos, 1);
					}
				}
				elseif (in_array($match[0][$x], array('%S!','%I!','%F!')))
				{
					// get positions of [ and ];
					$right_pos = strpos($query_string, ']', $var_pos[$x]);
					$str_slice = substr($query_string, 0, $var_pos[$x]);
					$left_pos = strrpos($str_slice,'[');
					
					if ($right_pos === false || $left_pos === false)
					{
						trigger_error('missing or unmatched brackets for % placeholder(s): ' . $query_string);
					}
					else
					{
					
						if ($query_vars[$x] == '')
						{
							// remove entire part of string and replace with null
							$query_string = substr_replace($query_string, 'NULL', $left_pos, ($right_pos-$left_pos)+1);
						}
						else
						{
							// remove the brackets only, and replace %S
							$query_string = substr_replace($query_string, '', $right_pos, 1);
							$query_string = substr_replace($query_string, SafeSQL::convert_var($query_vars[$x],$match[0][$x]), $var_pos[$x], strlen($match[0][$x]));
							$query_string = substr_replace($query_string, '', $left_pos, 1);
						}
					}
				}
				elseif (in_array($match[0][$x], array('%s!','%i!','%f!')))
				{
					// get positions of ' and ';
					$right_pos = strpos($query_string, "'", $var_pos[$x]);
					$str_slice = substr($query_string, 0, $var_pos[$x]);
					$left_pos  = strrpos($str_slice, "'");
					$substr1   = substr($query_string, $var_pos[$x]-1, 1);
					$substr2   = substr($query_string, $left_pos+($right_pos-$left_pos), 1);

					if (empty($query_vars[$x]) && $substr1 == "'" && $substr1 == "'")
					{
							// remove the surrounding single quotes
							$query_string = substr_replace($query_string, '', $right_pos, 1);
							$query_string = substr_replace($query_string, SafeSQL::convert_var($query_vars[$x],$match[0][$x]), $var_pos[$x], strlen($match[0][$x]));
							$query_string = substr_replace($query_string, '', $left_pos, 1);
					}
					else
					{
						$query_string = substr_replace($query_string, SafeSQL::convert_var($query_vars[$x], $match[0][$x]), $var_pos[$x], strlen($match[0][$x]));
					}
				}
				else
				{
					$query_string = substr_replace($query_string, SafeSQL::convert_var($query_vars[$x], $match[0][$x]), $var_pos[$x], strlen($match[0][$x]));
				}
			}
		}
		
		return $query_string;
	}
	
	
	/*
	** Purpose:  Recursively escape variables/arrays for SQL use.
	**
	** Accepts:  $var         - the variable/array to escape.
	**           $escape_mode - the mode to escape for.
	**
	** Returns:  The escaped variables/array for SQL use.
	*/
	function sql_escape($var, $escape_mode)
	{
		if (is_array($var))
		{
			foreach($var as $element)
			{
				$newvar[] = SafeSQL::sql_escape($element, $escape_mode);
			}
			return $newvar;
		}
		else
		{
			switch ($escape_mode)
			{
				case 'ansi':
					return str_replace("'", "''", $var);
				
				case 'mysql':  // fall-through
				default:
					return addslashes($var);
			}
		}
	}
	
	
	/*
	** Function: conver_var
	**
	** Purpose:  Convert a variable to the given type.
	**
	** Accepts:  $var  - the variable
	**           $type - the type to convert to:
	**                     %s, %S   - cast to string
	**                     %s!, %S! - cast to string if valid, else return 'NULL' string
	**                     %i, %I   - cast to integer
	**                     %i!, %I! - cast to integer if valid, else return 'NULL' string
	**                     %f, %F   - cast to float
	**                     %f!, %F! - cast to float if valid, else return 'NULL' string
	**                     %c, %C   - comma separate, cast each element to integer
	**                     %q, %Q   - quote and comma separate each element as a string 
	*/
	function convert_var($var, $type)
	{
		switch($type)
		{
			case '%s':  // fall-through
			case '%S':
				// -- nothing --
				break;
				
			case '%s!':  // fall-through
			case '%S!':
				if (empty($var))
				{
					$var = 'NULL';
				}
				break;

			case '%i':  // fall-through
			case '%I':
				// cast to integer
				settype($var, 'integer');
				break;

			case '%i!':  // fall-through
			case '%I!':
				$v = trim($var);
				if ( $v !== '0' && (empty($v) || !is_numeric($v)) )
				{
					$var = 'NULL';
					settype($var, 'string');
				}
				else
				{
					// cast to integer
					settype($var, 'integer');
				}
				break;
			
			case '%f':  // fall-through
			case '%F':
				// cast to float
				settype($var, 'float');
				break;

			case '%f!':  // fall-through
			case '%F!':
				$v = trim($var);
				if ( $v != '0' && (empty($var) || !is_numeric($v)) )
				{
					$var = 'NULL';
					settype($var, 'string');
				}
				else
				{
					// cast to float
					settype($var, 'float');
				}
				break;
			
			case '%c':  // fall-through
			case '%C':
				// comma separate
				settype($var, 'array');
				for ($x = 0 , $y = count($var); $x < $y; $x++)
				{
					// cast to integers
					settype($var[$x], 'integer');
				}
				$var = implode(',', $var);
				if ($var == '')
				{
					// force 0, keep syntax from breaking
					$var = '0';
				}
				break;

			case '%q':  // fall-through
			case '%Q':
				settype($var, 'array');
				// quote comma separate
				$var = "'" . implode("','",$var) . "'";
				break;
		}
		
		return $var;
	}
	
	
}


?>