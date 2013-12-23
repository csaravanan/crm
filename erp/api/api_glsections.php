<?php
/* $Id: api_glsections.php 5768 2012-12-20 08:38:22Z daintree $*/

/* Check that the account section doesn't already exist'*/
	function VerifyAccountSection($AccountSection, $i, $Errors, $db) {
		$Searchsql = "SELECT count(sectionid)
				FROM accountsection
				WHERE sectionid='".$AccountSection."'";
		$SearchResult=DB_query($Searchsql, $db);
		$answer = DB_fetch_array($SearchResult);
		if ($answer[0]>0) {
			$Errors[$i] = GLAccountSectionAlreadyExists;
		}
		return $Errors;
	}

/* Check that the name is 256 characters or less long */
	function VerifySectionName($SectionName, $i, $Errors) {
		if (mb_strlen($SectionName)>256) {
			$Errors[$i] = IncorrectSectionNameLength;
		}
		return $Errors;
	}

	function InsertGLAccountSection($AccountSectionDetails, $user, $password) {
		$Errors = array();
		$db = db($user, $password);
		if (gettype($db)=='integer') {
			$Errors[0]=NoAuthorisation;
			return $Errors;
		}
		foreach ($AccountSectionDetails as $key => $value) {
			$AccountSectionDetails[$key] = DB_escape_string($value);
		}
		$Errors=VerifyAccountSection($AccountSectionDetails['sectionname'], sizeof($Errors), $Errors, $db);
		if (isset($AccountSectionDetails['accountname'])){
			$Errors=VerifySectionName($AccountSectionDetails['sectionname'], sizeof($Errors), $Errors);
		}
		$FieldNames='';
		$FieldValues='';
		foreach ($AccountSectionDetails as $key => $value) {
			$FieldNames.=$key.', ';
			$FieldValues.='"'.$value.'", ';
		}
		if (sizeof($Errors)==0) {
			$sql = "INSERT INTO accountsection ('" . mb_substr($FieldNames,0,-2) . "')
					VALUES ('" . mb_substr($FieldValues,0,-2) . "')";
			$result = DB_Query($sql, $db);
			if (DB_error_no($db) != 0) {
				$Errors[0] = DatabaseUpdateFailed;
			} else {
				$Errors[0]=0;
			}
		}
		return $Errors;
	}
?>