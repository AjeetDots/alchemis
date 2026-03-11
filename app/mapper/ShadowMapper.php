<?php

/**
 * Defines the app_mapper_ShadowMapper class.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

require_once('app/mapper/Mapper.php');

/**
 * Responsible for handling the transition from database to object and adds
 * shadow capabilities.
 * @package Framework
 */
abstract class app_mapper_ShadowMapper extends app_mapper_Mapper
{

	/**
	 * Overwrites parent function to add shadow handling.
	 * @param MDB2_Statement_Common $stmt the statement to execute
	 * @param array $values array of data values to pass to use with the statement
	 * @return a result handle or MDB2_OK on success, a MDB2 error on failure
	 */
	// public function doStatement(MDB2_Statement_Common $stmt, $values)
	public function doStatement($stmt, $values = null)
	{
		$this->debug = (get_class($stmt) == 'MDB2_Error');

		if ($this->debug) echo "<pre>";
		if ($this->debug) print_r($stmt);
		if ($this->debug) echo "</pre>";

		if ($this->debug) echo "<h2>app_mapper_ShadowMapper::doStatement(".get_class($stmt).", $values)</h2>";
		if ($this->debug) echo "<pre>";
		if ($this->debug) print_r($values);
		if ($this->debug) echo "</pre>";
		if ($this->debug) echo "\$stmt type = " . get_class($stmt);

		// Ensure MDB2 debug option is set. This records the database statements from which the relevant shadow table
		// statements can be determined.
		$stmt->db->setOption('debug', true);

		try
		{
			$res = $stmt->execute($values);
		}
		catch (Exception $e)
		{
			exit($e->getMessage());
		}

		if (MDB2::isError($res))
		{
			// if deadlock retry query
			if ($res->getCode() == MDB2_ERROR_DEADLOCK) {
				$res = $stmt->execute($values);
				if (MDB2::isError($res)) throw new app_base_MDB2Exception($res);
			} else {
				throw new app_base_MDB2Exception($res);
			}
		}

		// Get the contents of the shadow array and pass to processing function
		$shadow_output = $stmt->db->getShadowOutput();
		self::doShadow($stmt->db, $shadow_output);

		// Clear the shadow output ready for next time
		$stmt->db->flushShadowOutput();

		return $res;
	}

