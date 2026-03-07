<?php

// ==================================================================
//  Author: Justin Vincent (justin@visunet.ie)
//    Web:     http://php.justinvincent.com
//    Name:     ezSQL
//     Desc:     Class to make it very easy to deal with mySQL database connections.
//
// !! IMPORTANT !!
//
//  Please send me a mail telling me what you think of ezSQL
//  and what your using it for!! Cheers. [ justin@visunet.ie ]
//
// ==================================================================
// User Settings -- CHANGE HERE

//define("EZSQL_DB_USER", "");            // <-- mysql db user
//define("EZSQL_DB_PASSWORD", "");        // <-- mysql db password
//define("EZSQL_DB_NAME", "mysql");        // <-- mysql db pname
//define("EZSQL_DB_HOST", "localhost");    // <-- mysql server host


/*
 * ----- Start of EasySql Constants -----
 */
define('EZSQL_VERSION', '2.0.0');
define('OBJECT', 'OBJECT');
define('ARRAY_A', 'ARRAY_A');
define('ARRAY_N', 'ARRAY_N');
/*
 * ----- End of EasySql Constants -----
 */


/**
 * EasySql Class<br />
 *
 * @access  public
 * @package uk.co.illumen
 */
class EasySql
{

    var $trace = false;         // same as $debug_all
    public $debug_all = false;  // same as $trace
    var $show_errors = true;
    var $num_queries = 0;
    var $last_query;
    var $col_info;
    var $debug_called;
    var $vardump_called;
    public $insert_id;
    public $dbh;
    public $rows_affected;
    public $result;
    public $last_result;
    public $num_rows, $func_call;


    /**
     * EasySql Constructor.<br />
     * Connects to the server and selects a database.
     *
     * @param  string username
     * @param  string password
     * @param  string database name
     * @param  string host name
     * @access public
     */
    function __construct($dbuser, $dbpassword, $dbname, $dbhost)
    {
        try {
            // Use PDO connection string that matches your working test.php
            $pdo = new PDO(
                "mysql:host=$dbhost;charset=utf8mb4",
                $dbuser,
                $dbpassword,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_TIMEOUT => 60
                ]
            );
            
            // Select database after connection if specified
            if ($dbname) {
                $pdo->exec("USE `$dbname`");
            }
            
            // Store PDO connection
            $this->dbh = $pdo;
            
        } catch (Exception $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }


    /**
     * Selects a new database to work with the current database connection.
     *
     * @param  string database name
     * @return boolean
     * @access public
     */
    public function select($dbname)
    {
        if (!mysqli_select_db($this->dbh, $dbname)) {
            $this->print_error("<ol><b>Error selecting database <u>$dbname</u>!</b><li>Are you sure it exists?<li>Are you sure there is a valid database connection?</ol>");
            return false;
        }
        return true;
    }


    /**
     * Format a string correctly in order to stop accidental mal-formed queries under all PHP 
     * conditions.
     * 
     * $db->escape() makes any string safe to use as a value in a query under all PHP 
     * conditions. I.E. if magic quotes are turned on or off. Note: Should not be 
     * used by itself to guard against SQL injection attacks. The purpose of this function is to stop accidental mal formed queries.
     * 
     * @param  string
     * @access private 
     */
    private function escape($str)
    {
        return mysqli_escape_string($this->dbh, stripslashes($str));
    }


    /**
     * Print SQL/DB error.
     *
     * @access private
     */
    private function print_error($str = '')
    {
        // All errors go to the global error array $EZSQL_ERROR
        global $EZSQL_ERROR;

        // If no special error string then use mysql default
        if (!$str) {
            $str      = $this->dbh->error;
            $error_no = $this->dbh->errno;
        }

        // Log this error to the global array
        $EZSQL_ERROR[] = array(
        'query'     => $this->last_query,
        'error_str' => $str,
        'error_no'  => $error_no
        );

        // Is error output turned on or not
        if ($this->show_errors) {
            // If there is an error then take note of it
            print "<blockquote><font face=arial size=2 color=ff0000>";
            print "<b>SQL/DB Error --</b> ";
            print "[<font color=000077>$str</font>]";
            print "</font></blockquote>";
        } else {
            return false;
        }
    }


    /**
     * @deprecated
     * @see        showErrors()
     */
    public function show_errors()
    {
        $this->showErrors();
    }


