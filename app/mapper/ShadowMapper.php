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

		$res = $stmt->execute($values);

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

		// Get the contents of the shadow array and flush it before processing
		// (flush first so a doShadow failure cannot leave stale entries that cascade into the next call)
		$shadow_output = $stmt->db->getShadowOutput();
		$stmt->db->flushShadowOutput();
		self::doShadow($stmt->db, $shadow_output);

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

		isset($_SESSION['auth_session']['user']['id']) ? $updated_by = $_SESSION['auth_session']['user']['id'] : $updated_by = 1;

		// Phase 1: parse every PREPARE in the output and build shadow versions for manipulation queries.
		// Key: original MDB2 statement name  →  Value: ['key' => shadow_key, 'prepare' => shadow_PREPARE_sql]
		$shadow_map = array();

		foreach ($output as $out)
		{
			if (!preg_match('/^PREPARE\s+(MDB2\S*)\s+FROM\s+\'(.*)$/i', $out, $matches))
			{
				continue;
			}

			$orig_name  = $matches[1];
			$my_query   = $matches[2]; // SQL text, includes the closing ' from the PREPARE string
			$table_name = self::getTableName($my_query);

			if ($table_name === null || $table_name === '')
			{
				continue; // SELECT or unrecognised — no shadow needed
			}

			$table_name_shadow = $table_name . '_shadow';
			$upd = str_replace($table_name, $table_name_shadow, $my_query);
			$key = md5(rand());
			$add = false;

			if (preg_match('/^INSERT/i', $upd))
			{
				$upd = preg_replace('/INSERT INTO \S* \(/i', "INSERT INTO $table_name_shadow (shadow_type, shadow_updated_by, ", $upd);
				$upd = preg_replace('/\s*VALUES\s*\(/i', " VALUES (\'i\', \'" . $updated_by . "\', ", $upd);
				$add = true;
			}
			elseif (preg_match('/^UPDATE/i', $upd))
			{
				$type = preg_match('/UPDATE\s+\w+\s+SET\s+.*deleted\s*=\s*1.*/i', $upd) ? 'd' : 'u';
				$upd  = str_replace('UPDATE', 'INSERT INTO', $upd);
				$upd  = preg_replace('/\s*WHERE\s*/i', ", shadow_type = \'$type\', shadow_updated_by = \'$updated_by\', ", $upd);
				$add  = true;
			}
			elseif (preg_match('/^DELETE/i', $upd))
			{
				$upd = str_replace('DELETE FROM', 'INSERT INTO', $upd);
				// Trailing ', ' keeps the WHERE condition as a valid column assignment in the SET list
				$upd = preg_replace('/\s*WHERE\s*/i', " SET shadow_type = \'d\', shadow_updated_by = \'$updated_by\', ", $upd);
				$add = true;
			}

			if ($add)
			{
				$shadow_map[$orig_name] = array(
					'key'     => $key,
					'prepare' => 'PREPARE MDB2_SHADOW_STATEMENT_mysqli_' . $key . ' FROM \'' . $upd,
				);
				if ($debug) echo '<p style="color: green">Shadow prepared for: ' . $orig_name . '</p>';
			}
		}

		// Phase 2: walk through the output again, collecting SET statements into a buffer.
		// When an EXECUTE is seen, check whether the executed statement name has a shadow.
		// If so, emit: shadow PREPARE + buffered SETs + shadow EXECUTE.
		// SET statements that belong to a non-shadowed statement are discarded.
		$shadow       = array();
		$pending_sets = array();

		foreach ($output as $out)
		{
			if (preg_match('/^SET/i', $out))
			{
				$pending_sets[] = $out;
			}
			elseif (preg_match('/^EXECUTE\s+(MDB2\S*)/i', $out, $matches))
			{
				$orig_name = $matches[1];

				if (isset($shadow_map[$orig_name]))
				{
					$key         = $shadow_map[$orig_name]['key'];
					$param_count = count($pending_sets);

					$shadow[] = $shadow_map[$orig_name]['prepare'];
					foreach ($pending_sets as $set)
					{
						$shadow[] = $set;
					}

					$exec_str = 'EXECUTE MDB2_SHADOW_STATEMENT_mysqli_' . $key;
					if ($param_count > 0)
					{
						$exec_str .= ' USING @' . implode(', @', range(0, $param_count - 1));
					}
					$shadow[] = $exec_str;

					if ($debug) echo '<p style="color: green">Shadow EXECUTE for: ' . $orig_name . ' (' . $param_count . ' params)</p>';
				}
				else
				{
					if ($debug) echo '<p style="color: red">No shadow for EXECUTE: ' . $orig_name . '</p>';
				}

				$pending_sets = array(); // consumed or discarded
			}
		}

		if ($debug) echo '<div style="border: 5px solid green; margin: 10px; padding: 10px">';
		if ($debug) echo '<p>Execute ' . count($shadow) . ' shadow statement(s):</p>';
		if ($debug) echo "<blockquote><pre>";
		foreach ($shadow as $idx => $str)
		{
			if ($debug) echo "<br />\$shadow[$idx] = $str";
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