	/**
	 *
	 * @param MDB2_Driver_Common $db
	 * @param array $output
	 */
	protected function doShadow(MDB2_Driver_Common $db, $output)
	{
		$debug = false;
		if ($debug) echo "<h1>app_mapper_ShadowMapper::doShadow()</h1>";
		if ($debug) echo '<p>' . get_class($db) . '</p>';
		if ($debug) echo '<pre>';
		if ($debug) print_r($output);
		if ($debug) echo '</pre>';

		$key = null;
		$shadow = array();
		$param_count = 0;
		$output_count = 0;
		$current_named_statement = null;

		foreach ($output as $out)
		{
			if ($debug) echo '<div style="border: 1px solid black; margin: 10px; padding: 10px">';
			if ($debug) echo "<b>\$output[$output_count]:    $out</b><br />";

			if (preg_match('/^INSERT/i', $out))
			{
				if ($debug) echo '<p style="color: red">INSERT query - do not add</p>';
//				$table_name = self::getTableName($out);
//				$table_name_shadow = $table_name . '_shadow';
//				$upd = str_replace($table_name, $table_name_shadow, $out);
//				$type = 'i';
//				echo "<br />INSERT QUERY: $upd";
//				$upd = preg_replace('/INSERT INTO \S* \(/i', "INSERT INTO $table_name_shadow (type, ", $upd);
//				$upd = preg_replace('/\s*VALUES\s*\(/i', " VALUES ('i', ", $upd);
//				$shadow[] = $upd;
			}
			elseif (preg_match('/^UPDATE/i', $out))
			{
				if ($debug) echo '<p style="color: red">UPDATE query - do not add</p>';
//				$table_name = self::getTableName($out);
//				$table_name_shadow = $table_name . '_shadow';
//				$upd = str_replace($table_name, $table_name_shadow, $out);
//				if (preg_match('/UPDATE\s+\w+\s+SET\s+.*deleted\s*=\s*1.*/i', $upd))
//				{
//					echo "<br />deleted type";
//					$type = 'd';
//				}
//				else
//				{
//					echo "<br />updated type";
//					$type = 'u';
//				}
//				$upd = str_replace('UPDATE', 'INSERT INTO', $upd);
////					$upd = preg_replace('/\s*WHERE.*/i', '\'', $upd);
////					echo "<br />Q: $upd";
////					$mats = preg_split('/(\s*WHERE\s*)/i', $upd);
////					print_r($mats);
////					echo "<br />Q: $upd";
//				$upd = preg_replace('/\s*WHERE\s*/i', ", type = \'$type\', ", $upd);
//				$upd = preg_replace('/deleted\s+=\s+1,?\s*/i', "", $upd);
////				echo "<br />UPDATE QUERY: $upd";
////				echo "<br />After:         <b>$upd</b>";
////				$upd = $upd
			}
			elseif (preg_match('/^DELETE/i', $out))
			{
				if ($debug) echo '<p style="color: red">DELETE query - do not add</p>';
			}
			elseif (preg_match('/^SELECT/i', $out))
			{
				if ($debug) echo '<p style="color: red">SELECT query - do not add</p>';
			}
			elseif (preg_match('/^PREPARE MDB2\S* FROM \'(.*)$/i', $out, $matches))
			{
				$param_count = 0;
				$key = md5(rand());
				$table_name = self::getTableName($matches[1]);

				// If we can't safely determine a non-empty table name, skip shadow handling
				if ($table_name === null || $table_name === '') {
					if ($debug) echo '<p style="color: red">Do not add to shadow: unable to determine table name</p>';
					continue;
				}

				$table_name_shadow = $table_name . '_shadow';

				$q = 'PREPARE MDB2_SHADOW_STATEMENT_mysqli_' . $key . ' FROM \'';

				$my_query = $matches[1];
				$upd = str_replace($table_name, $table_name_shadow, $my_query);

				// Default is not to add to shadow
				$add_to_shadow = false;

				// Get ID of current user
				// TODO
				//  - update using SessionRegistry(?) object
				isset($_SESSION['auth_session']['user']['id']) ? $updated_by = $_SESSION['auth_session']['user']['id'] : $updated_by = 1;

				if (preg_match('/^INSERT/i', $upd))
				{
					$current_named_statement = 'MDB2_SHADOW_STATEMENT_mysqli_' . $key;
					$type = 'i';
					if ($debug) echo "<br />INSERT QUERY: $upd";
					$upd = preg_replace('/INSERT INTO \S* \(/i', "INSERT INTO $table_name_shadow (shadow_type, shadow_updated_by, ", $upd);
					$upd = preg_replace('/\s*VALUES\s*\(/i', " VALUES (\'$type\', \'" . $updated_by . "\', ", $upd);
					$add_to_shadow = true;
				}
				elseif (preg_match('/^UPDATE/i', $upd))
				{
					$current_named_statement = 'MDB2_SHADOW_STATEMENT_mysqli_' . $key;

					// Determine the type of query being run
					// Rather than actually deleting an original row, a 'deleted' field is set to '1'. By leaving the
					// row in place, we can still take advantage of foreign key constraints.
					if (preg_match('/UPDATE\s+\w+\s+SET\s+.*deleted\s*=\s*1.*/i', $upd))
					{
						$type = 'd';
					}
					else
					{
						$type = 'u';
					}

					// Replace UPDATE of original query to INSERT INTO required for shadow table statement
					$upd = str_replace('UPDATE', 'INSERT INTO', $upd);

					// Add 'type' and 'updated_by' fields to the statement
					$upd = preg_replace('/\s*WHERE\s*/i', ", shadow_type = \'$type\', shadow_updated_by = \'$updated_by\', ", $upd);

					// Ensure any reference to a deleted column is removed
//					$upd = preg_replace('/deleted\s+=\s+1,?\s*/i', "", $upd);

					// Set flad to add to list of shadow statements
					$add_to_shadow = true;
				}
				elseif (preg_match('/^DELETE/i', $upd))
				{
					$current_named_statement = 'MDB2_SHADOW_STATEMENT_mysqli_' . $key;
					$type = 'd';
					if ($debug) echo "<br />DELETE QUERY: $upd";
					$upd = str_replace('DELETE FROM', 'INSERT INTO', $upd);
					if ($debug) echo "<br />DELETE QUERY: $upd";

					// Add 'type' and 'updated_by' fields
					$upd = preg_replace('/\s*WHERE\s*/i', " SET shadow_type = \'d\', shadow_updated_by = \'$updated_by\'", $upd);
					if ($debug) echo "<br />DELETE QUERY: $upd";
					$add_to_shadow = true;
				}
				else
				{
					if ($debug) echo '<p style="color: red">Do not add to shadow: query is not INSERT, UPDATE or DELETE</p>';
				}

				if ($add_to_shadow)
				{
					$q .= $upd;
					if ($debug) echo '<p style="color: green">Add to shadow: ' . $q . '</p>';
					$shadow[] = $q;
				}
			}
			elseif (preg_match('/^SET/i', $out))
			{
				if (!is_null($current_named_statement))
				{
					if ($debug) echo '<p style="color: green">Add SET to shadow: <strong>' . $out . '</strong></p>';
					$shadow[] = $out;
					$param_count++;
					if ($debug) echo '<p style="color: green">Param Count: <strong>' . $param_count . '</strong></p>';
				}
				else
				{
					if ($debug) echo '<p style="color: red">Do not add SET to shadow: no statement prepared</p>';
				}
			}
			elseif (preg_match('/^EXECUTE/i', $out))
			{
				if (!is_null($current_named_statement))
				{
					$str = 'EXECUTE MDB2_SHADOW_STATEMENT_mysqli_' . $key;

					if ($param_count > 0)
					{
						$str .= ' USING ';
						for ($i = 0; $i < $param_count; $i++)
						{
							$str .= '@'. $i;
							if ($i != $param_count-1)
							{
								$str .= ', ';
							}
						}
					}
					if ($debug) echo '<p style="color: green">Add EXECUTE to shadow: <strong>' . $out . '</strong></p>';
					$shadow[] = $str;
				}
				else
				{
					if ($debug) echo '<p style="color: red">Do not add EXECUTE to shadow: no statement prepared</p>';
				}
			}
			else
			{
				// TODO
				//  - add exception / logging here?
			}

			if ($debug) echo "</div>";
			$output_count++;
		}

		if ($debug) echo '<div style="border: 5px solid green; margin: 10px; padding: 10px">';
		if ($debug) echo '<p>Execute ' . count($shadow) . ' shadow statement(s):</p>';
		if ($debug) echo "<blockquote><pre>";
		foreach ($shadow as $key => $str)
		{
			if ($debug) echo "<br />\$shadow[$key] = $str";
			$db->exec($str);
		}
		if ($debug) echo "</pre></blockquote>";
		if ($debug) echo "</div>";
	}

	/**
	 * Extracts the table name from a INSERT, UPDATE or DELETE SQL statement.
	 * @param string $query a SQL statement
	 * @return string the table name
	 */
	protected function getTableName($query)
	{
		if (preg_match('/^(INSERT\s*INTO\s*)(\S*)(\s*\(.*\)\s*VALUES).*$/i', $query, $matches))
		{
			return $matches[2];
		}
		elseif (preg_match('/^(UPDATE\s*)(\S*).*$/i', $query, $matches))
		{
			return $matches[2];
		}
		elseif (preg_match('/^(DELETE\s*FROM\s*)(\S*).*$/i', $query, $matches))
		{
			return $matches[2];
		}
		return null;
	}

}

?>