    /**
     * Turn ezSQL error output to browser on.
     * 
     * $db->show_errors() turns ezSQL error output to the browser on. If you have not used the 
     * function $db->hide_errors this function (show_errors) will have no effect.
     * 
     * @access public
     */
    public function showErrors()
    {
        $this->show_errors = true;
    }


    /**
     * @deprecated
     * @see        hideErrors()
     */
    public function hide_errors()
    {
        $this->hideErrors();
    }


    /**
     * $db->hide_errors � turn ezSQL error output to browser off
     * 
     * $db->hide_errors() stops error output from being printed to the web client. If you would 
     * like to stop error output but still be able to trap errors for debugging or for your own 
     * error output function you can make use of the global error array $EZSQL_ERROR.
     * 
     * Note: If there were no errors then the global error array $EZSQL_ERROR will evaluate to 
     * false. If there were one or more errors then it will have  the following structure. Errors 
     * are added to the array in order of being called.
     * 
     * @access public
     */
    public function hideErrors()
    {
        $this->show_errors = false;
    }


    /**
     * Kill cached query results
     */
    private function flush()
    {
        // Get rid of these
        $this->last_result = null;
        $this->col_info    = null;
        $this->last_query  = null;
    }


    /**
     * TODO - confirm works as expected
     * 
     * Send a query to the database (and if any results, cache them)
     * 
     * $db->query() sends a query to the currently selected database. It should be noted 
     * that you can send any type of query to the database using this command. If there are 
     * any results generated they will be stored and can be accessed by any ezSQL function as long 
     * as you use a null query. If there are results returned the function will return true if no 
     * results the return will be false
     * 
     * 
     * INSERT / DELETE / UPDATE / REPLACE QUERIES
     *  - all return boolean true if executed successfully, false otherwise
     *  - update / delete queries may execute successfully but not actually affect any rows
     *
     * @param string the SQL query string
     * 
     * @return boolean
     * @access public
     */
    public function query($query)
    {
        // For reg expressions
        $query = trim($query);

        // initialise return
        $return_val = 0;

        // Flush cached values
        $this->flush();

        // Log how the function was called
        $this->func_call = "\$db->query(\"$query\")";

        // Keep track of the last query for debug..
        $this->last_query = $query;

        // Perform the query via PDO
        $this->result = $this->dbh->query($query);
        $this->num_queries++;

        // If there is an error then take note of it..
        if (!$this->result) {
            $this->print_error();
            return false;
        }

        // Query was an insert, delete, update, replace
        if (preg_match("/^(insert|delete|update|replace)\s+/i", $query)) {
            $this->rows_affected = $this->result->rowCount();

            // Take note of the insert_id
            if (preg_match("/^(insert|replace)\s+/i", $query)) {
                $this->insert_id = $this->dbh->lastInsertId();
            }

            $return_val = true;

            // Log query if possible
            global $log;
            if (isset($log)) {
                $log->log('SQL: ' . $query);
            }
        }
        // Query was an select
        else {
            // Store Query Results
            $num_rows = 0;
            while ($row = $this->result->fetchObject()) {
                // Store results as objects within main array
                $this->last_result[$num_rows] = $row;
                $num_rows++;
            }

            // Log number of rows the query returned
            $this->num_rows = $num_rows;

            // Return number of rows selected
            $return_val = $this->num_rows;
        }

        // If debug ALL queries
        $this->trace || $this->debug_all ? $this->debug() : null;

        return $return_val;
    }


    /**
     * @deprecated
     * @see        getVariable()
     */
    public function get_var($query = null, $x = 0, $y = 0)
    {
        return $this->getVariable($query, $x, $y);
    }


    /**
     * get one variable, from one row, from the database (or previously cached results)
     * 
     * $db->get_var() gets one single variable from the database or previously cached results. This function is very useful for evaluating query results within logic statements such as if or switch. If the query generates more than one row the first row will always be used by default. If the query generates more than one column the leftmost column will always be used by default. Even so, the full results set will be available within the array $db->last_results should you wish to use them.
     * 
     * @param string
     * @param integer
     * @param integer
     * 
     * @return var
     * 
     * @access public
     */
    public function getVariable($query = null, $x = 0, $y = 0)
    {
        // Log how the function was called
        $this->func_call = "\$db->get_var(\"$query\",$x,$y)";

        // If there is a query then perform it if not then use cached results
        if ($query) {
            $this->query($query);
        }

        // Extract var out of cached results based x,y vals
        if ($this->last_result[$y]) {
            $values = array_values(get_object_vars($this->last_result[$y]));
        }

        // If there is a value return it else return null
        return (isset($values[$x]) && $values[$x] !== '') ? $values[$x] : null;
    }


