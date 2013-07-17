<?php

/**
 * Description of Resource_DB_MySQL
 *
 * @author Holger Szüsz <hszuesz@live.com>
 */
class Resource_DB_MySQL implements IResource
{
    private $_resDb;

    /**
     *
     * @throws Resource_Exception
     */
    public function __construct() {
        $this->_resDb = mysql_connect(RESOURCE_DB_HOST . ':' . RESOURCE_DB_PORT, RESOURCE_DB_USER, RESOURCE_DB_PASSWORD);

        if($this->_resDb === false) {
            throw new Resource_Exception('Can\'t connect to host "' . RESOURCE_DB_HOST . ':' . RESOURCE_DB_PORT . '": ' . mysql_error());
        }

        if(!mysql_selectdb(RESOURCE_DB_NAME, $this->_resDb)) {
            throw new Resource_Exception('Can\'t select database "' . RESOURCE_DB_NAME . '": ' . mysql_error());
        }
    }

    /**
     *
     */
    public function __destruct() {
        mysql_close($this->_resDb);
    }

    /**
     *
     * @param String $strQuery
     * @return boolean
     * @throws Resource_Exception
     */
    public function exec($strQuery) {
        if (mysql_query($strQuery, $this->_resDb)) {
            return true;
        } else {
            throw new Resource_Exception('MySQL Query execute error: ' . mysql_error() . ' with SQL: $strQuery');
        }
    }

    /**
     *
     * @param String $strQuery
     * @param boolean $blnArray
     * @return resource|Array
     * @throws Resource_Exception
     */
    public function read($strQuery, $blnArray = false) {
        $objResultset = mysql_query($strQuery, $this->_resDb);

        if($objResultset === false) {
            throw new Resource_Exception('MySQL Query execute error: ' . mysql_error() . ' with SQL: $strQuery');
        }

        if($blnArray) {
            return $this->_toArray($objResultset);
        }

        return $objResultset;
    }

    /**
     *
     * @param String $strQuery
     * @return Array
     */
    public function readSingle($strQuery) {
        return $this->read($strQuery, true);
    }

    public function update($arrFieldList, $strScope, $arrConditions = array()) {
        if (empty($arrFieldList)) {
            throw new Resource_Exception('Fieldlist is empty!');
        }

        if ($strScope === '') {
            throw new Resource_Exception('Table is empty!');
        }

        $arrValues      = array();
        $arrSelectors   = array();
        $strSql         = 'UPDATE ' . $strScope . ' SET %s %s';

        foreach ($arrFieldList as $strFieldName => $mixFieldValue) {
            $arrValues[] = $strFieldName . ' = \'' . $mixFieldValue . '\'';
        }

        $blnFirst = true;
        if (!empty($arrConditions)) {
            foreach ($arrConditions as $strFieldName => $arrConditionParams) {
                if ($blnFirst) {
                    $arrSelectors[] = 'WHERE ' . $strFieldName . ' ' . $arrConditionParams['operator'] . ' \'' . $arrConditionParams['value'] . '\'';
                    $blnFirst = false;
                } else {
                    $arrSelectors[] = 'AND ' . $strFieldName . ' ' . $arrConditionParams['operator'] . ' \'' . $arrConditionParams['value'] . '\' ';
                }
            }
        }

        return $this->exec(sprintf($strSql, implode(', ', $arrValues), implode('', $arrSelectors)));
    }

    /**
     * Do Insert into DB
     *
     * If you have two or more Rows to Insert, pass an Array like this:
     * 		array(
     * 			0 => array('uid', 'strName'),	//field lisst
     * 			1 => array(0, 'Name1'),			//row1
     * 			2 => array(1, 'Name2')			//row2
     * If you have only one row to insert, pass an Array like this:
     * 		array(
     * 			'uid'		=> 0,
     * 			'strName'	=> 'Name1'
     * 		)
     *
     * @param Array $arrFieldList
     * @param String $strScope
     * @return Boolean
     */
    public function insert($arrFieldList, $strScope) {
        if (empty($arrFieldList)) {
            throw new Resource_Exception('Fieldlist is empty!');
        }

        if ($strScope == '') {
            throw new Resource_Exception('Table is empty!');
        }

        $arrValues  = array();
        $strSql     = 'INSERT INTO ' . $strScope . ' SET %s';

        if (!is_string(array_shift(array_keys($arrFieldList)))) {
            $arrFieldnames = array_shift($arrFieldList);
            $strSql = str_replace('SET', '(' . implode(', ', $arrFieldnames) . ') VALUES ', $strSql);

            foreach ($arrFieldList as $arrRow) {
                $arrValues[] = '("' . implode('", "', $arrRow) . '")';
            }
        } else {
            foreach ($arrFieldList as $strFieldName => $mixFieldValue) {
                $arrValues[] = $strFieldName . ' = "' . $mixFieldValue . '"';
            }
        }

        return $this->exec(sprintf($strSql, implode(', ', $arrValues)));
    }

    private function __clone() {
        ;
    }

    /**
     *
     * @param resource $objResultset
     * @return Array
     * @throws Resource_Exception
     */
    private function _toArray($objResultset) {
        $arrRows = mysql_fetch_assoc($objResultset);

        if($arrRows === false) {
            throw new Resource_Exception('MySQL fetch assoc error: ' . mysql_error());
        }

        return $arrRows;
    }
}