    /**
     * @deprecated
     * @see        getRow()
     */
    function get_row($query = null, $output = OBJECT, $y = 0)
    {
        return $this->getRow($query, $output, $y);
    }


    /**
     * get one row from the database (or previously cached results)
     * 
     * $db->get_row() gets a single row from the database or cached results. If the 
     * query returns more than one row and no row offset is supplied the first row 
     * within the results set will be returned by default. Even so, the full results will be 
     * cached should you wish to use them with another ezSQL query.
     * 
     * @param string
     * @param constant
     * @param integer
     * 
     * @access public
     */
    public function getRow($query = null, $output = OBJECT, $y = 0)
    {
        // Log how the function was called
        $this->func_call = "\$db->get_row(\"$query\",$output,$y)";

        // If there is a query then perform it if not then use cached results
        if ($query) {
            $this->query($query);
        }

        // If the output is an object then return object using the row offset
        if ($output == OBJECT) {
            return $this->last_result[$y] ? $this->last_result[$y] : null;
        }
        // If the output is an associative array then return row as such
        elseif ($output == ARRAY_A) {
            return $this->last_result[$y] ? get_object_vars($this->last_result[$y]) : null;
        }
        // If the output is an numerical array then return row as such
        elseif ($output == ARRAY_N) {
            return $this->last_result[$y] ? array_values(get_object_vars($this->last_result[$y])) : null;
        }
        // If invalid output type was specified
        else {
            $this->print_error('$db->get_row(string query, output type, int offset) -- Output type must be one of: OBJECT, ARRAY_A, ARRAY_N');
        }
    }


    /**
     * @deprecated
     * @see        getColumn()
     */
    public function get_col($query = null, $x = 0)
    {
        return $this->getColumn($query, $x);
    }


    /**
     * Function to get 1 column from the cached result set based in X index
     * 
     * get one column from query (or previously cached results) based on column offset
     * 
     * $db->get_col � get one column from query (or previously cached results) based on column offset
     * 
     * Description
     * $db->get_col( string query / null [, int column offset] )
     * $db->get_col() extracts one column as one dimensional array based on a column offset. If 
     * no offset is supplied the offset will defualt to column 0. I.E the first column. If a null query 
     * is supplied the previous query results are used.
     *      * 
     *
     * @param  string
     * @param  integer
     * @access public
     */
    public function getColumn($query = null, $x = 0)
    {
        $new_array = array();

        // If there is a query then perform it if not then use cached results.
        if ($query) {
            $this->query($query);
        }

        // Extract the column values
        for ($i = 0; $i < count($this->last_result); $i++) {
            $new_array[$i] = $this->get_var(null, $x, $i);
        }

        return $new_array;
    }


    /**
     * @deprecated
     * @see        getResults()
     */
    public function get_results($query = null, $output = OBJECT)
    {
        return $this->getResults($query, $output);
    }


    /**
     * Get multiple row result set from the database (or previously cached results).
     * 
     * Gets multiple rows of results from the database based on query and returns them as a 
     * multi-dimensional array. Each element of the array contains one row of results and can be 
     * specified to be either an object, associative array or numerical array. If no results are 
     * found then the function returns false enabling you to use the function within logic 
     * statements such as if.
     * 
     * 
     * Example 1 � Return results as objects (default)
     * 
     * Returning results as an object is the quickest way to get and display results. It is also 
     * useful that you are able to put $object->var syntax directly inside print statements 
     * without having to worry about causing php parsing errors.
     * 
     *     // Extract results into the array $users (and evaluate if there are any results at the same time)..
     *     if ( $users = $db->get_results("SELECT name, email FROM users") )
     *     {
     *         // Loop through the resulting array on the index $users[n]
     *         foreach ( $users as $user )
     *         {
     *             // Access data using column names as associative array keys
     *             echo "$user->name - $user->email<br>";
     *         }
     *     }
     *     else
     *     {
     *         // If no users were found then if evaluates to false.
     *         echo "No users found.";
     *     }
     * 
     *     Output:    Amy - amy@hotmail.com
     *                Tyson - tyson@hotmail.com
     * 
     * 
     * Example 2 � Return results as associative array
     * 
     * Returning results as an associative array is useful if you would like dynamic access to 
     * column names. Here is an example.
     * 
     *     // Extract results into the array $dogs (and evaluate if there are any results at the same time).
     *     if ( $dogs = $db->get_results("SELECT breed, owner, name FROM dogs", ARRAY_A) )
     *     {
     *         // Loop through the resulting array on the index $dogs[n]
     *         foreach ( $dogs as $dog_detail )
     *         {
     *             // Loop through the resulting array
     *             foreach ( $dogs_detail as $key => $val )
     *             {
     *                 // Access and format data using $key and $val pairs.
     *                 echo "<b>" . ucfirst($key) . "</b>: $val<br>";
     *             }
     * 
     *             // Do a P between dogs.
     *             echo "<p>";
     *         }
     *     }
     *     else
     *     {
     *         // If no users were found then if evaluates to false..
     *         echo "No dogs found.";
     *     }
     * 
     *     Output:    Breed: Boxer
     *                Owner: Amy
     *                Name: Tyson
     * 
     *                Breed: Labrador
     *                Owner: Lee
     *                Name: Henry
     * 
     *                Breed: Dachshund
     *                Owner: Mary
     *                Name: Jasmine
     * 
     * 
     * Example 3 � Return results as numerical array
     * 
     * Returning results as a numerical array is useful if you are using completely dynamic 
     * queries with varying column names but still need a way to get a handle on the results. Here 
     * is an example of this concept in use. Imagine that this script was responding to a form 
     * with $type being submitted as either �fish� or �dog�.
     * 
     *     // Create an associative array for animal types.
     *     $animal = array ( "fish" => "num_fins", "dog" => "num_legs" );
     * 
     *     // Create a dynamic query on the fly.
     *     if ( $results = $db->("SELECT $animal[$type] FROM $type",ARRAY_N))
     *     {
     *         foreach ( $results as $result )
     *         {
     *             echo "$result[0]<br>";
     *         }
     *     }
     *     else
     *     {
     *         echo "No $animal\s!";
     *     }
     * 
     *     Output:    4
     *                4
     *                4
     * 
     *     Note: The dynamic query would be look like one of the following:
     * 
     *         SELECT num_fins FROM fish
     *         SELECT num_legs FROM dogs
     * 
     *     It would be easy to see which it was by using $db->debug(); after the dynamic query call.
     * 
     * @param  string
     * @param  constant
     * @return array
     * @access public
     */
    public function getResults($query = null, $output = OBJECT)
    {
        // Log how the function was called
        $this->func_call = "\$db->get_results(\"$query\", $output)";

        // If there is a query then perform it if not then use cached results.
        if ($query) {
            $this->query($query);
        }

        // Send back array of objects. Each row is an object.
        if ($output == OBJECT) {
            return $this->last_result;
        } elseif ($output == ARRAY_A || $output == ARRAY_N) {
            if ($this->last_result) {
                $i = 0;
                foreach ($this->last_result as $row) {
                    $new_array[$i] = get_object_vars($row);

                    if ($output == ARRAY_N) {
                        $new_array[$i] = array_values($new_array[$i]);
                    }

                    $i++;
                }

                return $new_array;
            } else {
                return null;
            }
        }
    }


    /**
     * @deprecated
     * @see        getColumnInfo()
     */
    public function get_col_info($info_type = 'name', $col_offset = -1)
    {
        return $this->getColumnInfo($info_type, $col_offset);
    }


    // ==================================================================
    // Function to get column meta data info pertaining to the last query
    // see docs for more info and usage
    public function getColumnInfo($info_type = 'name', $col_offset = -1)
    {
        if ($this->col_info) {
            if ($col_offset == -1) {
                $i = 0;
                foreach ($this->col_info as $col) {
                    $new_array[$i] = $col->{$info_type};
                    $i++;
                }
                return $new_array;
            } else {
                return $this->col_info[$col_offset]->{$info_type};
            }
        }
    }


    /**
     * print the contents and structure of any variable
     * 
     * $db->vardump() prints the contents and structure of any variable. It does not 
     * matter what the structure is be it an object, associative array or numerical array.
     * 
     * Dumps the contents of any input variable to screen in a nicely
     * formatted and easy to understand way - any type: Object, Var or Array
     * 
     * @access public
     */
    public function vardump($mixed = '')
    {
        echo "<p><table><tr><td bgcolor=\"#FFFFFF\"><blockquote><font color=\"#000090\">";
        echo "<pre><font face=\"arial\">";

        if (!$this->vardump_called) {
            echo "<font color=\"#800080\"><b>ezSQL</b> (v" . EZSQL_VERSION . ") <b>Variable Dump.</b></font>\n\n";
        }

        $var_type = gettype($mixed);
        print_r(($mixed ? $mixed : "<font color=\"red\">No Value / False</font>"));

        echo "\n\n<b>Type:</b> " . ucfirst($var_type) . "\n";
        echo "<b>Last Query</b> [" . $this->num_queries . "]<b>:</b> " . ($this->last_query ? $this->last_query : "NULL") . "\n";
        echo "<b>Last Function Call:</b> " . ($this->func_call ? $this->func_call : "None") . "\n";
        echo "<b>Last Rows Returned:</b> " . count($this->last_result) . "\n";
        echo "</font></pre></font></blockquote></td></tr></table>";
        echo "\n<hr size=\"1\" noshade color=\"#DDDDDD\">";

        $this->vardump_called = true;
    }


    /**
     * @deprecated
     * Alias for vardump().
     * @see        vardump.
     */
    public function dumpvar($mixed)
    {
        $this->vardump($mixed);
    }


    /**
     * Print last sql query and returned results (if any)
     * 
     * Displays the last query string that was sent to the database & a table listing results 
     * (if there were any). 
     * 
     * (abstracted into a seperate file to save server overhead).
     * 
     * @access public
     */
    public function debug()
    {
        echo "<blockquote>\n";

        // Only show ezSQL credits once
        if (!$this->debug_called) {
            echo "<font color=\"#800080\" face=\"arial\" size=\"2\"><b>ezSQL</b> (v" . EZSQL_VERSION . ") <b>Debug...</b></font><p>\n";
        }
        echo "<font face=\"arial\" size=\"2\" color=\"#000099\"><b>Query</b> [$this->num_queries] <b>--</b> ";
        echo "[<font color=\"#000000\"><b>$this->last_query</b></font>]</font><p>";
        echo "<font face=\"arial\" size=\"2\" color=\"#000099\"><b>Query Result...</b></font>\n";
        echo "<blockquote>\n";

        if ($this->col_info) {
            // Results top rows
            echo "<table cellpadding=\"5\" cellspacing=\"1\" bgcolor=\"#555555\">\n";
            echo "<tr bgcolor=\"#eeeeee\"><td nowrap valign=\"bottom\"><font color=\"#555599\" face=\"arial\" size=\"2\"><b>(row)</b></font></td>\n";

            for ($i = 0; $i < count($this->col_info); $i++) {
                echo "<td nowrap align=\"left\" valign=\"top\">\n";
                echo "<font size=\"1\" color=\"#555599\" face=\"arial\">{$this->col_info[$i]->type} {$this->col_info[$i]->max_length}</font>";
                echo "<br /><span style=\"font-family: arial; font-size: 10pt; font-weight: bold\">{$this->col_info[$i]->name}</span>";
                echo "</td>";
            }

            echo "</tr>\n";

            // Print main results
            if ($this->last_result) {

                $i = 0;
                foreach ($this->get_results(null, ARRAY_N) as $one_row) {
                    $i++;
                    echo "<tr bgcolor=ffffff><td bgcolor=eeeeee nowrap align=middle><font size=2 color=555599 face=arial>$i</font></td>";

                    foreach ($one_row as $item) {
                        echo "<td nowrap><font face=arial size=2>$item</font></td>";
                    }

                    echo "</tr>";
                }
            } // if last result
            else {
                echo "<tr bgcolor=ffffff><td colspan=" . (count($this->col_info) + 1) . "><font face=arial size=2>No Results</font></td></tr>";
            }

            echo "</table>\n";
        }
        // If col_info
        else {
            echo "<font face=\"arial\" size=\"2\">No Results</font>\n";
        }

        echo "</blockquote></blockquote><hr noshade color=\"#dddddd\" size=\"1\">\n";
        $this->debug_called = true;
    }
}
